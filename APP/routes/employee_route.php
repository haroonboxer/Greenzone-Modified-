<?php

use App\Http\Controllers\rms\EmployeeController;
use Illuminate\Support\Facades\Route;

//Employees Routes
// middleware('auth:sanctum')->
Route::middleware('auth:sso')->controller(EmployeeController::class)->prefix('employee')->group(function () {
    Route::get('index', 'index');
    Route::get('createButton', 'createButton');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('update/{id}', 'update');
    Route::post('changeStatus', 'changeStatus');
});
