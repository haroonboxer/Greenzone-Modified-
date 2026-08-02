<?php

use App\Http\Controllers\workshop\AssistantController;
use Illuminate\Support\Facades\Route;

//Assistant Routes
// middleware('auth:sanctum')->
Route::controller(AssistantController::class)->prefix('workshopAssistant')->group(function () {
    Route::get('index', 'index');
    Route::get('createButton', 'createButton');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('update/{id}', 'update');
    Route::post('changeStatus', 'changeStatus');
});
