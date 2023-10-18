<?php

namespace App\Providers;

use App\Utils\Caches\AppSettingUtil;
use App\Utils\Caches\BannerUtil;
use App\Utils\Caches\CompanyInformationUtil;
use App\Utils\Caches\EmbedScriptUtil;
use App\Utils\Caches\LanguageUtil;
use App\Utils\Caches\MenuUtil;
use App\Utils\CartUtil;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $headerCodes = EmbedScriptUtil::getInstance()->getHeaderCode();
            View::share('headerCodes', $headerCodes);

            $bodyCodes = EmbedScriptUtil::getInstance()->getBodyCode();
            View::share('bodyCodes', $bodyCodes);

            $menus = MenuUtil::getInstance()->getCachedData();
            View::share('menus', $menus);

            $companyInformation = CompanyInformationUtil::getInstance()->getCachedData();
            View::share('companyInformation', $companyInformation);

            $appSettings = AppSettingUtil::getInstance()->getCachedData();
            View::share('appSettings', $appSettings);

            $languages = LanguageUtil::getInstance()->getCachedData();
            View::share('languages', $languages);

            $banners = BannerUtil::getInstance()->getCachedData();
            View::share('banners', $banners);

            $cart_info = CartUtil::getInstance()->getItems();
            View::share('cart_info', $cart_info);
        } catch (\Exception $e) {
            Log::error($e);
        }

    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
