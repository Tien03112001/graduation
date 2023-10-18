<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/4/2018
 * Time: 1:17 PM
 */

namespace App\Utils;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUtil
{

    public static function base64ToImage($base64_string)
    {
        $data = explode(',', $base64_string);
        if (count($data) != 2) {
            return null;
        }
        preg_match('/^data\:image\/(\w+)\;base64$/', $data[0], $matches);
        if (empty($matches)) {
            return null;
        }

        return self::saveStringToImageFile(base64_decode($data[1]), Str::lower($matches[1]));
    }

    public static function saveStringToImageFile(string $fileData, $extension, $quantity = 100)
    {
        $image = imagecreatefromstring($fileData);
        $imageType = $extension == 'jpeg' ? 'jpg' : $extension;
        $outputFile = public_path() . '\tmp_' . Str::random(64) . '.' . $imageType;
        if ($imageType == 'jpg' || $imageType == 'jpeg') {
            imagejpeg($image, $outputFile, $quantity);
        } elseif ($imageType == 'png') {
            imagepng($image, $outputFile);
        } elseif ($imageType == 'gif') {
            imagegif($image, $outputFile);
        }
        imagedestroy($image);
        $file = new UploadedFile($outputFile, 'image/' . $extension, null, null);
        $urlSavedFile = $file->store('images', 'public');
        unlink($outputFile);
        return $urlSavedFile;
    }

    public static function removePublicFile($input)
    {
        if (empty($input)) {
            return;
        }

        if (is_string($input)) {
            if (Str::startsWith($input, 'http')) {
                return;
            }
            FileStorageUtil::getInstance()->deleteFiles($input);
            return;
        }

        if (is_array($input)) {
            foreach ($input as $url) {
                self::removePublicFile($url);
            }
            return;
        }
    }

    public static function getDefaultImage()
    {
        return FileStorageUtil::getInstance()->url('images/default.jpg');
    }

    public static function compressImage($source, $quality)
    {
        $pathSource = $source;
        if (file_exists(storage_path('app/public/' . $source))) {
            $pathSource = storage_path('app/public/' . $source);
        }
        $info = getimagesize($pathSource);
        if (!Str::startsWith($info['mime'], 'image')) {
            return null;
        }
        $image = null;
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($pathSource);
        elseif ($info['mime'] == 'image/jpg')
            $image = imagecreatefromjpeg($pathSource);
        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($pathSource);
        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($pathSource);
        elseif ($info['mime'] == 'image/webp')
            $image = imagecreatefromwebp($pathSource);
        $destination = self::getZipPathFile($source);
        imagejpeg($image, storage_path('app/public/' . $destination), $quality);
        return $destination;
    }

    public static function getZipPathFile($source)
    {
        $file = pathinfo($source);
        return join('', [$file['dirname'], '/', $file['filename'], '_zip', '.jpg']);
    }


}
