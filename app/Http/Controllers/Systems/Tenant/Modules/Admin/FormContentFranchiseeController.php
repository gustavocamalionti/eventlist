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
#endregion

#region Import Jobs
#endregion

/**
 * Controller responsible for managing franchise form content in the administrative area.
 */
class FormContentFranchiseeController extends Controller
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
     * Constructor of the class, initializes necessary services, and configures permission middlewares.
     *
     * @param CrudParameterService $crudParameterService Service responsible for managing parameters.
     * @param CrudLinkService $crudLinkService Service responsible for managing links.
     * @param CrudStoreService $crudStoreService Service responsible for managing stores.
     * @param CrudStateService $crudStateService Service responsible for managing states.
     * @param CrudCitieService $crudCitieService Service responsible for managing cities.
     * @param CrudLogAuditService $crudLogAuditService Service responsible for managing audit logs.
     * @param RulesFilesService $rulesFilesService Service responsible for file validation rules.
     * @param RulesMaintenanceService $rulesMaintenanceService Service responsible for maintenance rules.
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

        $this->middleware("can:read_form_contents_franchisee")->only(["formFranchiseesList", "formFranchiseesFilters"]);
    }
    #endregion

    /**
     * Lists all franchise form contents.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the view with the franchise list or a JSON response in case of error.
     */
    public function formFranchiseesList()
    {
        #region content
        try {
            $pageTitle = ViewsModules::FORM_FRANCHISEE;
            $parameters = $this->crudParameterService->findById(1);

            return view("panel.pages.forms.form_franchisees_list", compact("pageTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_FRANCHISEE,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_FRANCHISEE,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Filters franchise form contents based on the request parameters.
     *
     * @param Request $request The request object containing the filtering parameters.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with the filtered data.
     */
    public function formFranchiseesFilters(Request $request)
    {
        #region content
        try {
            $query = FormContent::with(["formSubject.form"]) // Corrigido o relacionamento
                ->whereHas("formSubject.form", function ($q) {
                    $q->where("id", EnumForms::FRANCHISEE);
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

            // if ($request->selFilterStatus != 2) {
            //     $query->where("active", $request->selFilterStatus);
            // }
            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "name",
                1 => "email",
                2 => "stores_id",
                3 => "subject",
                4 => "form_subjects_id",
                5 => "message",
                6 => "phone_cell",
                6 => "phone",
                7 => "cities_id",
                8 => "states_id",
                9 => "stores_id",
                10 => "policy_policy",
                11 => "created_at",
                12 => "updated_at",
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
                ->addColumn("phone", function ($item) {
                    if (array_key_exists("phone", (array) json_decode($item["content"]))) {
                        return Utils::MaskPhone(json_decode($item["content"])->phone);
                    }
                    return "-";
                })

                ->addColumn("cities_live_id", function ($item) {
                    return Citie::find(json_decode($item["content"])->cities_live_id)->name;
                })
                ->addColumn("states_live_id", function ($item) {
                    return State::find(json_decode($item["content"])->states_live_id)->initials;
                })

                ->addColumn("cities_intention_id", function ($item) {
                    return Citie::find(json_decode($item["content"])->cities_intention_id)->name;
                })
                ->addColumn("states_intention_id", function ($item) {
                    return State::find(json_decode($item["content"])->states_intention_id)->initials;
                })

                ->addColumn("privacy_policy", function ($item) {
                    return $item->privacy_policy == 1 ? "Concorda" : "Não Concorda";
                })
                ->addColumn("capital_intention", function ($item) {
                    return json_decode($item["content"])->capital_intention;
                })

                // ->addColumn("active", function ($item) {
                //     return "<span class='badge " . ($item->active ? "bg-success" : "bg-danger") . "'>" . ($item->active ? 'Ativo' : 'Inativo') . "</span>";
                // })

                ->addColumn("created_at", function ($item) {
                    return date("d/m/Y H:i:s", strtotime($item->created_at));
                })
                ->rawColumns([
                    "active",
                    "cities_live_id",
                    "states_live_id",
                    "cities_intention_id",
                    "states_intention_id",
                    "created_at",
                    "phone_cell",
                    "phone",
                    "capital_intention",
                ])
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_FRANCHISEE,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_FRANCHISEE,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }
}
