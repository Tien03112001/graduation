<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 10/28/2022
 * Time: 19:07
 */

namespace App\Common\Config;


use App\Common\SingletonPattern;

abstract class AbstractPayConfig extends SingletonPattern
{
    protected $method;

    /**
     * AbstractPayConfig constructor.
     * @param $paymentMethod
     * @param array $attributes
     * @param array $config
     * @throws \Exception
     */
    public function __construct($paymentMethod, $attributes = [], array $config)
    {
        $this->method = $paymentMethod;
        if (empty($config)) {
            throw new \Exception("Chưa cấu hình $paymentMethod");
        }
        foreach ($attributes as $attribute) {
            $this->check($attribute, $config);
        }
    }

    /**
     * @param $key
     * @param $config
     * @throws \Exception
     */
    protected function check($key, $config)
    {
        if (!array_key_exists($key, $config)) {
            throw new \Exception("Cấu hình $this->method chưa đặt giá trị $key");
        } else {
            $this->{$key} = $config[$key];
        }
    }
}