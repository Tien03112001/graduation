<?php

/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 5/20/2021
 * Time: 11:33 PM
 */

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
    Route::group(['prefix' => 'facebook_fanpages'], function () {
        Route::group(['prefix' => '{id}'], function () {
            Route::resource('assign', 'FacebookFanpageController@assign');
        });
    });
    Route::resource('facebook_fanpages', 'FacebookFanpageController');
    Route::apiResource('agents', 'AgentController');

    Route::resource('facebook_admin_accounts', 'FacebookAdminAccountController');

    Route::group(['prefix' => 'agent_pages'], function () {
        Route::get('', 'AgentPageController@index');
        Route::post('attach', 'AgentPageController@attachAgentPage');
        Route::post('detach', 'AgentPageController@detachAgentPage');
    });
});
