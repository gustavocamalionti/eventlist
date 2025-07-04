<?php

namespace App\Http\Controllers\Common\Auth;

use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as BladeResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Common\Controller;
use App\Http\Requests\Systems\Master\Modules\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): InertiaResponse
    {
        $isTenant = tenancy()->initialized;
        $pathRender = "systems/master/modules/auth/pages/Login";
        $routePrefix = "master.auth";
        if ($isTenant) {
            $pathRender = "systems/tenant/modules/auth/pages/Login";
            $routePrefix = "tenant.auth";
        }

        return Inertia::render($pathRender, [
            "canResetPassword" => Route::has($routePrefix . "." . "password.request"),
            "status" => session("status"),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): BladeResponse|RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return Inertia::location(route(RouteServiceProvider::homeRoute()));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard("web")->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect("/");
    }
}
