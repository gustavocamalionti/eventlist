<?php

namespace App\Http\Controllers\Common\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Inertia\Response as InertiaResponse;
use App\Http\Controllers\Common\Controller;
use Illuminate\Http\Response as BladeResponse;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse|BladeResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return Inertia::location(route(RouteServiceProvider::homeRoute()));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with("status", "verification-link-sent");
    }
}
