<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\Admin\LogController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Systems\Tenant\Modules\Site\SiteController;
use App\Http\Controllers\Systems\Tenant\Modules\Site\FaleConoscoController;
use App\Http\Controllers\Systems\Tenant\Modules\Admin\UserController;
use App\Http\Controllers\Systems\Tenant\Modules\Admin\AdminController;
use App\Http\Controllers\Systems\Tenant\Modules\Admin\WebhookController;
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
        require __DIR__ . "../../../../common/routes-common.php";
        Route::name("tenant.auth.")->group(function () {
            require __DIR__ . "../../../../common/auth/auth.php";
        });

        // Route::name("tenant.admin.*")->prefix("admin")->middleware(["tenant.auth", "tenant.verified"])->group(function () {
        Route::name("tenant.admin.")
            ->prefix("admin")
            ->middleware(["auth", "verified"])
            ->group(function () {
                require __DIR__ . "../../../../common/admin/profile.php";

                Route::controller(AdminController::class)->group(function () {
                    Route::get("/home", "index");
                    Route::get("/dashboard", "index");
                    Route::get("/", "index")->name("dashboard");
                });

                Route::controller(WebhookController::class)->group(function () {
                    Route::post("/webhooks", "index")
                        ->middleware(["asaas.ip", "webhook.auth"])
                        ->name("webhook");
                });

                // LINKS
                // Route::controller(App\Http\Controllers\Panel\LinkController::class)->group(function () {
                //     Route::get("/links", "linksList")->name("links.list");
                //     Route::post("/links-filter", "linksFilters")->name("links.filter");
                //     Route::get("/links-manut/{id?}", "linksMaintenance")->name("links.maintenance");
                //     Route::post("/links-store", "linksStore")->name("links.store");
                //     Route::post("/links-update/{id}", "linksUpdate")->name("links.update");
                //     Route::delete("/links-delete/{id}", "linksDelete")->name("links.delete");
                //     Route::get("/links-history/{userId?}", "getlinkHistory")->name("links.history");
                // });

                // // BUYS
                // Route::controller(App\Http\Controllers\Panel\EventBuyController::class)->group(function () {
                //     Route::get("/event-buys", "eventBuysList")->name("event.buys.list");
                //     Route::post("/event-buys-filter", "eventBuysFilters")->name("event.buys.filter");
                //     Route::get("/event-buys-history/{buysId?}", "getBuysHistory")->name("event.buys.history");
                // });

                // // VOUCHERS
                // Route::controller(App\Http\Controllers\Panel\EventVoucherController::class)->group(function () {
                //     Route::get("/event-vouchers", "eventVouchersList")->name("event.vouchers.list");
                //     Route::post("/event-vouchers-filter", "eventVouchersFilters")->name("event.vouchers.filter");
                //     Route::get("/event-vouchers-history/{userId?}", "getlinkHistory")->name("event.vouchers.history");
                // });

                // // USERS
                // Route::controller(App\Http\Controllers\Panel\UserController::class)->group(function () {
                //     Route::get("/users", "usersList")->name("users.list");
                //     Route::post("/users-filter", "usersFilters")->name("users.filter");
                //     Route::get("/users-manut/{id?}", "usersMaintenance")->name("users.maintenance");
                //     Route::post("/users-store", "usersStore")->name("users.store");
                //     Route::post("/users-update/{id}", "usersUpdate")->name("users.update");
                //     Route::delete("/users-delete/{id}", "usersDelete")->name("users.delete");
                //     Route::get("/users-history/{userId?}", "getUserHistory")->name("users.history");
                // });

                // FORM CONTACTS
                // Route::controller(App\Http\Controllers\Panel\FormContentContactController::class)->group(function () {
                //     Route::get("/form-contacts", "formContactsList")->name("form.contacts.list");
                //     Route::post("/form-contacts-filter", "formContactsFilters")->name("form.contacts.filter");
                // });

                // FORM CONFIGS
                // Route::controller(App\Http\Controllers\Panel\FormConfigController::class)->group(function () {
                //     Route::get("/form-configs", "formConfigsList")->name("form.configs.list");
                //     Route::post("/form-configs-filter", "formConfigsFilters")->name("form.configs.filter");
                //     Route::get("/form-configs-manut/{id?}", "formConfigsMaintenance")->name("form.configs.maintenance");
                //     Route::post("/form-configs-store", "formConfigsStore")->name("form.configs.store");
                //     Route::post("/form-configs-update/{id}", "formConfigsUpdate")->name("form.configs.update");
                //     Route::delete("/form-configs-delete/{id}", "formConfigsDelete")->name("form.configs.delete");
                //     Route::get("/form-configs-history/{formConfigId?}", "getFormConfigsHistory")->name("form.configs.history");
                // });

                // LOGS
                Route::controller(LogController::class)->group(function () {
                    // Log Emails
                    Route::get("/log-emails", "logEmailsList")->name("log.emails.list");
                    Route::post("/log-emails-filter", "logEmailsFilters")->name("log.emails.filter");

                    // Log Audits
                    Route::get("/log-audits", "logAuditsList")->name("log.audits.list");
                    Route::post("/log-audits-filter", "logAuditsFilters")->name("log.audits.filter");
                    Route::get("/log-audits-history/{logId?}", "getLogAuditHistory")->name("log.audits.history");

                    // Log Errors
                    Route::get("/log-errors", "logErrorsList")->name("log.errors.list");
                    Route::post("/log-errors-filter", "logErrorsFilters")->name("log.errors.filter");

                    Route::get("/log-webhooks", "logWebhooksList")->name("log.webhooks.list");
                    Route::post("/log-webhooks-filter", "logWebhooksFilters")->name("log.webhooks.filter");
                });
                Route::controller(UserController::class)->group(function () {
                    // Log Emails
                    Route::get("/users", "usersList")->name("users.list");
                    Route::post("/users-filter", "usersFilters")->name("users.filter");
                    Route::get("/users-manut/{id?}", "usersMaintenance")->name("users.maintenance");
                    Route::post("/users-store", "usersStore")->name("users.store");
                    Route::post("/users-update/{id}", "usersUpdate")->name("users.update");
                    Route::delete("/users-delete/{id}", "usersDelete")->name("users.delete");
                    Route::get("/users-history/{userId?}", "getUserHistory")->name("users.history");
                });
                // Route::controller(App\Http\Controllers\Panel\LogController::class)->group(function () {
                //     // Log Emails
                //     Route::get("/log-emails", "logEmailsList")->name("log.emails.list");
                //     Route::post("/log-emails-filter", "logEmailsFilters")->name("log.emails.filter");

                //     // Log Audits
                //     Route::get("/log-audits", "logAuditsList")->name("log.audits.list");
                //     Route::post("/log-audits-filter", "logAuditsFilters")->name("log.audits.filter");
                //     Route::get("/log-audits-history/{logId?}", "getLogAuditHistory")->name("log.audits.history");

                //     // Log Errors
                //     Route::get("/log-errors", "logErrorsList")->name("log.errors.list");
                //     Route::post("/log-errors-filter", "logErrorsFilters")->name("log.errors.filter");

                //     Route::get("/log-webhooks", "logWebhooksList")->name("log.webhooks.list");
                //     Route::post("/log-webhooks-filter", "logWebhooksFilters")->name("log.webhooks.filter");
                // });
            });

        Route::name("tenant.site.")->group(function () {            
            Route::controller(SiteController::class)->group(function () {
                Route::get("/home", "index");
                Route::get("/dashboard", "index");
                Route::get("/", "index")->name("dashboard");
                Route::get("/politica-privacidade", "politicaPrivacidade")->name("privacy.policy");

            });

             Route::controller(FaleConoscoController::class)->group(function () {
                Route::get("/faleconosco", "index");
            });
        });
    }
);
