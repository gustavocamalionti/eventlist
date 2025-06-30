<?php

namespace App\Http\Controllers\Common\Admin;

use App\Libs\Errors;
use Inertia\Inertia;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use Illuminate\Http\Request;

use App\Libs\Enums\EnumOrderBy;

use App\Libs\Enums\EnumErrorsType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Common\Controller;
use App\Services\Common\Crud\CrudCitieService;
use App\Services\Common\Crud\CrudStateService;
use App\Services\Systems\Tenant\Crud\CrudRoleService;
use App\Services\Systems\Tenant\Crud\CrudUserService;
use App\Services\Systems\Tenant\Crud\CrudParameterService;
use App\Http\Requests\Systems\Tenant\Modules\Admin\ProfileUpdateRequest;
use App\Http\Requests\Systems\Tenant\Modules\Admin\ProfileUpdatePasswordRequest;

/**
 * Controller responsible for handling user profile editing and saving.
 */
class ProfileController extends Controller
{
    protected $crudParameterService;
    protected $crudUserService;
    protected $crudRoleService;
    protected $crudStateService;
    protected $crudCitieService;

    public function __construct(
        CrudParameterService $crudParameterService,
        CrudUserService $crudUserService,
        CrudRoleService $crudRoleService,
        CrudStateService $crudStateService,
        CrudCitieService $crudCitieService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudUserService = $crudUserService;
        $this->crudRoleService = $crudRoleService;
        $this->crudStateService = $crudStateService;
        $this->crudCitieService = $crudCitieService;
    }

    /**
     * Displays the user profile edit page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the view with user profile edit page or a JSON response in case of an error.
     */
    public function edit()
    {
        try {
            $pageTitle = ViewsModules::SITE_HOME;
            $parameters = $this->crudParameterService->findById(1);
            $user = auth()->user();
            $states = $this->crudStateService->getAll([], ["initials" => "asc"]);
            $cities = $this->crudCitieService->getAll(["states_id" => optional($user->cities)->states_id]);
            $roles = $this->crudRoleService->getAll([], ["id" => EnumOrderBy::DESC]);

            $path = $this->isTenant() ? "tenant" : "master";
            return view(
                "legacy.systems.{$path}.modules.admin.pages.profile.profile",
                compact("parameters", "pageTitle", "user", "states", "cities", "roles")
            );
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::SITE_HOME,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::SITE_HOME,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $data = $request->all();
            $newEmail = $data["email"];
            $user = $this->crudUserService->findById($request->id);

            if ($newEmail != $user->email) {
                $data["email_verified_at"] = null;
            }

            $user->fill($data)->save();
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::SITE_HOME,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::SITE_HOME,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
    /**
     * Update the user's profile information.
     */
    public function updatePassword(ProfileUpdatePasswordRequest $request)
    {
        try {
            $user = $this->crudUserService->findById($request->user_id);
            $user->password = $request->password;

            $user->save();

            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::SITE_HOME,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::SITE_HOME,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $user = $this->crudUserService->findById($requestData["id"]);
            Auth::logout();

            $user->delete();

            $request->session()->invalidate();

            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::SITE_HOME,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::SITE_HOME,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
