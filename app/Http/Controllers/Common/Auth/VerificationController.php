<?php

namespace App\Http\Controllers\Common\Auth;

use Inertia\Inertia;

use App\Providers\RouteServiceProvider;

use App\Models\Systems\Master\MasterUser;
use App\Models\Systems\Tenant\TenantUser;
use App\Http\Controllers\Common\Controller;
use App\Services\Systems\Tenant\Crud\CrudParameterService;
use App\Jobs\Systems\Tenant\Modules\Auth\Email\JobSuccessVerifyEmail as TenantJobSuccessVerifyEmail;
use App\Jobs\Systems\Master\Modules\Auth\Email\JobSuccessVerifyEmail as MasterJobSuccessVerifyEmail;

/**
 * Controller responsible for handling user email verification.
 * Includes methods for displaying the verification page, resending verification emails,
 * and checking the verification status.
 */
class VerificationController extends Controller
{
    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = "/";
    protected $crudParameterService;

    /**
     * Class constructor, initializes necessary services and sets up Reset Password.
     *
     * @param CrudParameterService $crudParameterService Service for handling parameters.
     * @return void
     */
    public function __construct(CrudParameterService $crudParameterService)
    {
        $this->middleware("auth");
        $this->middleware("throttle:6,1")->only("verify");
        $this->crudParameterService = $crudParameterService;
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     */
    public function verify($id, $hash)
    {
        $user = null;
        $isTenant = tenancy()->initialized;

        if ($isTenant) {
            $user = TenantUser::findOrFail($id);
        } else {
            $user = MasterUser::findOrFail($id);
        }

        if (hash_equals($hash, sha1($user->email))) {
            $user->email_verified_at = now();
            $user->save();
            if ($isTenant) {
                TenantJobSuccessVerifyEmail::dispatch($user->email, [
                    "email" => $user->email,
                    "users_id" => $user->id,
                ], null, tenant()->getTenantKey());
                return Inertia::location(route(RouteServiceProvider::homeRoute()));
            }

            MasterJobSuccessVerifyEmail::dispatch($user->email, [
                "email" => $user->email,
                "users_id" => $user->id,
            ]);
            return Inertia::location(route(RouteServiceProvider::homeRoute()));
        }
    }
}
