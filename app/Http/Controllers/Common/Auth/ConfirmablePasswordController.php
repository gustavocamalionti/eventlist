<?php

namespace App\Http\Controllers\Common\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Common\Controller;
use Illuminate\Validation\ValidationException;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\Response as BladeResponse;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): InertiaResponse
    {
        return Inertia::render("systems/master/modules/auth/pages/ConfirmPassword");
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): BladeResponse|RedirectResponse
    {
        if (
            !Auth::guard("web")->validate([
                "email" => $request->user()->email,
                "password" => $request->password,
            ])
        ) {
            throw ValidationException::withMessages([
                "password" => __("auth.password"),
            ]);
        }

        $request->session()->put("auth.password_confirmed_at", time());

        return Inertia::location(route(RouteServiceProvider::homeRoute()));
    }
}
