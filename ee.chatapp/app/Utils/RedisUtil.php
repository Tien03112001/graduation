<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/26/2022
 * Time: 20:54
 */

namespace App\Utils;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class RedisUtil
{

    /**
     * @param $connection
     * @return \Illuminate\Redis\Connections\Connection
     */
    private static function getRedisInstance($connection)
    {
        return empty($connection) ? Redis::connection() : Redis::connection($connection);
    }

    /**
     * @param string $key
     * @param null|string $connection
     * @return string
     */
    public static function get($key, $connection = null)
    {
        return self::getRedisInstance($connection)->get($key);
    }

    /**
     * @param string $key
     * @param null|string $connection
     * @return array
     */
    public static function getArray($key, $connection = null)
    {
        return json_decode(self::get($key, $connection), true);
    }

    /**
     * @param string $key
     * @param string $value
     * @param null|string $connection
     * @return mixed
     */
    public static function set($key, $value, $connection = null)
    {
        Log::info("set: to $connection key $key value $key");
        return self::getRedisInstance($connection)->set($key, $value);
    }

    /**
     * @param $key
     * @param array $value
     * @param null|string $connection
     * @return mixed
     */
    public static function setArray($key, $value, $connection = null)
    {
        return self::set($key, json_encode($value), $connection);
    }

    /**
     * @param string $command
     * @param array $args
     * @param null|string $connection
     * @return mixed
     */
    public static function command($command, $args, $connection = null)
    {
        return self::getRedisInstance($connection)->command($command, ...$args);
    }

    /**
     * @param array $values
     * @param null|string $connection
     * @return mixed
     */
    public static function pipeline($values, $connection = null)
    {
        self::getRedisInstance($connection)->pipeline(function ($pipe) use ($values) {
            foreach ($values as $key => $value) {
                echo $key;
                $pipe->set($key, json_encode($value));
            }
        });
    }

    /**
     * @param string $data
     * @param string $channel
     * @param null|string $connection
     * @return int
     */
    public static function publish($channel, $data, $connection = null)
    {
        $count = self::getRedisInstance($connection)->publish($channel, $data);
        if ($count > 0) {
            Log::info("publish: to $connection channel $channel result $count");
        } else {
            Log::error("publish to $connection: channel $channel result $count");
        }
        return $count;
    }

    /**
     * @param array $data
     * @param string $channel
     * @param null|string $connection
     * @return int
     */
    public static function publishArray($channel, array $data, $connection = null)
    {
        return self::publish($channel, json_encode($data), $connection);
    }

    /**
     * @param \Closure $closure
     * @param string $channel
     * @param null|string $connection
     */
    public static function subscribe($channel, \Closure $closure, $connection = null)
    {
        Log::info("subscribing: from $connection channel $channel");
        self::getRedisInstance($connection)->subscribe($channel, $closure);
    }

    /**
     * @param \Closure $closure
     * @param array $channels
     * @param null|string $connection
     */
    public static function psubscribe(array $channels, \Closure $closure, $connection = null)
    {
        Log::info("subscribing: from $connection channel: " . join(',', $channels));
        self::getRedisInstance($connection)->psubscribe($channels, $closure);
    }
}