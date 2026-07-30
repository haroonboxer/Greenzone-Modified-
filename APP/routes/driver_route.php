<?php

use App\Http\Controllers\green_zone\driverController;
use Illuminate\Support\Facades\Route;

//Driver Routes
Route::middleware('auth:sanctum')->controller(driverController::class)->prefix('driver')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::get('edit/{id}', 'edit');
    Route::post('update/{id}', 'update');
    Route::get('createButton', 'createButton');
    Route::post('changeStatus', 'changeStatus');
});
