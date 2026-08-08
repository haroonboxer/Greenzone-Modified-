<?php

use App\Http\Controllers\rms\VehicalController;
use Illuminate\Support\Facades\Route;

//Vehicals Routes
// middleware('auth:sanctum')->
Route::middleware('auth:sso')->controller(VehicalController::class)->prefix('vehical')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('update/{id}', 'update');
    Route::get('createButton', 'createButton');
    Route::post('changeStatus', 'changeStatus');
});
