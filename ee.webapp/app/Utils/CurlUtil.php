<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5/23/2018
 * Time: 9:08 AM
 */

namespace App\Utils;

use Ixudra\Curl\Facades\Curl;

class CurlUtil
{
    public static function curlGet($path, $data, $headers = [], $options = [])
    {
        $request = Curl::to($path)
            ->withData($data)
            ->withContentType('application/json; charset=utf-8');

        foreach ($options as $key => $value) {
            $request = $request->withOption($key, $value);
        }

        $response = $request
            ->withHeaders($headers)
            ->withTimeout(300)
            ->asJson()
            ->returnResponseObject()
            ->get();
        return $response;
    }

    public static function curlPost($path, $body, $headers = [], $options = [])
    {
        $request = Curl::to($path)
            ->withData($body)
            ->withContentType('application/json; charset=utf-8');

        foreach ($options as $key => $value) {
            $request = $request->withOption($key, $value);
        }
        $response = $request
            ->withHeaders($headers)
            ->asJson()
            ->returnResponseObject()
            ->withTimeout(300)
            ->post();
        return $response;
    }

    public static function curlPut($path, $body, $headers = [], $options = [])
    {
        $request = Curl::to($path)
            ->withData($body)
            ->withContentType('application/json; charset=utf-8');

        foreach ($options as $key => $value) {
            $request = $request->withOption($key, $value);
        }
        $response = $request
            ->withHeaders($headers)
            ->asJson()
            ->returnResponseObject()
            ->withTimeout(300)
            ->put();
        return $response;
    }

    public static function curlDelete($path, $body, $headers = [], $options = [])
    {
        $request = Curl::to($path)
            ->withData($body)
            ->withContentType('application/json; charset=utf-8');

        foreach ($options as $key => $value) {
            $request = $request->withOption($key, $value);
        }
        $response = $request
            ->withHeaders($headers)
            ->asJson()
            ->returnResponseObject()
            ->withTimeout(300)
            ->delete();
        return $response;
    }
}
