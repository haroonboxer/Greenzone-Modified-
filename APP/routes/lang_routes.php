<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Auth\LanguageController;
// middleware(['middleware' => 'auth'])->
Route::middleware('auth:sso')->controller(LanguageController::class)->group(function () {
    Route::get('/language/{locale}', 'changeLanguage')->name('language');
});