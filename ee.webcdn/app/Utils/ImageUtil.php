<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 9/9/2020
 * Time: 2:58 AM
 */

namespace App\Utils;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageUtil
{

    /**
     * @param string $base64string
     * @param string|null $filename
     * @param string|null $path
     * @param int $fullQuantity
     * @param int $minQuantity
     * @return string
     * @throws \Exception
     */
    public static function save($base64string, $filename = 'temp', $path = '', $fullQuantity = 100, $minQuantity = 10)
    {
        $status = preg_match('/^data:image\/(\w+);base64,(.+)/', $base64string, $matches);
        if ($status) {
            $extension = $matches[1];
            $savedFilePath = self::saveToCDN($path, $filename, $base64string, $extension, $fullQuantity, $minQuantity);
            if ($savedFilePath) {
                return $savedFilePath;
            } else {
                throw new \Exception('Can\'t save file');
            }
        } else {
            throw new \Exception('Invalid String');
        }
    }

    /**
     * @param $path
     * @param $filename
     * @param $base64string
     * @param $extension
     * @param int $fullQuantity
     * @param int $minQuantity
     * @return bool
     * @throws \Exception
     */
    public static function saveToCDN($path, $filename, $base64string, $extension, $fullQuantity = 100, $minQuantity = 10)
    {
        $temp = self::createWebpImageTempFile($base64string, $extension, $fullQuantity);
        $uploadFile = new UploadedFile($temp, "$filename.webp", 'image/webp');
        $savedFilePath = FileStorageUtil::getInstance()->putFile($path, $uploadFile);
        unlink($temp);
        $filename = pathinfo($savedFilePath, PATHINFO_FILENAME);
        $temp = self::createWebpImageTempFile($base64string, $extension, $minQuantity);
        $uploadFile = new UploadedFile($temp, "$filename.min.webp", 'image/webp');
        FileStorageUtil::getInstance()->putFileAs($path, "$filename.min.webp", $uploadFile);
        unlink($temp);
        return $savedFilePath;
    }

    /**
     * @param $base64EncodeStr
     * @param string $ext
     * @param int $quality
     * @return string
     * @throws \Exception
     */
    public static function createWebpImageTempFile($base64EncodeStr, $ext = 'jpeg', $quality = 100)
    {
        $temp = storage_path('temp_' . Str::random(50) . '.' . $ext);
        if ($ext == 'png') {
            $im = imagecreatefrompng($base64EncodeStr);
            imagepalettetotruecolor($im);
            imagewebp($im, $temp, $quality);
            imagedestroy($im);
        } else if ($ext == 'jpg' || $ext == 'jpeg') {
            $im = imagecreatefromjpeg($base64EncodeStr);
            imagepalettetotruecolor($im);
            imagewebp($im, $temp, $quality);
            imagedestroy($im);
        } elseif ($ext == 'gif') {
            $im = imagecreatefromgif($base64EncodeStr);
            imagepalettetotruecolor($im);
            imagewebp($im, $temp, $quality);
            imagedestroy($im);
        } elseif ($ext == 'webp') {
            $im = imagecreatefromwebp($base64EncodeStr);
            imagepalettetotruecolor($im);
            imagewebp($im, $temp, $quality);
            imagedestroy($im);
        } else {
            throw new \Exception('Image extension not support');
        }
        return $temp;
    }

    public static function getBase64String($path, $type)
    {
        return 'data:' . $type . ';base64,' . base64_encode(file_get_contents($path));
    }
}
