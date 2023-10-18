<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 7/14/2022
 * Time: 16:41
 */

namespace App\Utils;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageUtil
{
    /**
     * @param string $path
     * @param UploadedFile $file
     * @return bool
     */
    public static function putFile($path, $file)
    {
        $savedPath = Storage::disk('system_file_storage')->put($path, $file);
        if (!$savedPath) {
            return false;
        }
        return Storage::disk('system_file_storage')->url($savedPath);
    }


    /**
     * @param array|string|null $urls
     * @return bool
     */
    public static function deleteFiles($urls)
    {
        if (is_array($urls)) {
            foreach ($urls as $url) {
                if (!FileStorageUtil::deleteFiles($url)) {
                    return false;
                }
            }
            return true;
        } elseif (is_string($urls)) {
            $root = Storage::disk('system_file_storage')->url('');
            if (Str::startsWith($urls, $root)) {
                $path = Str::replaceFirst($root, '', $urls);
                return Storage::disk('system_file_storage')->delete($path);
            }
        }
        return false;
    }


}