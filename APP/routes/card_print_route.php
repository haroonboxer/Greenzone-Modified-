<?php

use App\Http\Controllers\workshop\CardPrintController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sso')->controller(CardPrintController::class)->prefix('printedCard')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('view/{id}', 'view');
    Route::post('update/{id}', 'update');
    Route::get('createButton', 'createButton');
    Route::post('changeStatus', 'changeStatus');
    Route::post('changeStatusOfLicense', 'changeStatusOfLicense');
    Route::get('generate-license/{id}', 'generateLicense');
});
