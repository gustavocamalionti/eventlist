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
use App\Libs\Enums\EnumOrderBy;
use App\Libs\Enums\EnumErrorsType;
#endregion

#region Import Requests
use App\Http\Requests\Panel\SaveFormConfigsRequest;
use App\Http\Requests\Panel\UpdateFormConfigsRequest;
#endregion

#region Import Services
use App\Services\Crud\CrudFormService;
use App\Services\Crud\CrudCitieService;
use App\Services\Crud\CrudStateService;
use App\Services\Crud\CrudLogAuditService;
use App\Services\Crud\CrudParameterService;
use App\Services\Crud\CrudFormConfigService;
use App\Services\Crud\CrudFormSubjectService;
use App\Services\Panel\Rules\RulesMaintenanceService;
#endregion

#region Import Models
use App\Models\FormConfig;

#endregion

#region Import Jobs

#endregion

/**
 * Controller responsible for managing the email trigger settings for forms in the administration panel.
 */
class FormConfigController extends Controller
{
    #region variables
    protected $crudParameterService;
    protected $crudFormConfigService;
    protected $crudFormService;
    protected $crudFormSubjectService;
    protected $crudStateService;
    protected $crudCitieService;
    protected $crudLogAuditService;

    protected $rulesArchivedService;
    protected $rulesMaintenanceService;
    #endregion

    #region _construct
    /**
     * Class constructor, initializes necessary services and sets up permission middlewares.
     *
     * @param CrudParameterService $crudParameterService Service for handling parameters.
     * @param CrudFormConfigService $crudFormConfigService Service for handling form configurations.
     * @param CrudFormService $crudFormService Service for handling forms.
     * @param CrudFormSubjectService $CrudFormSubjectService Service for handling form subjects.
     * @param CrudStateService $crudStateService Service for handling states.
     * @param CrudCitieService $crudCitieService Service for handling cities.
     * @param CrudLogAuditService $crudLogAuditService Service for handling audit logs.
     * @param RulesMaintenanceService $rulesMaintenanceService Service for maintenance rules.
     * @return void
     */
    public function __construct(
        CrudParameterService $crudParameterService,
        CrudFormConfigService $crudFormConfigService,
        CrudFormService $crudFormService,
        CrudFormSubjectService $crudFormSubjectService,
        CrudStateService $crudStateService,
        CrudCitieService $crudCitieService,
        CrudLogAuditService $crudLogAuditService,
        RulesMaintenanceService $rulesMaintenanceService
    ) {
        $this->crudFormConfigService = $crudFormConfigService;
        $this->crudFormService = $crudFormService;
        $this->crudFormSubjectService = $crudFormSubjectService;
        $this->crudParameterService = $crudParameterService;
        $this->crudStateService = $crudStateService;
        $this->crudCitieService = $crudCitieService;
        $this->crudLogAuditService = $crudLogAuditService;
        $this->rulesMaintenanceService = $rulesMaintenanceService;

        $this->middleware("can:read_form_configs")->only(["formConfigsList", "formConfigsFilters"]);
        $this->middleware("can:read_form_configs_audit")->only(["getFormConfigsHistory"]);
        $this->middleware("can:create_form_configs")->only(["formConfigsMaintenance", "formConfigsStore"]);
        $this->middleware("can:update_form_configs")->only(["formConfigsMaintenance", "formConfigsUpdate"]);
        $this->middleware("can:delete_form_configs")->only(["formConfigsDelete"]);
    }
    #endregion

