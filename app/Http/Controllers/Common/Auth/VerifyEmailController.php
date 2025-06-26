<?php

namespace App\Http\Controllers\Common\Auth;

use Inertia\Inertia;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Inertia\Response as InertiaResponse;
use App\Http\Controllers\Common\Controller;
use Illuminate\Http\Response as BladeResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse|BladeResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return Inertia::location(route(RouteServiceProvider::homeRoute()));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return Inertia::location(route(RouteServiceProvider::homeRoute()));
    }
}
