<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\Admin\LogController;
use App\Http\Controllers\Systems\Master\Modules\Site\SiteController;
use App\Http\Controllers\Systems\Master\Modules\Admin\UserController;
use App\Http\Controllers\Systems\Tenant\Modules\Admin\AdminController;
use App\Http\Controllers\Systems\Master\Modules\Site\MasterSiteController;
use App\Http\Controllers\Systems\Master\Modules\Admin\MasterAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes Master
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . "../../../../common/routes-common.php";

Route::name("master.auth.")->group(function () {
    require __DIR__ . "../../../../common/auth/auth.php";
});

Route::name("master.general.")->group(function () {});

// Route::name("master.admin.*")->prefix("admin")->middleware(["master.auth", "master.verified"])->group(function () {
Route::name("master.admin.")
    ->prefix("admin")
    ->middleware(["auth", "verified"])
    ->group(function () {
        require __DIR__ . "../../../../common/admin/profile.php";
        Route::controller(AdminController::class)->group(function () {
            Route::get("/home", "index");
            Route::get("/dashboard", "index");
            Route::get("/", "index")->name("dashboard");
        });

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
    });

Route::name("master.site.")->group(function () {
    Route::controller(SiteController::class)->group(function () {
        Route::get("/home", "index");
        Route::get("/dashboard", "index");
        Route::get("/", "index")->name("dashboard");
        Route::get("/politica-privacidade", "politicaPrivacidade")->name("privacy.policy");
    });
});
