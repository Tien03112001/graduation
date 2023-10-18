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
Route::post('agents/check', 'AgentController@checkAgent');

Route::group(['middleware' => ['auth.admin']], function () {

    Route::resource('conversations', 'ConversationController');

    Route::resource('messages', 'MessageController');

    Route::resource('agents', 'AgentController');
    Route::group(['prefix' => 'agents'], function () {
        Route::post('/online','AgentController@online');
        Route::post('/offline','AgentController@offline');
    });
});
