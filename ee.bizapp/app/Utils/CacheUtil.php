<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 4/27/2019
 * Time: 8:12 PM
 */

namespace App\Utils;

use App\Models\ContentBannerGroup;
use App\Models\ContentCompanyBranch;
use App\Models\ContentPage;
use App\Models\ContentPostTag;
use App\Models\SettingDefault;
use App\Models\SettingEmbedCode;
use App\Models\SettingLanguage;
use App\Models\SettingMenuPosition;
use App\Models\SettingMenuType;
use App\Models\SettingWidget;
use Illuminate\Support\Facades\Cache;

class CacheUtil
{
    public static $TTL = 2 * 60 * 60;

    public static $EMBED_CODE_KEY = 'embed_codes';
    public static $APP_SETTING_KEY = 'app_settings';
    public static $ALL_TAGS_KEY = 'all_tags';
    public static $ALL_PAGES_KEY = 'all_pages';

    public static function getEmbeddedCode()
    {
        if (Cache::has(self::$EMBED_CODE_KEY)) {
            $data = Cache::get(self::$EMBED_CODE_KEY);
        } else {
            $data = self::reloadEmbedCode();
        }
        return json_decode($data, true);
    }

    public static function reloadEmbedCode()
    {
        Cache::forget(self::$EMBED_CODE_KEY);
        $elements = SettingEmbedCode::all();
        $data = json_encode($elements->toArray());
        Cache::put(self::$EMBED_CODE_KEY, $data, self::$TTL);
        return $data;
    }

    public static function getAppSetting()
    {
        if (Cache::has(self::$APP_SETTING_KEY)) {
            $data = Cache::get(self::$APP_SETTING_KEY);
        } else {
            $data = self::reloadAppSetting();
        }
        return json_decode($data, true);
    }

    public static function reloadAppSetting()
    {
        Cache::forget(self::$APP_SETTING_KEY);
        $elements = SettingDefault::all();
        $config = [];
        foreach ($elements as $d) {
            $config[$d['name']] = $d['value'];
        }
        $data = json_encode($config);
        Cache::put(self::$APP_SETTING_KEY, $data, self::$TTL);
        return $data;
    }

    public static function getAllTags()
    {
        if (Cache::has(self::$ALL_TAGS_KEY)) {
            $data = Cache::get(self::$ALL_TAGS_KEY);
        } else {
            $data = self::reloadAllTags();
        }
        return json_decode($data, true);
    }

    public static function reloadAllTags()
    {
        Cache::forget(self::$ALL_TAGS_KEY);
        $elements = ContentPostTag::all();
        $data = json_encode($elements->toArray());
        Cache::put(self::$ALL_TAGS_KEY, $data, self::$TTL);
        return $data;
    }

    public static function getAllPages()
    {
        if (Cache::has(self::$ALL_PAGES_KEY)) {
            $data = Cache::get(self::$ALL_PAGES_KEY);
        } else {
            $data = self::reloadAllPages();
        }
        return json_decode($data, true);
    }

    public static function reloadAllPages()
    {
        Cache::forget(self::$ALL_PAGES_KEY);
        $elements = ContentPage::published()->get();
        $data = json_encode($elements->toArray());
        Cache::put(self::$ALL_PAGES_KEY, $data, self::$TTL);
        return $data;
    }

    public static $ALL_MENUS_KEY = 'all_menus';

    public static function getMenu()
    {
        if (Cache::has(self::$ALL_MENUS_KEY)) {
            $data = Cache::get(self::$ALL_MENUS_KEY);
        } else {
            $data = self::reloadMenu();
        }
        return json_decode($data, true);
    }

    public static function reloadMenu()
    {
        Cache::forget(self::$ALL_MENUS_KEY);
        $elements = SettingMenuPosition::with(['menus' => function ($q) {
            $q->where('parent_id', 0)->with(['children' => function ($q) {
                $q->with(['children' => function ($q) {
                    $q->orderBy('order', 'asc');
                }])->orderBy('order', 'asc');
            }])->orderBy('order', 'asc');
        }])->get();
        $config = [];
        foreach ($elements as $d) {
            if (isset($d->menus)) {
                $data = self::generateMenu($d->menus);
                $config[$d['code']] = $data;
            }
        }
        $data = json_encode($config);
        Cache::forever(self::$ALL_MENUS_KEY, $data);
        return $data;
    }

