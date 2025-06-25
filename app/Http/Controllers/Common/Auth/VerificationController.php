<?php

namespace App\Http\Controllers\Common\Auth;

#region Import Libraries
use App\Models\Systems\Master\MasterUser;
#endregion

#region Import Requests
#endregion

#region Import Services
use App\Models\Systems\Tenant\TenantUser;
#endregion

#region Import Models
use App\Http\Controllers\Common\Controller;
use App\Services\Systems\Tenant\Crud\CrudParameterService;
use App\Jobs\Systems\Tenant\Modules\Auth\Email\SuccessVerifyEmailJob;
use App\Jobs\Systems\Tenant\Modules\Auth\Email\TenantJobSuccessVerifyEmail;
#endregion

#region Import Jobs

#endregion

/**
 * Controller responsible for handling user email verification.
 * Includes methods for displaying the verification page, resending verification emails,
 * and checking the verification status.
 */
class VerificationController extends Controller
{
    #region variables
    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = "/";
    protected $crudParameterService;
    #endregion

    #region _construct
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
    #endregion

    /**
     * Mark the authenticated user's email address as verified.
     *
     */
    public function verify($id, $hash)
    {

        #region content
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
                ]);
                return redirect(route("tenant.admin.dashboard"));
            }

            TenantJobSuccessVerifyEmail::dispatch($user->email, [
                "email" => $user->email,
                "users_id" => $user->id,
            ]);
            return redirect(route("master.admin.dashboard"));
        }

        return redirect("/login");
        #endregion
    }
}
