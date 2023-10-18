<?php

/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 5/20/2021
 * Time: 11:33 PM
 */

use Illuminate\Support\Facades\Artisan;
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

Route::post('login', 'LoginController@login');

Route::group(['middleware' => ['auth.super_admin']], function () {

    Route::get('dashboard', 'DashboardController@index');
    Route::get('clear_cache_page', 'DashboardController@clear');

    Route::resource('users', 'UserController');

    Route::resource('pages', 'PageController');
    Route::group(['prefix' => 'pages'], function () {
        Route::post('{id}/disable', 'PageController@disable');
        ROute::post('{id}/enable', 'PageController@enable');
    });
    Route::resource('widgets', 'WidgetController');
    Route::resource('blocks', 'BlockController');
    Route::group(['prefix' => 'blocks/{id}'], function () {
        Route::post('up', 'BlockController@up');
        Route::post('down', 'BlockController@down');
    });

    Route::resource('menus', 'MenuController');
    Route::group(['prefix' => 'menus/{id}'], function () {
        Route::post('up', 'MenuController@up');
        Route::post('down', 'MenuController@down');
    });

    Route::resource('menu_positions', 'MenuPositionController');

    Route::resource('companies', 'CompanyInformationController');

    Route::resource('channels', 'ChannelController');
    Route::group(['prefix' => 'channels/{id}'], function () {
        Route::post('', 'ChannelController@update');
    });

    Route::resource('payment_types', 'PaymentTypeController');
    Route::group(['prefix' => 'payment_types/{id}'], function () {
        Route::post('', 'PaymentTypeController@update');

    });

    Route::resource('sale_order_status', 'SaleOrderStatusController');
    Route::group(['prefix' => 'sale_order_status/{id}'], function () {
        Route::post('', 'SaleOrderStatusController@update');
    });

    Route::resource('payment_methods', 'PaymentMethodController');
    Route::group(['prefix' => 'payment_methods/{id}'], function () {
        Route::post('', 'PaymentMethodController@update');
        Route::post('destroyConfig', 'PaymentMethodController@destroyConfig');
        Route::post('destroyPaymentMethod', 'PaymentMethodController@destroy');
    });
    Route::resource('shipping_units', 'ShippingUnitController');
    Route::post('shipping_units/createUnitPartner', 'ShippingUnitController@createUnitPartner');
    Route::group(['prefix' => 'shipping_units/{id}'], function () {
        Route::post('', 'ShippingUnitController@update');
        Route::post('/synchronized', 'ShippingUnitController@synchronized');
    });

    Route::resource('shipping_status', 'SaleOrderShippingStatusController');
    Route::group(['prefix' => 'shipping_status/{id}'], function () {
        Route::post('', 'SaleOrderShippingStatusController@update');
    });

    Route::resource('dynamic_tables', 'DynamicTableController');
    ROute::resource('dynamic_table_columns', 'DynamicTableColumnController');
    Route::resource('dynamic_table_rows', 'DynamicTableRowController');

    Route::resource('forms', 'FormController');
    Route::resource('form_attributes', 'FormAttributeController');


    Route::resource('roles', 'RoleController');

    Route::group(['prefix' => 'user_roles'], function () {
        Route::get('', 'UserRoleController@index');
        Route::post('attach', 'UserRoleController@attachUserRole');
        Route::post('detach', 'UserRoleController@detachUserRole');
    });
});
