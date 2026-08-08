<?php

use App\Http\Controllers\workshop\WorkshopLicenseController;
use Illuminate\Support\Facades\Route;

//Licences Routes
// middleware('auth:sanctum')->
Route::middleware('auth:sso')->controller(WorkshopLicenseController::class)->prefix('workshopLicense')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('update/{id}', 'update');
    Route::get('createButton', 'createButton');
    Route::post('changeStatus', 'changeStatus');
    Route::post('changeStatusOfPrint', 'changeStatusOfPrint');
});
