<?php

use App\Http\Controllers\rms\GunController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->controller(GunController::class)->prefix('gun')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('update/{id}', 'update');
});
