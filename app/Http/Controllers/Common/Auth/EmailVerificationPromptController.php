<?php

namespace App\Http\Controllers\Common\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Common\Controller;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        $pathVerifyEmail = "systems/master/modules/auth/pages/VerifyEmail";
        if (tenancy()->initialized) {
            $pathVerifyEmail = "systems/tenant/modules/auth/pages/VerifyEmail";
        }
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(RouteServiceProvider::HOME)
            : Inertia::render($pathVerifyEmail, ["status" => session("status")]);
    }
}
