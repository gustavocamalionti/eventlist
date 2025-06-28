<?php

namespace App\Http\Controllers\Common\Auth;

use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\Response as BladeResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Common\Controller;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|InertiaResponse|BladeResponse
    {
        $pathVerifyEmail = "systems/master/modules/auth/pages/VerifyEmail";
        if (tenancy()->initialized) {
            $pathVerifyEmail = "systems/tenant/modules/auth/pages/VerifyEmail";
        }
        return $request->user()->hasVerifiedEmail()
            ? Inertia::location(route(RouteServiceProvider::homeRoute()))
            : Inertia::render($pathVerifyEmail, ["status" => session("status")]);
    }
}
