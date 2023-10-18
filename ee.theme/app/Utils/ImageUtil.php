<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31/05/2018
 * Time: 1:50 CH
 */

namespace App\Utils;


use Illuminate\Support\Str;

class ImageUtil
{
    public static function getMinUrl($url)
    {
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        return Str::replaceLast(".$ext", ".min.$ext", $url);
    }
}
