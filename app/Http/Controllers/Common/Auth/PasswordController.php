<?php

namespace App\Http\Controllers\Common\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Common\Controller;
use App\Models\Systems\Master\MasterParameter;
use App\Models\Systems\Tenant\TenantParameter;
use App\Jobs\Systems\Master\Modules\Auth\Email\JobSendResetPassword as MasterJobSendResetPassword;
use App\Jobs\Systems\Tenant\Modules\Auth\Email\JobSendResetPassword as TenantJobSendResetPassword;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "current_password" => ["required", "current_password"],
            "password" => ["required", Password::defaults(), "confirmed"],
        ]);

        $request->user()->update([
            "password" => Hash::make($validated["password"]),
        ]);

        $isTenant = tenancy()->initialized;
        if ($isTenant) {
            TenantJobSendResetPassword::dispatch($request->email, [
                "parameters" => TenantParameter::find(1),
                "email" => $request->email,
            ], null, tenant()->getTenantKey());
        } else {
            MasterJobSendResetPassword::dispatch($request->email, [
                "parameters" => MasterParameter::find(1),
                "email" => $request->email,
            ]);
        }

        return back();
    }
}
