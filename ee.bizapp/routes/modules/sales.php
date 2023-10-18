<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('login', 'LoginController@login');


Route::group(['middleware' => 'auth.sales'], function () {
    Route::get('dashboard', 'DashboardController@index');

    Route::group(['prefix' => 'sale_orders'], function () {
        Route::group(['prefix' => '{id}'], function () {
            Route::post('verify', 'SaleOrderController@verify');
            Route::post('confirm', 'SaleOrderController@confirm');
            Route::post('cancel', 'SaleOrderController@cancel');
            Route::post('complain', 'SaleOrderController@complain');
            Route::post('swap', 'SaleOrderController@swap');
            Route::post('refund', 'SaleOrderController@refund');
            Route::post('refundAmount', 'SaleOrderController@refundAmount');
            Route::post('createSwappingOrder', 'SaleOrderController@createSwappingOrder');
            Route::post('recreate', 'SaleOrderController@recreate');
            Route::post('complete', 'SaleOrderController@complete');
        });
    });

    Route::resource('sale_orders', 'SaleOrderController')->except('update');
    Route::group(['prefix' => 'sale_orders'], function () {
        Route::group(['prefix' => '{id}'], function () {
            Route::post('', 'SaleOrderController@update');
            Route::post('draftverify', 'SaleOrderController@draftverify');
        });
    });

    Route::resource('inventory_products', 'InventoryProductController');

    Route::get('shipping_provinces', 'ShippingProvinceController@index');

    Route::get('shipping_districts', 'ShippingDistrictController@index');

    Route::get('shipping_wards', 'ShippingWardController@index');

    Route::resource('sale_customers', 'SaleCustomerController');


    Route::group(['prefix' => 'sale_order_shippings'], function () {
        Route::group(['prefix' => '{id}'], function () {
            Route::get('info', 'SaleOrderShippingController@info');
        });
    });
    Route::resource('sale_order_shippings', 'SaleOrderShippingController');

    Route::resource('products', 'ProductController');

    Route::resource('orders', 'OrderController')->except('update');;

    Route::group(['prefix' => 'orders'], function () {
        Route::group(['prefix' => '{id}'], function () {
            Route::post('', 'OrderController@update');
            Route::post('verify', 'OrderController@verify');
            Route::post('cancel', 'OrderController@cancel');
        });
    });

    Route::resource('promotions', 'PromotionController');

    Route::resource('vouchers', 'VoucherController');

    Route::resource('shipping_fees', 'ShippingFeeController');
});

