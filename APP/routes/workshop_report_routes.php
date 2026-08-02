<?php

use App\Http\Controllers\workshop\ReportController;
use Illuminate\Support\Facades\Route;

// middleware('auth:sanctum')->
Route::controller(ReportController::class)->prefix('workshopReport')->group(function () {
    Route::get('index', 'index');
    Route::get('listCompany', 'listCompany');
    Route::get('monthlyCompanyStats', 'monthlyCompanyStats');
    Route::get('generate-report', 'gen_excel_report');
});
