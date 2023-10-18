<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('sale_order_statuses', 'SaleOrderStatusController@index');

Route::get('sale_order_shipping_statuses', 'SaleOrderShippingStatusController@index');

Route::get('channels', 'ChannelController@index');

Route::get('shipping_provinces', 'ShippingProvinceController@index');

Route::get('payment_types', 'PaymentTypeController@index');

Route::get('shipping_units', 'ShippingUnitController@index');

Route::get('shipping_stores', 'ShippingStoreController@index');

Route::get('shipping_services', 'ShippingServiceController@index');