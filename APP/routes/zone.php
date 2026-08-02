<?php

use App\Http\Controllers\ACU\ZoneController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ZoneController Routes
|--------------------------------------------------------------------------
|
| Here is where you can register ZoneController routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "ZoneController" middleware group. Now create something great!
|
*/
// middleware('auth:sanctum')->
Route::controller(ZoneController::class)->prefix('zone')->group(function () {
    Route::get('zone', 'index')->name('zone');
    Route::get('create-zone', 'create')->name('create-zone');
    Route::post('store-zone', 'store')->name('store-zone');
    Route::get('edi-zone/{id}', 'edit')->name('edit-zone');
    Route::post('update-zone/{id}', 'update')->name('update-zone');
    Route::get('get-provinces', 'getProvinces')->name('get-provinces');
    Route::post('bring-district-by-province-id', 'bringDistrictByProvinceId')->name('bring-district-by-province-id');
    Route::post('bring-district-by-province-id-for-search', 'bringDistrictByProvinceIdForSearch')->name('bring-district-by-province-id-for-search');
    Route::post('bring-zone-by-district-id-for-search', 'bringZoneByDistrictIdForSearch')->name('bring-zone-by-district-id-for-search');
    Route::post('bring-district-by-province-id-for-zone', 'bringDistrictByProvinceIdForZone')->name('bring-district-by-province-id-for-zone');
    Route::post('bring-zone-by-district-id', 'bringZoneByDistrictId')->name('bring-zone-by-district-id');
    Route::post('bring-shared-by-zone-id', 'bringSharedByDistrictId')->name('bring-shared-by-zone-id');
    Route::post('bring-gozars-by-shared-id', 'bringGozarsdSharedId')->name('bring-gozars-by-shared-id');
    Route::post('bring-home-by-area-id', 'bringHomeByAreaId')->name('bring-home-by-area-id');
    Route::post('bring-area-by-gozar-id', 'bringAreaByGozarId')->name('bring-area-by-gozar-id');
});
