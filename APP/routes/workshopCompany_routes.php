<?php

use App\Http\Controllers\workshop\CompanyController;
use Illuminate\Support\Facades\Route;

//Companies Routes
Route::middleware('auth:sanctum')->controller(CompanyController::class)->prefix('workshopCompany')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('changeStatus', 'changeStatus');
    Route::post('update', 'update');
});