    /**
     * Lists all available form configurations.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the view with the list of configurations or a JSON response in case of an error.
     */
    public function formConfigsList()
    {
        #region content
        try {
            $pageTitle = ViewsModules::FORM_CONFIG;
            $parameters = $this->crudParameterService->findById(1);

            return view("panel.pages.forms.form_configs_list", compact("pageTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_CONFIG,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_CONFIG,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Filters form configurations based on request parameters.
     *
     * @param Request $request Object containing request parameters.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with filtered data.
     */

    public function formConfigsFilters(Request $request)
    {
        #region content
        try {
            $query = FormConfig::query();

            if ($request->selFilterStatus != 2) {
                $query->where("active", $request->selFilterStatus);
            }

            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "id",
                1 => "form_subject",
                2 => "name",
                3 => "email",
                4 => "active",
            ];

            $orderColumnIndex = $request->input("order.0.column");
            $orderDirection = $request->input("order.0.dir", "asc");

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            } else {
                $query->orderBy("name", "ASC");
            }

            return DataTables::of($query)
                ->addColumn("form", function ($item) {
                    return $item->formSubject->form->name;
                })
                ->addColumn("form_subject", function ($item) {
                    return $item->formSubject->name;
                })
                ->addColumn("active", function ($item) {
                    return "<span class='badge " .
                        ($item->active ? "bg-success" : "bg-danger") .
                        "'>" .
                        ($item->active ? "Ativo" : "Inativo") .
                        "</span>";
                })
                ->addColumn("actions", function ($item) {
                    return view("panel.pages.forms._includes.form_configs_actions_grid", compact("item"))->render();
                })
                ->rawColumns(["actions", "active"])
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_CONFIG,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_CONFIG,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Retrieves the audit history of form configurations.
     *
     * @param int $formConfigsId ID of the form configuration to retrieve history for.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with audit history data.
     */
    public function getFormConfigsHistory($formConfigsId)
    {
        #region content
        try {
            Utils::maxOptimizations();
            $formConfigAudit = $this->crudLogAuditService->getAll(
                ["table_name" => "form_configs", "table_item_id" => $formConfigsId],
                ["created_at" => EnumOrderBy::DESC]
            );

            return response()->json([
                "status" => 1,
                "grid" => view(
                    "panel.pages.forms._includes.form_configs_tbody_audit_modal",
                    compact("formConfigAudit")
                )->render(),
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                2,
                ViewsModules::FORM_CONFIG,
                Actions::GET_INFO,
                $e->getCode(),
                $e->getMessage()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                3,
                ViewsModules::FORM_CONFIG,
                Actions::GET_INFO,
                $e->getCode(),
                $e->getMessage()
            );
        }
        #endregion
    }

    /**
     * Displays the maintenance screen for creating or editing a form configuration.
     *
     * @param int $id ID of the form configuration. If 0, it indicates a new entry.
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the maintenance view or a JSON response in case of an error.
     */
    public function formConfigsMaintenance($id = 0)
    {
        #region content
        try {
            $pageTitle = ViewsModules::FORM_CONFIG;

            $parameters = $this->crudParameterService->findById(1);
            $formConfig = $this->rulesMaintenanceService->getRegisterMaintenance($this->crudFormConfigService, $id);
            $forms = $this->crudFormService->getAll([], ["name" => EnumOrderBy::ASC]);
            $subTitle = "Novo Cadastro";
            $formSubjects = [];
            if ($formConfig != null) {
                $subTitle = $formConfig->name;
                $formSubjects = $this->crudFormSubjectService->getAll([
                    "forms_id" => $formConfig->formSubject->forms_id,
                ]);
            }

            return view(
                "panel.pages.forms.form_configs_maintenance",
                compact("pageTitle", "subTitle", "parameters", "formConfig", "forms", "formSubjects")
            );
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_CONFIG,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_CONFIG,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Stores a new form configuration in the database.
     *
     * @param SaveFormConfigsRequest $request Object containing validated request data.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response indicating success or failure.
     */
    public function formConfigsStore(SaveFormConfigsRequest $request)
    {
        #region content
        try {
            $this->crudFormConfigService->save($request->all());
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_CONFIG,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_CONFIG,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Updates an existing form configuration.
     *
     * @param UpdateFormConfigsRequest $request Object containing validated request data.
     * @param int $id ID of the form configuration to be updated.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response indicating success or failure.
     */
    public function formConfigsUpdate(UpdateFormConfigsRequest $request, $id)
    {
        #region content
        try {
            $this->crudFormConfigService->update($id, $request->all());
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_CONFIG,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_CONFIG,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Deletes a form configuration from the database.
     *
     * @param int $id ID of the form configuration to be deleted.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response indicating success or failure.
     */
    public function formConfigsDelete($id)
    {
        #region content
        try {
            $this->crudFormConfigService->delete($id);

            return response()->json([
                "status" => 1,
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_CONFIG,
                Actions::DELETE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_CONFIG,
                Actions::DELETE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }
}
