<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/18/2018
 * Time: 6:24 PM
 */

namespace App\Utils;

use Illuminate\Support\Str;

class StringUtil
{
    public static function existsURL(string $string)
    {
        return strpos($string, 'http') !== false || strpos($string, 'www.') !== false;
    }

    public static function joinPaths()
    {
        $args = func_get_args();
        $paths = array();
        foreach ($args as $arg) {
            $paths = array_merge($paths, (array)$arg);
        }

        $paths = array_map(function ($p) {
            return trim($p);
        }, $paths);
        $paths = array_filter($paths);
        return join('/', $paths);
    }

    /**
     * Bỏ dấu chuỗi ký tự
     * @param string $str
     * @return null|string|string[]
     */
    private static function nonSign(string $str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);

        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);

        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);

        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);

        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);

        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);

        $str = preg_replace("/(đ)/", 'd', $str);

        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);

        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);

        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);

        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);

        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);

        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);

        $str = preg_replace("/(Đ)/", 'D', $str);

        return $str;

    }

    public static function price_format($price = null)
    {
        if (!is_null($price)) {
            return number_format($price, 0, '.', ',') . 'đ';
        }
        return null;
    }

    public static function validateAddress($address)
    {
        if (function_exists('filter_var')) { //Introduced in PHP 5.2
            if (filter_var($address, FILTER_VALIDATE_EMAIL) === FALSE) {
                return false;
            } else {
                return true;
            }
        } else {
            return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $address);
        }
    }

    public static function formatPhoneNumber(string $phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        $phone = preg_replace('/.+(\d{9})$/', '0$1', $phone);
        return $phone;
    }

    public static function php2js($data)
    {
        return base64_encode(json_encode($data));
    }
}
