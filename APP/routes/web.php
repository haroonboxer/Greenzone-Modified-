<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\green_zone\CardController;
use App\Http\Controllers\green_zone\VehicleSaveController;
use App\Http\Controllers\rms\PrintedCardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//   return view('auth.login');
// });

// Route::get('/card/print/{id}', [CardController::class, 'generateLicense'])
//   ->name('card.print'); // no auth

//Illuminate\Support\Facades\Auth::routes();
Route::middleware('auth')->group(function () {
  Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
  // web.php or api.php (make sure this is accessible via GET)
  Route::get('printed_card/view/{id}', [PrintedCardController::class, 'generateIDCard']);
});


Route::post('/sso-login', [AuthController::class, 'ssoLogin']);

Route::get('/sso/token', [AuthController::class, 'getReactToken']);

