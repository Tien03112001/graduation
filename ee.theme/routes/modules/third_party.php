<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 9/17/2022
 * Time: 19:17
 */

use Illuminate\Support\Facades\Route;

Route::middleware('auth.api')->group(function () {
    Route::apiResource('cache', 'CacheController')->only('store');
    Route::apiResource('command', 'CommandController')->only('store');
    Route::apiResource('robot_file', 'RobotFileController');
    Route::apiResource('app_settings', 'AppSettingController');
    Route::apiResource('shipping_fee_tables', 'ShippingFeeTableController');
    Route::apiResource('structure_data_types', 'StructureDataTypeController');
});