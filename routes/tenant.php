<?php

declare(strict_types=1);

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware(["web", InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class])->group(
    function () {
        Route::get("/", function () {
            return Inertia::render("system_tenant/module_site/pages/Welcome", [
                "teste" => "This is your multi-tenant application. The id of the current tenant is " . tenant("id"),
            ]);
        });

        Route::post("/webhooks", [App\Http\Controllers\Tenant\Admin\WebhookController::class, "index"])->middleware(
            "asaas.ip",
            "webhook.auth"
        );
    }
);
