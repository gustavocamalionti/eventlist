<?php

namespace App\Http\Controllers\Panel;

#region Import Libraries
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;
use App\Libs\Utils;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use App\Libs\Enums\EnumForms;
use App\Libs\Enums\EnumErrorsType;
#endregion

#region Import Requests
#endregion

#region Import Services
use App\Services\Crud\CrudLinkService;
use App\Services\Crud\CrudCitieService;
use App\Services\Crud\CrudStateService;
use App\Services\Crud\CrudStoreService;
use App\Services\Crud\CrudLogAuditService;
use App\Services\Crud\CrudParameterService;
use App\Services\Panel\Rules\RulesFilesService;
use App\Services\Panel\Rules\RulesMaintenanceService;
#endregion

#region Import Models
use App\Models\Citie;
use App\Models\State;
use App\Models\Store;
use App\Models\FormContent;
use App\Services\Crud\CrudFormSubjectService;

#endregion

#region Import Jobs
#endregion

/**
 * Controller responsible for managing the contact form content in the administration panel.
 */
class FormContentContactController extends Controller
{
    #region variables
    protected $crudParameterService;
    protected $crudLinkService;
    protected $crudStoreService;
    protected $crudStateService;
    protected $crudCitieService;
    protected $crudLogAuditService;

    protected $rulesArchivedService;
    protected $rulesFilesService;
    protected $rulesMaintenanceService;
    #endregion

    #region _construct
    /**
     * Class constructor, initializes necessary services and sets up permission middlewares.
     *
     * @param CrudParameterService $crudParameterService Service for handling parameters.
     * @param CrudLinkService $crudLinkService Service for handling links.
     * @param CrudStoreService $crudStoreService Service for handling stores.
     * @param CrudStateService $crudStateService Service for handling states.
     * @param CrudCitieService $crudCitieService Service for handling cities.
     * @param CrudLogAuditService $crudLogAuditService Service for handling audit logs.
     * @param RulesFilesService $rulesFilesService Service for file validation rules.
     * @param RulesMaintenanceService $rulesMaintenanceService Service for maintenance rules.
     * @return void
     */
    public function __construct(
        CrudParameterService $crudParameterService,
        CrudLinkService $crudLinkService,
        CrudStoreService $crudStoreService,
        CrudStateService $crudStateService,
        CrudCitieService $crudCitieService,
        CrudLogAuditService $crudLogAuditService,
        RulesFilesService $rulesFilesService,
        RulesMaintenanceService $rulesMaintenanceService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudLinkService = $crudLinkService;
        $this->crudStoreService = $crudStoreService;
        $this->crudStateService = $crudStateService;
        $this->crudCitieService = $crudCitieService;
        $this->crudLogAuditService = $crudLogAuditService;
        $this->rulesFilesService = $rulesFilesService;
        $this->rulesMaintenanceService = $rulesMaintenanceService;

        $this->middleware("can:read_form_contents_contact")->only(["formContactsList", "formContactsFilters"]);
    }
    #endregion

    /**
     * Lists all available contact form contents.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the view with the list of contacts or a JSON response in case of an error.
     */
    public function formContactsList()
    {
        #region content
        try {
            $pageTitle = ViewsModules::FORM_CONTACT;
            $parameters = $this->crudParameterService->findById(1);

            return view("panel.pages.forms.form_contacts_list", compact("pageTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_CONTACT,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_CONTACT,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Filters contact form contents based on request parameters.
     *
     * @param Request $request Object containing request parameters.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with filtered data.
     */
    public function formContactsFilters(Request $request)
    {
        #region content
        try {
            $query = FormContent::with(["formSubject.form"]) // Corrigido o relacionamento
                ->whereHas("formSubject.form", function ($q) {
                    $q->where("id", EnumForms::CONTACT);
                });

            // Criando uma data válida no formato Y-m-d
            $dateStart = null;
            if (!empty($request->start)) {
                list($monthStart, $yearStart) = explode("/", $request->start);
                $dateStart = "{$yearStart}-{$monthStart}-01 00:00:00";
            }

            $dateEnd = null;
            if (!empty($request->end)) {
                list($monthEnd, $yearEnd) = explode("/", $request->end);
                $dateEnd = "{$yearEnd}-{$monthEnd}-01 00:00:00";
            }

            if ($dateStart && $dateEnd) {
                $query->whereBetween("created_at", [$dateStart, $dateEnd]);
            } elseif ($dateStart) {
                $query->where("created_at", ">=", $dateStart);
            } elseif ($dateEnd) {
                $query->where("created_at", "<=", $dateEnd);
            }

            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                1 => "form_subjects_id",
                2 => "cpf",
                3 => "name",
                4 => "email",
                5 => "stores_id",
                6 => "message",
                7 => "phone_cell",
                8 => "cities_id",
                9 => "states_id",
                10 => "stores_id",
                11 => "policy_policy",
                12 => "created_at",
            ];

            $orderColumnIndex = $request->input("order.0.column");
            $orderDirection = $request->input("order.0.dir", "asc");

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            } else {
                $query->orderBy("created_at", "DESC");
            }

            return DataTables::of($query)
                ->addColumn("stores_id", function ($item) {
                    return isset(json_decode($item["content"])->stores_id)
                        ? Store::find(json_decode($item["content"])->stores_id)->name
                        : "-";
                })

                ->addColumn("phone_cell", function ($item) {
                    return Utils::MaskPhone(json_decode($item["content"])->phone_cell);
                })
                ->addColumn("message", function ($item) {
                    $message = $item->message;
                    return view("panel.layouts._modal_message", compact("message"));
                })
                ->addColumn("cpf", function ($item) {
                    if (array_key_exists("cpf", (array) json_decode($item["content"]))) {
                        return Utils::MaskFields(json_decode($item["content"])->cpf, "###.###.###-##");
                    }
                    return "-";
                })

                ->addColumn("cities_id", function ($item) {
                    return Citie::find(json_decode($item["content"])->cities_id)->name;
                })

                ->addColumn("states_id", function ($item) {
                    return State::find(json_decode($item["content"])->states_id)->initials;
                })

                ->addColumn("privacy_policy", function ($item) {
                    return $item->privacy_policy == 1 ? "Concorda" : "Não Concorda";
                })

                ->addColumn("form_subjects_id", function ($item) {
                    return app(CrudFormSubjectService::class)->findById($item->form_subjects_id)->form->name .
                        " - " .
                        app(CrudFormSubjectService::class)->findById($item->form_subjects_id)->name;
                })

                ->addColumn("created_at", function ($item) {
                    return date("d/m/Y H:i:s", strtotime($item->created_at));
                })

                ->rawColumns(["active", "cities_id", "states_id", "created_at", "phone_cell", "message"])
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_CONTACT,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_CONTACT,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }
}
