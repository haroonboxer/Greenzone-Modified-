<?php

use App\Http\Controllers\green_zone\LicenseController;
use Illuminate\Support\Facades\Route;

//Licences Routes
// middleware('auth:sanctum')->
Route::controller(LicenseController::class)->prefix('license')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('update/{id}', 'update');
    Route::get('createButton', 'createButton');
    Route::post('changeStatus', 'changeStatus');
    Route::post('sentPrint', 'sentPrint');
});
