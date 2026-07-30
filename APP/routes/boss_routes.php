<?php

use App\Http\Controllers\BossController;
use Illuminate\Support\Facades\Route;

//Boss Routes
Route::middleware('auth:sanctum')->controller(BossController::class)->prefix('boss')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::get('edit/{id}', 'edit');
    Route::post('update/{id}', 'update');
    Route::get('createButton', 'createButton');
    Route::post('changeStatus', 'changeStatus');
});
