<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Admin;

use App\Libs\Utils;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use Illuminate\Http\Request;
use App\Libs\Enums\EnumStatus;
use App\Libs\Enums\EnumOrderBy;
use Yajra\DataTables\DataTables;
use App\Libs\Enums\EnumErrorsType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\QueryException;
use App\Libs\Enums\Systems\Tenant\EnumTenantRoles;

use App\Models\Systems\Tenant\TenantUser;
use App\Http\Controllers\Common\Controller;
use App\Services\Common\Crud\CrudCitieService;
use App\Services\Common\Crud\CrudStateService;

use App\Services\Common\Crud\CrudLogAuditService;
use App\Services\Systems\Tenant\Crud\CrudRoleService;
use App\Services\Systems\Tenant\Crud\CrudUserService;
use App\Services\Common\Rules\RulesMaintenanceService;
use App\Services\Systems\Tenant\Crud\CrudParameterService;
use App\Http\Requests\Systems\Tenant\Modules\Admin\SaveUsersRequest;
use App\Http\Requests\Systems\Tenant\Modules\Admin\UpdateUsersRequest;

/**
 * Controller responsible for managing user settings in the administration panel.
 */
class UserController extends Controller
{
    protected $crudParameterService;
    protected $crudUserService;
    protected $crudLogAuditService;
    protected $crudStateService;
    protected $crudCitieService;
    protected $rulesMaintenanceService;
    protected $crudRoleService;

    public function __construct(
        CrudLogAuditService $crudLogAuditService,
        CrudStateService $crudStateService,
        CrudCitieService $crudCitieService,
        RulesMaintenanceService $rulesMaintenanceService,
        CrudParameterService $crudParameterService,
        CrudUserService $crudUserService,
        CrudRoleService $crudRoleService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudUserService = $crudUserService;
        $this->crudLogAuditService = $crudLogAuditService;
        $this->crudStateService = $crudStateService;
        $this->crudCitieService = $crudCitieService;
        $this->rulesMaintenanceService = $rulesMaintenanceService;
        $this->crudRoleService = $crudRoleService;

        $this->middleware("can:read_users")->only(["usersList", "usersFilters"]);
        $this->middleware("can:read_users_audit")->only(["getUserHistory"]);
        $this->middleware("can:create_users")->only(["usersMaintenance", "usersStore"]);
        $this->middleware("can:update_users")->only(["usersMaintenance", "usersUpdate"]);
        $this->middleware("can:delete_users")->only(["usersDelete"]);
    }

    /**
     * Lists all available users.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the view with the list of users or a JSON response in case of an error.
     */
    public function usersList()
    {
        try {
            $pageTitle = ViewsModules::PANEL_USERS;
            $parameters = $this->crudParameterService->findById(1);
            $viewPath = "legacy.systems.tenant.modules.admin.pages.users.users_list";
            return view($viewPath, compact("pageTitle", "parameters"));
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
    }

    /**
     * Filters users based on request parameters.
     *
     * @param Request $request Object containing request parameters.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with filtered user data.
     */
    public function usersFilters(Request $request)
    {
        try {
            $query = TenantUser::query();

            if (Auth::user()->roles_id == EnumTenantRoles::OWNER) {
                $query = $query->where("users.roles_id", "!=", EnumTenantRoles::ADMIN);
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
                    return view(
                        "legacy.systems.tenant.modules.admin.pages.users._includes.actions_grid",
                        compact("item")
                    )->render();
                })
                ->addColumn("active", function ($item) {
                    return "<span class='badge " .
                        ($item->active ? "bg-success" : "bg-danger") .
                        "'>" .
                        ($item->active ? "Ativo" : "Inativo") .
                        "</span>";
                })
                ->addColumn("roles_id", function ($item) {
                    return $item->roles->name;
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
    }

    /**
     * Retrieves the audit history of a user.
     *
     * @param int $userId ID of the user to retrieve audit history for.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with audit history data.
     */
    public function getUserHistory($userId)
    {
        try {
            Utils::maxOptimizations();
            $userAudit = $this->crudLogAuditService->getAll(
                ["table_name" => "users", "table_item_id" => $userId],
                ["created_at" => EnumOrderBy::DESC]
            );

            return response()->json([
                "status" => 1,
                "grid" => view(
                    "legacy.systems.tenant.modules.admin.pages.users._includes.tbody_audit_modal",
                    compact("userAudit")
                )->render(),
            ]);
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
    }

    /**
     * Displays the maintenance screen for creating or editing a user.
     *
     * @param int $id ID of the user. If 0, it indicates a new entry.
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the maintenance view or a JSON response in case of an error.
     */
    public function usersMaintenance($id = 0)
    {
        try {
            $pageTitle = ViewsModules::PANEL_USERS;
            $parameters = $this->crudParameterService->findById(1);
            $user = $this->rulesMaintenanceService->getRegisterMaintenance($this->crudUserService, $id);

            $states = $this->crudStateService->getAll([], ["initials" => "asc"]);

            $cities = null;
            $roles = $this->crudRoleService->getAll([], ["id" => EnumOrderBy::DESC]);

            if ($user != null && isset($user->cities)) {
                $cities = $this->crudCitieService->getAll(["states_id" => $user->cities->states_id]);
            }

            $subTitle = "Novo Cadastro";
            if ($user != null) {
                $subTitle = $user->name;
            }

            $viewPath = "legacy.systems.tenant.modules.admin.pages.users.users_maintenance";

            return view($viewPath, compact("pageTitle", "subTitle", "parameters", "user", "states", "cities", "roles"));
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
    }

    /**
     * Stores a new user in the database.
     *
     * @param SaveUsersRequest $request Object containing validated request data.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response indicating success or failure.
     */
    public function usersStore(SaveUsersRequest $request)
    {
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
        try {
            // dd($request->all());
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
    }

    /**
     * Deletes a user from the database.
     *
     * @param int $id ID of the user to be deleted.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response indicating success or failure.
     */
    public function usersDelete($id)
    {
        try {
            $this->crudUserService->delete($id);
            $user = $this->crudParameterService->getUserAuth();
            // $listUsers = $this->crudUserService->getUsersByLevel($user, "name", EnumOrderBy::ASC);

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
    }
}
