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
Route::get('/', function () {
    return view('welcome');
});
Route::post('login', 'LoginController@login');

Route::group(['middleware' => 'auth.admin'], function () {

    Route::get('clear_cache_page', 'ClearCachePageController@index');

    Route::resource('settings', 'SettingDefaultController');
    Route::resource('embed_codes', 'SettingEmbedCodeController');

    Route::resource('menus', 'SettingMenuController');
    Route::get('menu_types', 'SettingMenuTypeController@index');
    Route::get('menu_supports', 'SettingMenuTypeController@index');
    Route::resource('menu_positions', 'SettingMenuPositionController');

    Route::resource('banner_groups', 'ContentBannerGroupController');
    Route::resource('banners', 'ContentBannerController');
    Route::post('banners/{id}', 'ContentBannerController@update');
    Route::put('banners/{id}/priority', 'ContentBannerController@update_priority');

    Route::resource('comments', 'ContentCommentController');
    Route::group(['prefix' => 'comments/{id}'], function () {
        Route::post('enable', 'ContentCommentController@enable');
        Route::post('disable', 'ContentCommentController@disable');
    });

    Route::resource('companies', 'ContentCompanyController');
    Route::resource('labels', 'SettingLabelController');
    Route::resource('structured_datas', 'StructuredDataController');

    Route::resource('company_branches', 'ContentCompanyBranchController');
    Route::post('company_branches/{id}', 'ContentCompanyBranchController@update');

    Route::resource('partners', 'ContentPartnerController');
    Route::post('partners/{id}', 'ContentPartnerController@update');


    Route::resource('staffs', 'ContentStaffController');
    Route::post('staffs/{id}', 'ContentStaffController@update');

    Route::resource('galleries', 'ContentGalleryController');
    Route::get('galleries/{id}/images', 'ContentGalleryController@index_images');
    Route::resource('photos', 'ContentPhotoController');
    Route::post('photos/{id}', 'ContentPhotoController@update');

    Route::resource('post_categories', 'ContentPostCategoryController');
    Route::post('post_categories/{id}', 'ContentPostCategoryController@update');
    Route::group(['prefix' => 'post_categories/{id}'], function () {
        Route::post('enable', 'ContentPostCategoryController@enable');
        Route::post('disable', 'ContentPostCategoryController@disable');
        Route::post('change_order', 'ContentPostCategoryController@change_order');
    });

    Route::resource('posts', 'ContentPostController');
    Route::post('posts/{id}', 'ContentPostController@update');
    Route::group(['prefix' => 'posts/{id}'], function () {
        Route::post('tags', 'ContentPostController@tags');
        Route::post('enable', 'ContentPostController@enable');
        Route::post('disable', 'ContentPostController@disable');
        Route::post('change_order', 'ContentPostController@change_order');
    });

    Route::resource('post_tags', 'ContentPostTagController');
    Route::resource('tags', 'ContentTagController');

    Route::resource('product_categories', 'ContentProductCategoryController');
    Route::post('product_categories/{id}', 'ContentProductCategoryController@update');
    Route::group(['prefix' => 'product_categories/{id}'], function () {
        Route::post('enable', 'ContentProductCategoryController@enable');
        Route::post('disable', 'ContentProductCategoryController@disable');
        Route::post('change_order', 'ContentProductCategoryController@change_order');
    });


    Route::group(['prefix' => 'products'], function () {
        Route::get('sync_xml', 'ContentProductController@syncXml');
        Route::post('import', 'ContentProductController@import');
        Route::group(['prefix' => '{id}', 'where' => [
            'id' => '[0-9]+'
        ]], function () {
            Route::post('update', 'ContentProductController@update');
            Route::post('enable', 'ContentProductController@enable');
            Route::post('disable', 'ContentProductController@disable');
            Route::post('change_order', 'ContentProductController@change_order');
        });
    });
    Route::resource('products', 'ContentProductController');

    Route::group(['prefix' => 'pages/{id}'], function () {
        Route::post('enable', 'ContentPageController@enable');
        Route::post('disable', 'ContentPageController@disable');
    });
    Route::resource('pages', 'ContentPageController');
    Route::resource('seo_metas', 'SeoMetaController');
    Route::resource('widgets', 'SettingWidgetController');
    Route::resource('users', 'UserController');

    Route::resource('testimonials', 'ContentTestimonialController');
    Route::post('testimonials/{id}', 'ContentTestimonialController@update');
    Route::post('testimonials/{id}/change_order', 'ContentTestimonialController@change_order');

    Route::put('articles/{id}', 'ContentArticleController@update');
    Route::resource('languages', 'SettingLanguageController');

    Route::resource('product_types', 'ProductTypeController');
    Route::post('product_types/{id}', 'ProductTypeController@update');
    Route::group(['prefix' => 'product_types/{id}'], function () {
        Route::post('enable', 'ProductTypeController@enable');
        Route::post('disable', 'ProductTypeController@disable');
        Route::post('change_order', 'ProductTypeController@change_order');
    });

    Route::resource('orders', 'OrderController');
    Route::post('orders/export', 'OrderController@export');

    Route::resource('customers', 'CustomerController');
    Route::get('get_category', 'ContentProductCategoryController@get_category');

    Route::resource('attributes', 'ContentAttributeController');
    Route::group(['prefix' => 'attributes/{id}'], function () {
        Route::get('option', 'ContentAttributeController@show_option');
    });
    Route::resource('attribute_options', 'AttributeOptionController');

    Route::resource('product_tags', 'ProductTagController');
    Route::group(['prefix' => 'product_tags/{id}'], function () {
        Route::post('change_order_product_all', 'ProductTagController@change_order_product_all');
        Route::post('tags', 'ProductTagController@assign');
        Route::post('change_order', 'ProductTagController@change_order');
        Route::post('/change_tag_order/{product_id}', 'ProductTagController@change_tag_order');
        Route::delete('/products/all', 'ProductTagController@delete_product_all');
        Route::delete('/product/{product_id}', 'ProductTagController@delete_product');
    });
    Route::get('load_product', 'ProductTagController@load_product');
    Route::post('add_product_to_tag', 'ProductTagController@add_product');

    Route::resource('product_variants', 'ProductVariantController');
    Route::get('product_variants/{product_id}/variant', 'ProductVariantController@get_product');
    Route::post('un_variant_product', 'ProductVariantController@add_variant');

    Route::resource('product_combos', 'ProductComboController');
    Route::group(['prefix' => 'product_combos/{id}'], function () {
        Route::post('enable', 'ProductComboController@enable');
        Route::post('disable', 'ProductComboController@disable');
        Route::get('products', 'ProductComboController@loadProducts');
        Route::post('products', 'ProductComboController@addProduct');
        Route::delete('products/all', 'ProductComboController@deleteProductAll');
        Route::delete('products/{productId}', 'ProductComboController@deleteProduct');
    });

    Route::group(['prefix' => 'product_inventories'], function () {
        Route::delete('all', 'ProductInventoryController@deleteAll');
    });
    Route::resource('product_inventories', 'ProductInventoryController');


    Route::group(['prefix' => 'combo_product_prices'], function () {
        Route::post('/combos/{id}/bulk', 'ComboProductPriceController@bulk');
        Route::delete('products/{id}', 'ComboProductPriceController@deleteByProduct');
        Route::delete('combos/{id}', 'ComboProductPriceController@deleteByCombo');
    });
    Route::resource('combo_product_prices', 'ComboProductPriceController');

    Route::resource('form_contacts', 'FormContactController');

    Route::resource('contact_forms', 'ContactFormController');

    Route::resource('contact_form_data', 'ContactFormDataController');

    Route::resource('shopping_g_products', 'ShoppingGProductController');
    Route::group(['prefix' => 'shopping_g_products'], function () {
        Route::post('{id}/enable', 'ShoppingGProductController@enable');
        Route::post('{id}/disable', 'ShoppingGProductController@disable');
        Route::post('export', 'ShoppingGProductController@export');
        Route::post('import', 'ShoppingGProductController@import');
        Route::post('sync', 'ShoppingGProductController@sync');
    });

    Route::resource('shopping_f_products', 'ShoppingFProductController');
    Route::group(['prefix' => 'shopping_f_products'], function () {
        Route::post('{id}/enable', 'ShoppingFProductController@enable');
        Route::post('{id}/disable', 'ShoppingFProductController@disable');
        Route::post('export', 'ShoppingFProductController@export');
        Route::post('import', 'ShoppingFProductController@import');
        Route::post('sync', 'ShoppingFProductController@sync');
    });

});

