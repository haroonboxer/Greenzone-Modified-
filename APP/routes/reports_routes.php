<?php

use App\Http\Controllers\green_zone\ReportController;
use Illuminate\Support\Facades\Route;


// middleware('auth:sanctum')->
Route::controller(ReportController::class)->prefix('report')->group(function () {
    Route::get('index', 'index');
    Route::get('listCompany', 'listCompany');
    Route::get('monthlyCompanyStats', 'monthlyCompanyStats');
    Route::get('generate-report', 'gen_excel_report');
});
