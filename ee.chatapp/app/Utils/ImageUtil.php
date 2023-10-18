<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 9/9/2020
 * Time: 2:58 AM
 */

namespace App\Utils;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUtil
{
    /**
     * @param UploadedFile $file
     * @param string $dest
     * @param int $max_width
     * @param int $max_height
     * @param int $quality
     * @param string $disk
     * @return null|string
     */
    public static function optimize(UploadedFile $file, string $dest,
                                    int $max_width = 900, int $max_height = 900, int $quality = 90,
                                    $disk = 'system_file_storage')
    {
        $content = self::compress($file, $max_width, $quality, $max_height);
        try {
            return self::save($content, $dest, $disk);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $base64str
     * @param string $dest
     * @param int $max_width
     * @param int $max_height
     * @param int $quality
     * @param string $disk
     * @return string
     * @throws \Exception
     */
    public static function optimizeFromBase64Image(string $base64str, string $dest,
                                                   int $quality = 90, int $max_width = 900, int $max_height = 900,
                                                   $disk = 'public')
    {
        $tempFile = self::save($base64str, 'tmp', 'local');
        $file = new UploadedFile(Storage::path($tempFile), 'tmp.jpg');
        $content = self::compress($file, $quality, $max_width, $max_height);
        Storage::delete($tempFile);
        return self::save($content, $dest, $disk);
    }

    /**
     * @param UploadedFile $file
     * @param int $quality
     * @param int $max_width
     * @param int $max_height
     * @return string
     */
    public static function compress(UploadedFile $file, int $quality = 75, int $max_width = 900, int $max_height = 900)
    {
        $source = $file->getRealPath();
        $info = getimagesize($source);
        $image = null;
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);

        $width = $info[0];
        $height = $info[1];

        $x_ratio = $max_width / $width;
        $y_ratio = $max_height / $height;

        if (($width <= $max_width) && ($height <= $max_height)) {
            $tn_width = $width;
            $tn_height = $height;
        } elseif (($x_ratio * $height) < $max_height) {
            $tn_height = ceil($x_ratio * $height);
            $tn_width = $max_width;
        } else {
            $tn_width = ceil($y_ratio * $width);
            $tn_height = $max_height;
        }
        $tmp = imagecreatetruecolor($tn_width, $tn_height);
        imagecopyresampled($tmp, $image, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
        ob_start();
        imagejpeg($tmp, null, $quality);
        imagedestroy($tmp);
        $content = ob_get_contents();
        ob_end_clean();
        $data = sprintf('data:image/jpeg;base64,%s', base64_encode($content));
        return $data;
    }


    /**
     * @param string $base64string
     * @param string|null $path
     * @param string|null $filename
     * @param string $disk
     * @return string
     * @throws \Exception
     */
    public static function save($base64string, $path = null, $disk = 'system_file_storage')
    {
        $status = preg_match('/^data:image\/(\w+);base64,(.+)/', $base64string, $matches);
        if ($status) {
            $data = $matches[2];
            $temp = self::createImageTempFile(base64_decode($data));
            $uploadFile = new UploadedFile($temp, 'temp.jpeg');
            $savedFilePath = FileStorageUtil::putFile($path ?? "", $uploadFile);
            unlink($temp);
            if ($savedFilePath) {
                return $savedFilePath;
            } else {
                throw new \Exception('Can\'t save file');
            }
        } else {
            throw new \Exception('Invalid String');
        }
    }

    public static function getAttributes(UploadedFile $file)
    {
        $exif = exif_read_data($file->getRealPath(), null, true);
        foreach ($exif as $key => $section) {
            foreach ($section as $name => $val) {
                echo "$key.$name: $val<br />\n";
            }
        }
    }


    /**
     * @param string $url
     * @param int $quality
     * @param string $location
     * @return string
     * @throws \Exception
     */
    public static function downloadFromFBUrl(string $url, int $quality = 90, string $location = 'images')
    {
        $temp = self::createImageTempFile(file_get_contents($url));
        $img = ImageUtil::compress(new UploadedFile($temp, 'temp.jpeg'), $quality);
        $savedFile = ImageUtil::save($img, $location);
        unlink($temp);
        return $savedFile;
    }

    public static function createImageTempFile($string, $ext = 'jpeg')
    {
        $temp = storage_path('temp_' . Str::random(50));
        file_put_contents($temp, $string);
        return $temp;
    }

}