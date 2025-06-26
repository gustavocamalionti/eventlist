<?php

namespace App\Http\Controllers\Common\Auth;

use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\Response as BladeResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Models\Systems\Master\MasterUser;
use App\Models\Systems\Tenant\TenantUser;
use App\Http\Controllers\Common\Controller;
use App\Libs\Enums\Systems\Master\EnumMasterRoles;
use App\Libs\Enums\Systems\Tenant\EnumTenantRoles;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): InertiaResponse
    {
        $pathRender = "systems/master/modules/auth/pages/Register";
        if (tenancy()->initialized) {
            $pathRender = "systems/tenant/modules/auth/pages/Register";
        }

        return Inertia::render($pathRender);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse|BladeResponse
    {
        $user = null;
        $isTenant = tenancy()->initialized;

        $request->validate([
            "name" => "required|string|max:255",
            "email" =>
            "required|string|lowercase|email|max:255|unique:" . ($isTenant ? TenantUser::class : MasterUser::class),
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "roles_id" => $isTenant ? EnumMasterRoles::MANAGER : EnumTenantRoles::PROMOTER,
            "password" => Hash::make($request->password),
        ];

        if ($isTenant) {
            $user = TenantUser::create($data);
        } else {
            $user = MasterUser::create($data);
        }

        event(new Registered($user));

        Auth::login($user);

        return Inertia::location(route(RouteServiceProvider::homeRoute()));
    }
}
