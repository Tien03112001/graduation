<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Không được xóa route này*/
Route::get('/execute', 'ExecuteController@index');

//Ajax
Route::get('provinces/{id}/districts', 'ShippingFeeTableController@getDistricts');
Route::get('districts/{id}/wards', 'ShippingFeeTableController@getWards');
Route::get('getFee', 'ShippingFeeTableController@getFee');

//IPN URL
Route::group(['prefix' => 'ipn'], function () {
    Route::get('vnpay', 'IPNController@vnpay');
    Route::get('momo', 'IPNController@momo');
    Route::get('paypal', 'IPNController@paypal');
});

Route::get('/', 'HomeController@index');
Route::get('/contact', 'ContactController@index');
Route::get('/about', 'AboutController@index');

Route::get('/policy/{slug}', 'PolicyController@detail');



Route::post('/forms', 'FormController@store')->name('forms.submit');
Route::post('/comments', 'CommentController@store')->name('comments.submit');

Route::get('/error', 'ErrorController@index');
Route::get('/blog', 'PostController@index');
Route::get('/post_categories/{slug}', 'PostCategoryController@detail');
Route::get('/post_categories/{categorySlug}/posts/{slug}', 'PostController@detail');
Route::get('/post_tags/{slug}', 'PostTagController@detail');
Route::get('/post_search', 'PostSearchController@search');

Route::get('/buyNow/{id}', 'CartController@buyNow');
Route::group(['prefix' => 'cart'], function () {
    Route::get('addItem/{id}', 'CartController@addItem');
    Route::get('buyItem/{id}', 'CartController@buyItem');
    Route::get('removeItem/{id}', 'CartController@removeItem');
    Route::get('updateQuantity/{id}', 'CartController@updateQuantity');
    Route::post('applyVoucher', 'CartController@applyVoucher');
    Route::get('', 'CartController@index');
    Route::get('pushQuantity/{id}', 'CartController@pushQuantity');
    Route::get('minusQuantity/{id}', 'CartController@minusQuantity');
});
Route::get('checkout', 'CartController@checkout');
Route::group(['prefix' => 'orders'], function () {
    Route::post('/create', 'OrderController@create');
    Route::get('/complete/{channel}', 'OrderController@complete');
});

Route::get('/products', 'ProductController@index');
Route::get('/promotion_products', 'ProductCategoryController@promotion_products');
Route::get('/product_categories/{slug}', 'ProductCategoryController@detail');
Route::get('/product_categories/{categorySlug}/products/{slug}', 'ProductController@detail');
Route::get('/product_tags/{slug}', 'ProductTagController@detail');
Route::get('/product_search', 'ProductSearchController@search');

Route::get('/promotions/{slug}', 'PromotionController@detail');

Route::get('/{slug}', 'PageController@detail');
