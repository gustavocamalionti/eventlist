<?php

use Illuminate\Support\Facades\Route;
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

Route::name("master.auth.")->group(function () {
    require __DIR__ . "../../../../common/auth/auth.php";
});

Route::name("master.general.")->group(function () {});

// Route::name("master.admin.*")->prefix("admin")->middleware(["master.auth", "master.verified"])->group(function () {
Route::name("master.admin.")->prefix("admin")->middleware(["auth", "verified"])->group(function () {
    // require __DIR__ . "../../../../common/admin/profile.php";
    Route::controller(MasterAdminController::class)->group(function () {
        Route::get("/home", "index");
        Route::get("/dashboard", "index");
        Route::get("/", "index")->name("dashboard");
    });
});

Route::name("master.site.")->group(function () {
    Route::controller(MasterSiteController::class)->group(function () {
        Route::get("/home", "index");
        Route::get("/dashboard", "index");
        Route::get("/", "index")->name("dashboard");
    });
});
