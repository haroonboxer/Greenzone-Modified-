<?php

use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;
// ->middleware('permission:admin-view')
// users routes
Route::get('/user-management-sys', function () {
    return view('auth.home');
})->name('user-management-sys');
// middleware('auth:sanctum')->
Route::middleware('auth:sso')->controller(UserController::class)->prefix('user')->group(function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::get('edit/{id}', 'edit');
    Route::get('view/{id}', 'view');
    Route::get('change-status/{id}/{status}', 'changeStatus');
    Route::get('restore/{id}', 'restore');
    Route::post('update/{id}', 'update');
    Route::get('create', 'create');
    Route::post('change-password', 'change_password');
    Route::post('change-image', 'changeUserImage');
    Route::post('change-user-password/{id}', 'changeUserPassword');
    Route::post('refresh_token', 'refreshToken');
});

Route::middleware('auth:sso')->controller(UserController::class)->prefix('user')->group(function () {
    Route::get('auth-user', 'authUser');
    Route::post('change-password', 'changePassword');
    Route::post('logout', 'logout');
});

