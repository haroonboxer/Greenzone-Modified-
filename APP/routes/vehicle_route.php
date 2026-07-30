<?php

use App\Http\Controllers\green_zone\vehicleController;
use Illuminate\Support\Facades\Route;

//vehicles Routes
Route::middleware('auth:sanctum')->controller(vehicleController::class)->prefix('vehicle')->group(function () {
    Route::get('index', 'index');
    Route::get('expired', 'expiredLicenses');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('update/{id}', 'update');
    Route::get('createButton', 'createButton');
    Route::post('changeStatus', 'changeStatus');
});
