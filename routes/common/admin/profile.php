<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\Admin\ProfileController;

Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::post("/profile", [ProfileController::class, "update"])->name("profile.update");
    Route::post("/profile-password", [ProfileController::class, "updatePassword"])->name("profile.update.password");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");
});
