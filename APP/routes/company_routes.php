<?php

use App\Http\Controllers\rms\CompanyController;
use Illuminate\Support\Facades\Route;

//Companies Routes
// middleware('auth:sanctum')->
Route::middleware('auth:sso')->controller(CompanyController::class)->prefix('company')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('changeStatus', 'changeStatus');
    Route::post('update', 'update');
});
