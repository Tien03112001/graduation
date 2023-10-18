<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 11/4/2022
 * Time: 13:37
 */

namespace App\Utils;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CommandUtil
{
    public static function pullCode()
    {
        return ProcessUtil::shellSync(sprintf('%s pull', config('command.git.path')), base_path());
    }

    public static function runCommand($command)
    {
        Artisan::call($command);
        return Artisan::output();
    }

    public static function cacheClear()
    {
        Artisan::call('optimize:clear');
        $message = Artisan::output();
        $files = File::allFiles(base_path('resources/views/cache'));
        foreach ($files as $file) {
            if (Str::endsWith($file->getFilename(), '.blade.php')) {
                unlink($file->getRealPath());
            }
        }
        return $message;
    }

    public static function composerUpdate()
    {
        return ProcessUtil::shellSync(sprintf('%s update', config('command.composer.path')), base_path());
    }

    public static function migrate($options = [])
    {
        Artisan::call('migrate', array_merge(['--force' => true], $options));
        return Artisan::output();
    }

    public static function dbSeed($options = [])
    {
        Artisan::call('db:seed', array_merge(['--force' => true], $options));
        return Artisan::output();
    }
}