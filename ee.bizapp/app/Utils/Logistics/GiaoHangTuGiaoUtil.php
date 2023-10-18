<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 9/15/2021
 * Time: 2:20 PM
 */

namespace App\Utils\Logistics;


use App\Models\SaleOrder;
use App\Models\SaleOrderShipping;
use App\Models\ShippingService;
use App\Models\ShippingStore;

class GiaoHangTuGiaoUtil extends GiaoHangAbstractUtil
{

    public function __construct($input = null)
    {
        if ($input == null) {
            $input = 'Tự giao';
        }
        parent::__construct($input);
    }

    public function authenticate($account)
    {
        throw new \Exception('Method chưa hỗ trợ');
    }

    public function getServices()
    {
        $services = [];

        array_push($services, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'Giao nhanh',
            'code' => '',
            'data' => [],
            'is_often' => 1
        ]);

        return $services;
    }

    public function getStores()
    {
        $shops = [];
        array_push($shops, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'Thời trang Spexi',
            'partner_id' => 1,
            'data' => [],
            'is_often' => 1
        ]);
        return $shops;
    }

    public function getProvinces()
    {
        throw new \Exception('Method chưa hỗ trợ');
    }


    public function getDistricts($provinceId)
    {
        throw new \Exception('Method chưa hỗ trợ');
    }

    public function getWards($districtId)
    {
        throw new \Exception('Method chưa hỗ trợ');
    }

    public function getOrder(SaleOrderShipping $order)
    {
        return $order;
    }

    public function createOrder(SaleOrder $order, ShippingStore $store, ShippingService $service = null)
    {
        $data = new \stdClass();
        $data->order_code = "TUGIAO_" . $order->id;
        $data->total_fee = 30000;
        $data->expected_delivery_time = now()->addDays(2)->toDateTimeString();
        return $data;
    }

    public function updateOrder(string $code, SaleOrder $order, ShippingService $service = null)
    {
        throw new \Exception('Method chưa hỗ trợ');
    }

    public function cancelOrder(SaleOrderShipping $order)
    {
    }

    public function returnOrder(SaleOrderShipping $order)
    {
        throw new \Exception('Method chưa hỗ trợ');
    }

    public function storingOrder(SaleOrderShipping $order)
    {
        throw new \Exception('Method chưa hỗ trợ');
    }

}