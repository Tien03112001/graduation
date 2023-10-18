<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 9/15/2021
 * Time: 2:14 PM
 */

namespace App\Utils;


use App\Models\SaleOrder;
use App\Models\SaleOrderShipping;
use App\Models\ShippingDistrict;
use App\Models\ShippingProvince;
use App\Models\ShippingService;
use App\Models\ShippingStore;
use App\Models\ShippingUnit;
use App\Models\ShippingWard;
use App\Utils\Logistics\GiaoHangAbstractUtil;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class GiaoHangUtil
{
    /** @var GiaoHangAbstractUtil */
    protected $util;


    /**
     * @param ShippingUnit|SaleOrderShipping|SaleOrder|null $input
     */
    public function __construct($input = null)
    {
        if ($input instanceof SaleOrderShipping) {
            $input = $input->unit;
        }
        if ($input instanceof SaleOrder) {
            $shipping = $input->shipping;
            if (isset($shipping)) {
                $input = $shipping->unit;
            }
        }
        if (empty($input)) {
            $input = ShippingUnit::whereName("Tự giao")->first();
        }
        $this->util = new $input->class_name($input);
    }

    public static function checkAddress(SaleOrder $order)
    {
        $province = ShippingProvince::where('name', $order->province)->first();
        if (empty($province)) {
            return false;
        }
        $district = ShippingDistrict::where('name', $order->district)->first();
        if (empty($district)) {
            return false;
        }
        $ward = ShippingWard::where('name', $order->ward)->first();
        if (empty($ward)) {
            return false;
        }
        return true;
    }

    public function getServices()
    {
        return $this->util->getServices();
    }

    public function getStores()
    {
        return $this->util->getStores();
    }

    public function getProvinces()
    {
        return $this->util->getProvinces();
    }

    public function getDistricts($provinceId)
    {
        return $this->util->getDistricts($provinceId);
    }

    public function getWards($districtId)
    {
        return $this->util->getWards($districtId);
    }

    public function getOrder(SaleOrderShipping $order)
    {
        return $this->util->getOrder($order);
    }

    public function createOrder(SaleOrder $order, ShippingStore $store, ShippingService $service = null)
    {
        return $this->util->createOrder($order, $store, $service);
    }

    public function updateOrder(string $code, SaleOrder $order, ShippingService $service = null)
    {
        return $this->util->updateOrder($code, $order, $service);
    }

    public function cancelOrder(SaleOrderShipping $order)
    {
        return $this->util->cancelOrder($order);
    }

    public function returnOrder(SaleOrderShipping $order)
    {
        return $this->util->returnOrder($order);
    }

    public function storingOrder(SaleOrderShipping $order)
    {
        return $this->util->storingOrder($order);
    }

    public function printOrders($orders)
    {
        $src = $this->util->printOrders($orders);
        $file_name = sprintf('phieu_gui_hang_%d.html', now()->getTimestamp());
        file_put_contents(storage_path('app/public/' . $file_name), $src);
        return Storage::disk('public')->url($file_name);
    }

    /**
     * @return GiaoHangAbstractUtil
     */
    public function getUtil()
    {
        return $this->util;
    }


}
