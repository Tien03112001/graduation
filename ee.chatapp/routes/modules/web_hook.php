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

Route::resource('commands', 'CommandWebHookController')
    ->only('store');

Route::get('facebook', 'FacebookWebHookController@verify');
Route::post('facebook', 'FacebookWebHookController@subscribe');