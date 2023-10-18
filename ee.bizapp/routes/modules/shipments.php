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

Route::group(['middleware' => 'auth.shipments'], function () {
    Route::get('dashboard', 'DashboardController@index');

    Route::group(['prefix' => 'sale_orders'], function () {
        Route::resource('/', 'SaleOrderController', ['only' => ['index']]);
        Route::get('searchExchangeOrders', 'SaleOrderController@searchExchangeOrders');
        Route::group(['prefix' => '{id}'], function () {
            Route::post('note', 'SaleOrderController@noteShip');
            Route::post('done', 'SaleOrderController@done');
            Route::post('cancel', 'SaleOrderController@cancel');
            Route::post('return', 'SaleOrderController@return');
            Route::post('refund', 'SaleOrderController@refund');
            Route::post('receiveRefund', 'SaleOrderController@receiveRefund');
            Route::post('reUpdate', 'SaleOrderController@reUpdate');
        });
        Route::get('printShippingBill', 'SaleOrderController@printShippingBill');
    });

    Route::resource('sale_order_shippings', 'SaleOrderShippingController');
    Route::get('sale_order_shippings/{id}/printBill', 'SaleOrderShippingController@printBill');
    Route::post('sale_order_shippings/{id}/done', 'SaleOrderShippingController@done');

    Route::get('sale_order_shipping_pick_addresses', 'SaleOrderShippingPickAddressController@index');

    Route::resource('inventory_products', 'InventoryProductController');

    Route::resource('products', 'ProductController');

    Route::resource('product_variants', 'ProductVariantController');

    Route::resource('exporting_notes', 'ExportingNoteController');
    Route::get('exporting_notes/{id}/print', 'ExportingNoteController@printBill');
});

