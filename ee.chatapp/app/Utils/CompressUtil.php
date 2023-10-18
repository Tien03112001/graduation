<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 8/5/2022
 * Time: 00:01
 */

namespace App\Utils;


use Illuminate\Support\Str;

class CompressUtil
{

    public static function run($rootPath, $filename)
    {
        $zip = new \ZipArchive();
        $zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        /** @var \SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = Str::replace('\\', '/', $file->getRealPath());
                echo $filePath . PHP_EOL;
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
    }

}