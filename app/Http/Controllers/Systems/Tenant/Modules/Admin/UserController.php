<?php

namespace App\Http\Controllers\Panel;

#region Import Libraries
use App\Http\Controllers\Controller as Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Libs\Utils;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use App\Libs\Enums\EnumOrderBy;
use App\Libs\Enums\EnumErrorsType;
use App\Libs\Enums\EnumPermissionsLevel;
#endregion

#region Import Requests
use App\Http\Requests\Panel\SaveUsersRequest;
use App\Http\Requests\Panel\UpdateUsersRequest;
#endregion

#region Import Services
use App\Services\Crud\CrudUserService;
use App\Services\Crud\CrudCitieService;
use App\Services\Crud\CrudStateService;
use App\Services\Crud\CrudLogAuditService;
use App\Services\Crud\CrudParameterService;
use App\Services\Panel\Rules\RulesMaintenanceService;
#endregion

#region Import Models
use App\Models\User;
#endregion

#region Import Jobs
#endregion

/**
 * Controller responsible for managing user settings in the administration panel.
 */
class UserController extends Controller
{
    #region variables
    protected $crudParameterService;
    protected $crudUserService;
    protected $crudLogAuditService;
    protected $crudStateService;
    protected $crudCitieService;
    protected $rulesMaintenanceService;
    #endregion

    #region _construct
    /**
     * Class constructor, initializes necessary services and sets up permission middlewares.
     *
     * @param CrudParameterService $crudParameterService Service for handling parameters.
     * @param CrudUserService $crudUserService Service for handling user data.
     * @param CrudLogAuditService $crudLogAuditService Service for handling audit logs.
     * @param CrudStateService $crudStateService Service for handling states.
     * @param CrudCitieService $crudCitieService Service for handling cities.
     * @param RulesMaintenanceService $rulesMaintenanceService Service for maintenance rules.
     * @return void
     */
    public function __construct(
        CrudParameterService $crudParameterService,
        CrudUserService $crudUserService,
        CrudLogAuditService $crudLogAuditService,
        CrudStateService $crudStateService,
        CrudCitieService $crudCitieService,
        RulesMaintenanceService $rulesMaintenanceService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudUserService = $crudUserService;
        $this->crudLogAuditService = $crudLogAuditService;
        $this->crudStateService = $crudStateService;
        $this->crudCitieService = $crudCitieService;
        $this->rulesMaintenanceService = $rulesMaintenanceService;

        $this->middleware("can:read_users")->only(["usersList", "usersFilters"]);
        $this->middleware("can:read_users_audit")->only(["getUserHistory"]);
        $this->middleware("can:create_users")->only(["usersMaintenance", "usersStore"]);
        $this->middleware("can:update_users")->only(["usersMaintenance", "usersUpdate"]);
        $this->middleware("can:delete_users")->only(["usersDelete"]);
    }
    #endregion

