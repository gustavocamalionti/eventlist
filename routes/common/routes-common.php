<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Common\CommonController;

Route::controller(CommonController::class)->group(function () {
    Route::get("/cities/{statesId?}", "cities");

    Route::get("/cascade-form-subjects/{formId}", "cascadeFormSubjects");
    Route::get("/filter-stores-card/{statesId}/{citiesId}/{storesId}", "filterStoreCard");
});
