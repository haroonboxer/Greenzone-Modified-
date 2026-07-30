<?php

use App\Http\Controllers\rms\PrintedCardController;
use Illuminate\Support\Facades\Route;

//Printed Cards Routes
Route::middleware('auth:sanctum')->controller(PrintedCardController::class)->prefix('printed_card')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('update/{id}', 'update');
    Route::get('createButton', 'createButton');
    Route::post('changeStatus', 'changeStatus');
    Route::get('generate-idcard/{id}', 'generateIDCard');
});
