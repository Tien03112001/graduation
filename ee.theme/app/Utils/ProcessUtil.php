<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/26/2022
 * Time: 18:27
 */

namespace App\Utils;


use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ProcessUtil
{
    /**
     * @param array|string $cmd
     * @param null $workingDir
     * @param null $env
     * @param null $input
     * @param int $timeout
     * @return string
     */
    public static function cmdSync($cmd, $workingDir = null, $env = null, $input = null, $timeout = 300)
    {
        $arr = [];
        if (is_string($cmd)) {
            $arr = explode(' ', $cmd);
        } elseif (is_array($cmd)) {
            $arr = $cmd;
        }
        try {
            Log::info('cmd: ' . join(' ', $arr));
            $process = new Process($arr, $workingDir, $env, $input, $timeout);
            $process->mustRun();
            Log::info('output: ' . $process->getOutput());
            return $process->getOutput();
        } catch (ProcessFailedException $exception) {
            Log::error($exception);
            throw $exception;
        }
    }

    public static function shell($cmd, $workingDir = null, $env = null, $input = null, $timeout = 300)
    {
        try {
            Log::info('cmd: ' . $cmd);
            $process = Process::fromShellCommandline($cmd, $workingDir, $env, $input, $timeout);
            $process->start();
            Log::info($process->getPid());
        } catch (ProcessFailedException $exception) {
            Log::error($exception);
            throw $exception;
        }
    }

    /**
     * @param $cmd
     * @param null $workingDir
     * @param null $env
     * @param null $input
     * @param int $timeout
     * @return string
     */
    public static function shellSync($cmd, $workingDir = null, $env = null, $input = null, $timeout = 300)
    {
        try {
            Log::info('cmd: ' . $cmd);
            $process = Process::fromShellCommandline($cmd, $workingDir, $env, $input, $timeout);
            $process->mustRun();
            Log::info('output: ' . $process->getOutput());
            return $process->getOutput();
        } catch (ProcessFailedException $exception) {
            Log::error($exception);
            throw $exception;
        }
    }
}