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
Route::get('/test', function(){
    dd(1);
});

Route::group(['middleware' => ['auth.owned_website']], function () {

    Route::get('dashboard', 'DashboardController@index');
    Route::group(['prefix' => 'cache'], function () {
        Route::get('', 'CacheController@index');
        Route::post('clear_cache_page', 'CacheController@clear');
    });
    Route::group(['prefix' => 'orders'], function () {
        Route::get('', 'OrderController@index');
        Route::post('{id}', 'OrderController@update');
    });
    Route::resource('users', 'UserController');

    Route::resource('articles', 'ArticleController');

    Route::resource('settings', 'SettingController');

    Route::resource('embed_scripts', 'EmbedScriptController');

    Route::resource('languages', 'LanguageController');

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
    Route::resource('post_categories', 'PostCategoryController');
    Route::resource('post_tags', 'PostTagController');

    Route::resource('meta_data', 'MetaDataController');
    Route::resource('structured_datas', 'StructureDataController');
    Route::resource('structured_data_types', 'StructureDataTypeController');
    Route::resource('structured_data_properties', 'StructureDataPropertyController')->except('update');
    Route::post('structured_data_properties/{id}', 'StructureDataPropertyController@update');
    Route::resource('companies', 'CompanyInformationController');

    Route::resource('comments', 'CommentController');

    Route::resource('dynamic_tables', 'DynamicTableController');
    ROute::resource('dynamic_table_columns', 'DynamicTableColumnController');
    Route::resource('dynamic_table_rows', 'DynamicTableRowController');
    Route::resource('product_variants', 'ProductVariantController');
    Route::group(['prefix' => 'product_variants/{id}'], function () {
        Route::post('', 'ProductVariantController@update');
    });

    Route::resource('forms', 'FormController');
    Route::resource('form_attributes', 'FormAttributeController');
    Route::resource('form_data', 'FormDataController');

    Route::resource('galleries', 'GalleryController');
    Route::group(['prefix' => 'galleries/{id}'], function () {
        Route::post('enable', 'GalleryController@enable');
        Route::post('disable', 'GalleryController@disable');
    });
    Route::resource('gallery_images', 'GalleryImageController')->except('update');
    Route::group(['prefix' => 'gallery_images/{id}'], function () {
        Route::post('', 'GalleryImageController@update');
        Route::post('up', 'GalleryImageController@up');
        Route::post('down', 'GalleryImageController@down');
    });

    Route::resource('photos', 'PhotoController')->except('update');
    Route::group(['prefix' => 'photos/{id}'], function () {
        Route::post('', 'GalleryImageController@update');
    });

    Route::resource('banner_groups', 'BannerGroupController');
    Route::resource('banners', 'BannerController');
    Route::group(['prefix' => 'banners'], function () {
        Route::post('{id}', 'BannerController@update');
        Route::put('{id}/priority', 'BannerController@priority');
        Route::group(['prefix' => '{id}'], function () {
            Route::post('up', 'BannerController@up');
            Route::post('down', 'BannerController@down');
        });
    });


    Route::resource('posts', 'PostController');
    Route::post('posts/{id}', 'PostController@update');
    Route::group(['prefix' => 'posts/{id}'], function () {
        Route::post('tags', 'PostController@tags');
        Route::post('enable', 'PostController@enable');
        Route::post('disable', 'PostController@disable');
        Route::post('up', 'PostController@enable');
        Route::post('down', 'PostController@disable');
    });
    Route::resource('related_posts', 'RelatedPostController');
    Route::group(['prefix' => 'related_posts/{id}'], function () {
        Route::post('up', 'RelatedPostController@up');
        Route::post('down', 'RelatedPostController@down');
    });


    Route::resource('products', 'ProductController')->except(['update']);
    Route::group(['prefix' => 'products/{id}'], function () {
        Route::post('', 'ProductController@update');
        Route::post('loadAvailableProducts', 'ProductController@loadAvailableProducts');
        Route::post('enable', 'ProductController@enable');
        Route::post('disable', 'ProductController@disable');
        Route::post('up', 'ProductController@up');
        Route::post('down', 'ProductController@down');
        Route::post('sync_tags', 'ProductController@syncTags');
        Route::post('attach_tags', 'ProductController@attachTags');
        Route::post('detach_tags', 'ProductController@detachTags');
    });

    Route::resource('product_categories', 'ProductCategoryController');
    Route::group(['prefix' => 'product_categories/{id}'], function () {
        Route::post('up', 'ProductCategoryController@up');
        Route::post('down', 'ProductCategoryController@down');
    });

    Route::resource('product_tags', 'ProductTagController');
    Route::group(['prefix' => 'product_tags/{id}'], function () {
        Route::post('sync_products', 'ProductTagController@syncProducts');
        Route::post('attach_products', 'ProductTagController@attachProducts');
        Route::post('detach_products', 'ProductTagController@detachProducts');
    });

    Route::resource('related_products', 'RelatedProductController');
    Route::group(['prefix' => 'related_products/{id}'], function () {
        Route::post('up', 'RelatedProductController@up');
        Route::post('down', 'RelatedProductController@down');
    });

    Route::resource('job_postings', 'JobPostingController');
    Route::resource('job_posting_cvs', 'JobPostingCvController');

    Route::group(['prefix' => 'job_postings/{id}'], function () {
        Route::post('', 'JobPostingController@update');
        Route::post('enable', 'JobPostingController@enable');
        Route::post('disable', 'JobPostingController@disable');
    });

    Route::resource('provinces', 'ProvinceController');

    Route::resource('districts', 'DistrictController');

    Route::resource('wards', 'WardController');

    Route::resource('shipping_fee_tables', 'ShippingFeeTableController');

    Route::resource('promotions', 'PromotionController');
    Route::group(['prefix' => 'promotions/{id}'], function () {
        Route::post('enable', 'PromotionController@enable');
        Route::post('disable', 'PromotionController@disable');
        Route::post('sync_products', 'PromotionController@syncProducts');
        Route::post('attach_products', 'PromotionController@attachProducts');
        Route::post('detach_products', 'PromotionController@detachProducts');
    });

    Route::resource('vouchers', 'VoucherController');
    Route::group(['prefix' => 'vouchers/{id}'], function () {
        Route::post('enable', 'VoucherController@enable');
        Route::post('disable', 'VoucherController@disable');
    });

    Route::resource('facebook_product_categories', 'FacebookProductCategoryController');
    Route::group(['prefix' => 'facebook_product_categories'], function () {
        Route::post('import', 'FacebookProductCategoryController@import');
        Route::post('truncate', 'FacebookProductCategoryController@truncate');
    });

    Route::group(['prefix' => 'facebook_products'], function () {
        Route::get('xml', 'FacebookProductController@xmlFacebookCatalog');
        Route::post('{id}/enable', 'FacebookProductController@enable');
        Route::post('{id}/disable', 'FacebookProductController@disable');
    });
    Route::resource('facebook_products', 'FacebookProductController');
});
