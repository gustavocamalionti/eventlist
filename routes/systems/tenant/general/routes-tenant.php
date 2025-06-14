<?php



use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Systems\Tenant\Modules\Site\TenantSiteController;
use App\Http\Controllers\Systems\Tenant\Modules\Admin\TenantAdminController;
use App\Http\Controllers\Systems\Tenant\Modules\Admin\TenantAdminWebhookController;

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
        Route::name("tenant.auth.")->group(function () {
            require __DIR__ . "../../../../common/auth/auth.php";
        });

        // Route::name("tenant.admin.*")->prefix("admin")->middleware(["tenant.auth", "tenant.verified"])->group(function () {
        Route::name("tenant.admin.")
            ->prefix("admin")
            ->middleware(["auth", "verified"])
            ->group(function () {
                // require __DIR__ . "../../../../common/admin/profile.php";
                Route::controller(TenantAdminController::class)->group(function () {
                    Route::get("/home", "index");
                    Route::get("/dashboard", "index");
                    Route::get("/", "index")->name("dashboard");
                });

                Route::controller(TenantAdminWebhookController::class)->group(function () {
                    Route::post("/webhooks", "index")
                        ->middleware(["asaas.ip", "webhook.auth"])
                        ->name("webhook");
                });
            });

        Route::name("tenant.site.")->group(function () {
            Route::controller(TenantSiteController::class)->group(function () {
                Route::get("/home", "index");
                Route::get("/dashboard", "index");
                Route::get("/", "index")->name("dashboard");
            });
        });
    }
);
