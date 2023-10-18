<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 9/15/2021
 * Time: 2:14 PM
 */

namespace App\Utils\Logistics;

use App\Models\AppSetting;
use App\Models\SaleOrder;
use App\Models\SaleOrderShipping;
use App\Models\ShippingService;
use App\Models\ShippingStore;
use App\Models\ShippingUnit;
use App\Utils\CacheUtil;

abstract class GiaoHangAbstractUtil
{
    protected $name;
    protected $endpoint;
    protected $token;
    protected $username;
    protected $password;
    protected $config;
    protected $shippingUnit;

    public function __construct($input)
    {
        if (is_string($input)) {
            $unit = ShippingUnit::where('name', $input)->first();
            $this->loadConfig($unit);
        }
        if (is_numeric($input)) {
            $unit = ShippingUnit::find($input);
            $this->loadConfig($unit);
        }
        if ($input instanceof \App\Models\ShippingUnit) {
            $this->loadConfig($input);
        }

    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param mixed $input
     */
    public function setInput($input): void
    {
        $this->input = $input;
    }


    public function loadConfig(ShippingUnit $unit)
    {
        $this->shippingUnit = $unit;
        $this->name = $unit->name;
        $this->endpoint = $unit->endpoint;
        $this->token = $unit->token;
        $this->username = $unit->username;
        $this->password = $unit->password;
        $this->config = $unit->config;
    }


    abstract public function getServices();

    abstract public function getStores();

    abstract public function getProvinces();

    abstract public function getDistricts($provinceId);

    abstract public function getWards($districtId);

    abstract public function getOrder(SaleOrderShipping $order);

    abstract public function createOrder(SaleOrder $order, ShippingStore $store, ShippingService $service = null);

    abstract public function updateOrder(string $code, SaleOrder $order, ShippingService $service = null);

    abstract public function cancelOrder(SaleOrderShipping $order);

    abstract public function returnOrder(SaleOrderShipping $order);

    abstract public function storingOrder(SaleOrderShipping $order);

    public function printOrders($orders)
    {
        $logo = AppSetting::whereName('store_logo')->first();
        $name = AppSetting::whereName('store_name')->first();
        $address = AppSetting::whereName('store_address')->first();
        $phone = AppSetting::whereName('store_phone')->first();
        $barcodeType = 'CODE128';
        return view('giaohang.default', compact('orders', 'barcodeType', 'logo', 'name', 'address', 'phone'))->render();
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param mixed $endpoint
     */
    public function setEndpoint($endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config): void
    {
        $this->config = $config;
    }

    /**
     * @return ShippingUnit
     */
    public function getShippingUnit(): ShippingUnit
    {
        return $this->shippingUnit;
    }

    /**
     * @param mixed $shippingUnit
     */
    public function setShippingUnit($shippingUnit): void
    {
        $this->shippingUnit = $shippingUnit;
    }

    public function returnMethod($curl)
    {
        if ($curl->status == 200) {
            if ($curl->content->status == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến ' . $this->name);
        }
    }

}