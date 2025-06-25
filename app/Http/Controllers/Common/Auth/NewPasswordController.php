<?php

namespace App\Http\Controllers\Common\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\Common\Controller;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        $isTenant = tenancy()->initialized;
        $pathRender = $isTenant ? "systems/tenant/modules/auth/pages/ResetPassword" : "systems/master/modules/auth/pages/ResetPassword";
        return Inertia::render($pathRender, [
            "email" => $request->email,
            "token" => $request->route("token"),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "token" => "required",
            "email" => "required|email",
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset($request->only("email", "password", "password_confirmation", "token"), function (
            $user
        ) use ($request) {
            $user
                ->forceFill([
                    "password" => Hash::make($request->password),
                    "remember_token" => Str::random(60),
                ])
                ->save();

            event(new PasswordReset($user));
        });

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        $isTenant = tenancy()->initialized;
        $routePrefix = $isTenant ? "tenant.auth" : "master.auth";
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route($routePrefix . "." . "login")->with("status", __($status));
        }

        throw ValidationException::withMessages([
            "email" => [trans($status)],
        ]);
    }
}
