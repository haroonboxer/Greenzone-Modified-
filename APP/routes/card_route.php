<?php

use App\Http\Controllers\green_zone\CardController;
use Illuminate\Support\Facades\Route;
// middleware('auth:sanctum')->
//Printed Cards Routes
Route::controller(CardController::class)->prefix('card')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('update/{id}', 'update');
    Route::get('createButton', 'createButton');
    Route::post('changeStatus', 'changeStatus');
    Route::post('changeStatusOfLicense', 'changeStatusOfLicense');
    Route::get('generate-license/{id}', 'generateLicense');
});
