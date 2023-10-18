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

Route::group(['middleware' => 'auth.inventory'], function () {
    Route::get('dashboard', 'DashboardController@index');

    Route::group(['prefix' => 'inventory_products'], function () {
        Route::get('export', 'InventoryProductController@export');
        Route::post('import', 'InventoryProductController@import');
        Route::post('{id}/add_quantity', 'InventoryProductController@addQuantity');
        Route::get('{id}/printBarcode', 'InventoryProductController@printBarcode')->where('id', '[0-9]+');
    });
    Route::resource('inventory_products', 'InventoryProductController', ['only' => ['index', 'store', 'update', 'destroy']]);

    Route::resource('products', 'ProductController');

    Route::resource('exporting_notes', 'ExportingNoteController');
    Route::group(['prefix' => 'exporting_notes'], function () {
        Route::group(['prefix' => '{id}'], function () {
            Route::post('approve', 'ExportingNoteController@approve');
            Route::post('disapprove', 'ExportingNoteController@disapprove');
            Route::get('print', 'ExportingNoteController@printBill');
        });
    });

    Route::resource('variants', 'VariantController');

    Route::resource('facebook_product_categories', 'FacebookProductCategoryController');
    Route::group(['prefix' => 'facebook_product_categories'], function () {
        Route::post('import', 'FacebookProductCategoryController@import');
    });
    
    Route::resource('facebook_products', 'FacebookProductController');
    Route::group(['prefix' => 'facebook_products/{id}'], function () {
        Route::post('enable', 'FacebookProductController@enable');
        Route::post('disable', 'FacebookProductController@disable');
    });
});

