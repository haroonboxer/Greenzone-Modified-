<?php

use App\Http\Controllers\rms\WeaponsController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group(function () {
//     // Weapon Routes
//     Route::prefix('weapon')->controller(WeaponsController::class)->group(function () {
//         Route::get('index', 'index');
//         Route::post('store', 'store');
//         Route::post('update/{id}', 'update');
//         Route::post('changeStatus', 'changeStatus');
//         Route::get('view/{id}', 'view');
//     });
// });
// Weapon Routes
Route::prefix('weapon')->middleware('auth:sso')->controller(WeaponsController::class)->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('update/{id}', 'update');
    Route::post('changeStatus', 'changeStatus');
    Route::get('view/{id}', 'view');
});