    /**
     * Lists all available users.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the view with the list of users or a JSON response in case of an error.
     */
    public function usersList()
    {
        #region content
        try {
            $pageTitle = ViewsModules::PANEL_USERS;
            $parameters = $this->crudParameterService->findById(1);

            return view("panel.pages.users.users_list", compact("pageTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_USERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_USERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Filters users based on request parameters.
     *
     * @param Request $request Object containing request parameters.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with filtered user data.
     */
    public function usersFilters(Request $request)
    {
        #region content
        try {
            $query = User::query();

            if (Auth::user()->roles_id == EnumPermissionsLevel::MANAGER) {
                $query = $query->where("users.roles_id", EnumPermissionsLevel::ANALIST);
            }

            if ($request->selFilterStatus != 2) {
                $query->where("active", $request->selFilterStatus);
            }

            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "id",
                1 => "name",
                2 => "email",
                3 => "roles_id",
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
                ->addColumn("actions", function ($item) {
                    return view("panel.pages.users._includes.actions_grid", compact("item"))->render();
                })
                ->addColumn("active", function ($item) {
                    return "<span class='badge " .
                        ($item->active ? "bg-success" : "bg-danger") .
                        "'>" .
                        ($item->active ? "Ativo" : "Inativo") .
                        "</span>";
                })
                ->addColumn("roles_id", function ($item) {
                    return match ($item->roles->id) {
                        EnumPermissionsLevel::ADMIN => "ADMINISTRADOR",
                        EnumPermissionsLevel::MANAGER => "GERENTE",
                        EnumPermissionsLevel::ANALIST => "ANALISTA",
                        EnumPermissionsLevel::CLIENT => "CLIENTE",
                        default => "NÃO IDENTIFICADO",
                    };
                })
                ->rawColumns(["actions", "active"])
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_USERS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_USERS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Retrieves the audit history of a user.
     *
     * @param int $userId ID of the user to retrieve audit history for.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with audit history data.
     */
    public function getUserHistory($userId)
    {
        #region content
        try {
            Utils::maxOptimizations();
            $userAudit = $this->crudLogAuditService->getAll(
                ["table_name" => "users", "table_item_id" => $userId],
                ["created_at" => EnumOrderBy::DESC]
            );

            return response()->json([
                "status" => 1,
                "grid" => view("panel.pages.users._includes.tbody_audit_modal", compact("userAudit"))->render(),
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                2,
                ViewsModules::PANEL_USERS,
                Actions::GET_INFO,
                $e->getCode(),
                $e->getMessage()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                3,
                ViewsModules::PANEL_USERS,
                Actions::GET_INFO,
                $e->getCode(),
                $e->getMessage()
            );
        }
        #endregion
    }

    /**
     * Displays the maintenance screen for creating or editing a user.
     *
     * @param int $id ID of the user. If 0, it indicates a new entry.
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the maintenance view or a JSON response in case of an error.
     */
    public function usersMaintenance($id = 0)
    {
        #region content
        try {
            $pageTitle = ViewsModules::PANEL_USERS;
            $parameters = $this->crudParameterService->findById(1);
            $user = $this->rulesMaintenanceService->getRegisterMaintenance($this->crudUserService, $id);
            $states = $this->crudStateService->getAll([], ["initials" => "asc"]);

            $cities = null;

            if ($user != null) {
                $cities = $this->crudCitieService->getAll(["states_id" => $user->cities->states_id]);
            }

            $subTitle = "Novo Cadastro";
            if ($user != null) {
                $subTitle = $user->name;
            }

            return view(
                "panel.pages.users.users_maintenance",
                compact("pageTitle", "subTitle", "parameters", "user", "states", "cities")
            );
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_USERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_USERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Stores a new user in the database.
     *
     * @param SaveUsersRequest $request Object containing validated request data.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response indicating success or failure.
     */
    public function usersStore(SaveUsersRequest $request)
    {
        #region content
        try {
            $this->crudUserService->save($request->all());
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_USERS,
                Actions::SAVE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_USERS,
                Actions::SAVE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Updates an existing user.
     *
     * @param UpdateUsersRequest $request Object containing validated request data.
     * @param int $id ID of the user to be updated.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response indicating success or failure.
     */
    public function usersUpdate(UpdateUsersRequest $request, $id)
    {
        #region content
        try {
            $this->crudUserService->updateUser($id, $request);
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_USERS,
                Actions::UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_USERS,
                Actions::UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Deletes a user from the database.
     *
     * @param int $id ID of the user to be deleted.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response indicating success or failure.
     */
    public function usersDelete($id)
    {
        #region content
        try {
            $this->crudUserService->delete($id);
            $user = $this->crudParameterService->getUserAuth();
            $listUsers = $this->crudUserService->getUsersByLevel($user, "name", EnumOrderBy::ASC);

            return response()->json([
                "status" => 1,
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_USERS,
                Actions::DELETE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_USERS,
                Actions::DELETE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }
}