    public static function generateMenu($contents)
    {
        $output = [];
        foreach ($contents as $content) {
            if ($content->menuable_type == null) {
                if (count($content->children) > 0) {
                    $child = self::generateMenu($content->children);
                    array_push($output, [
                        'menuable_type' => $content->menuable_type,
                        'menuable_id' => $content->url,
                        'name' => $content->name,
                        'url' => $content->url,
                        'children' => $child
                    ]);
                } else {
                    array_push($output, [
                        'menuable_type' => $content->menuable_type,
                        'menuable_id' => $content->url,
                        'name' => $content->name,
                        'url' => $content->url
                    ]);
                }
            } elseif ($content->menuable_type != null) {
                $menu_type = SettingMenuType::where('type', $content->menuable_type)->first();
                $data = null;
                eval($menu_type->code);
                foreach ($data as $d) {
                    if ($d->id == $content->menuable_id) {
                        $data = $d;
                        break;
                    }
                }
                if (count($content->children) > 0) {
                    $child = self::generateMenu($content->children);
                    if (isset($data)) {
                        array_push($output, [
                            'menuable_type' => $content->menuable_type,
                            'menuable_id' => $content->menuable_id,
                            'name' => $content->name ?? $data->name,
                            'url' => $content->url ?? $data->full_path,
                            'children' => $child
                        ]);
                    }
                } else {
                    if (isset($data)) {
                        array_push($output, [
                            'menuable_type' => $content->menuable_type,
                            'menuable_id' => $content->menuable_id,
                            'name' => $content->name ?? $data->name,
                            'url' => $content->url ?? $data->full_path
                        ]);
                    }
                }
            }
        }
        return $output;
    }

    public static $ALL_BANNERS_KEY = 'all_banners';

    public static function getBanner()
    {
        if (Cache::has(self::$ALL_BANNERS_KEY)) {
            $data = Cache::get(self::$ALL_BANNERS_KEY);
        } else {
            $data = self::reloadBanner();
        }
        return json_decode($data, true);
    }

    public static function reloadBanner()
    {
        Cache::forget(self::$ALL_BANNERS_KEY);
        $elements = ContentBannerGroup::with(['banners' => function ($q) {
            $q->orderBy('priority', 'desc');
        }])
            ->get();
        $config = [];
        foreach ($elements as $d) {
            if (!isset($config[$d['code']])) {
                $config[$d['code']] = $d->banners;
            }
        }
        $data = json_encode($config);
        Cache::forever(self::$ALL_BANNERS_KEY, $data);
        return $data;
    }

    public static $ALL_WIDGETS_KEY = 'all_widgets';

    public static function getWidget()
    {
        if (Cache::has(self::$ALL_WIDGETS_KEY)) {
            $data = Cache::get(self::$ALL_WIDGETS_KEY);
        } else {
            $data = self::reloadWidget();
        }
        return json_decode($data, true);
    }

    public static function reloadWidget()
    {
        Cache::forget(self::$ALL_WIDGETS_KEY);
        $elements = SettingWidget::all();
        $config = [];
        foreach ($elements as $d) {
            $d['html'] = str_replace('{{', '', $d['html']);
            $d['html'] = str_replace('}}', '', $d['html']);
            $config[$d['name']] = $d;
        }
        $data = json_encode($config);
        Cache::forever(self::$ALL_WIDGETS_KEY, $data);
        return $data;
    }

    public static $ALL_LANGUAGE_KEY = 'languages';

    public static function getLanguage()
    {
        if (Cache::has(self::$ALL_LANGUAGE_KEY)) {
            $data = Cache::get(self::$ALL_LANGUAGE_KEY);
        } else {
            $data = self::reloadLanguage();
        }
        return json_decode($data, true);
    }

    public static function reloadLanguage()
    {
        Cache::forget(self::$ALL_LANGUAGE_KEY);
        $elements = SettingLanguage::all();
        $config = [];
        foreach ($elements as $d) {
            $config[$d['name']] = $d['translate'];
        }
        $data = json_encode($config);
        Cache::forever(self::$ALL_LANGUAGE_KEY, $data);
        return $data;
    }

    public static $ALL_BRANCHES_KEY = 'all_branches';

    public static function getAllBranches()
    {
        if (Cache::has(self::$ALL_BRANCHES_KEY)) {
            $data = Cache::get(self::$ALL_BRANCHES_KEY);
        } else {
            $data = self::reloadAllBranches();
        }
        return json_decode($data, true);
    }

    public static function reloadAllBranches()
    {
        Cache::forget(self::$ALL_BRANCHES_KEY);
        $elements = ContentCompanyBranch::all();
        $config = [];
        foreach ($elements as $d) {
            array_push($config, $d->toArray());
        }
        $data = json_encode($config);
        Cache::forever(self::$ALL_BRANCHES_KEY, $data);
        return $data;
    }

}
