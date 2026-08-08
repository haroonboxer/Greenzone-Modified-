<?php

use App\Http\Controllers\workshop\BossController;
use Illuminate\Support\Facades\Route;

//Workshop Boss Routes
// middleware('auth:sanctum')->
Route::middleware('auth:sso')->controller(BossController::class)->prefix('workshopBoss')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::get('edit/{id}', 'edit');
    Route::post('update/{id}', 'update');
    Route::get('createButton', 'createButton');
    Route::post('changeStatus', 'changeStatus');
});
