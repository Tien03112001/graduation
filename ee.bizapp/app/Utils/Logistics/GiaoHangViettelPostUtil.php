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
use App\Models\ShippingWardByUnit;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class GiaoHangViettelPostUtil extends GiaoHangAbstractUtil
{

    public function __construct($input = null)
    {
        if ($input == null) {
            $input = 'Viettel Post';
        }
        parent::__construct($input);
    }

    public static function authenticate($account)
    {
        $url = 'https://partner.viettelpost.vn/v2/user/Login';
        $curl = Curl::to($url)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withData([
                'USERNAME' => $account['username'],
                'PASSWORD' => $account['password'],
            ])
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {
            if ($curl->content->status == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến ViettelPost');
        }
    }

    public function getServices()
    {
        $url = $this->endpoint . "/categories/listService";
        $curl = Curl::to($url)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withData([
                'TYPE' => 2
            ])
            ->asJson()
            ->returnResponseObject()
            ->post();

        if ($curl->status == 200) {
            if ($curl->content->status == 200) {
                $respData = $curl->content->data;
                $services = [];
                foreach ($respData as $index => $rd) {
                    $data = (array)$rd;
                    $data['default_note'] = 'Không giao được liên lạc lại shop, không tự ý hoàn đơn. Cho khách xem hàng, k cho thử.';
                    array_push($services, [
                        'unit_id' => $this->getShippingUnit()->id,
                        'name' => $rd->SERVICE_NAME,
                        'code' => $rd->SERVICE_CODE,
                        'data' => $data,
                        'is_often' => $rd->SERVICE_CODE == 'LCOD' ? 1 : 0
                    ]);
                }
                return $services;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến ' . $this->name);
        }

    }

    public function getStores()
    {
        $url = $this->endpoint . "/user/listInventory";
        $curl = Curl::to($url)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();

        if ($curl->status == 200) {
            if ($curl->content->status == 200) {
                $respData = $curl->content->data;
                $shops = [];
                foreach ($respData as $index => $rd) {
                    $data = (array)$rd;
                    array_push($shops, [
                        'unit_id' => $this->getShippingUnit()->id,
                        'name' => $rd->name,
                        'partner_id' => $rd->groupaddressId,
                        'data' => $data,
                        'is_often' => $index == 0 ? 1 : 0
                    ]);
                }
                return $shops;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến ' . $this->name);
        }
    }

    public function getProvinces()
    {
        $url = $this->endpoint . "/categories/listProvinceById";
        $curl = Curl::to($url)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withData([
                'provinceId' => -1,
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();

        return $this->returnMethod($curl);
    }


    public function getDistricts($provinceId)
    {
        // TODO: Implement getDistricts() method.
        $url = $this->endpoint . "/categories/listDistrict";
        $curl = Curl::to($url)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withData([
                'provinceId' => $provinceId,
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();

        return $this->returnMethod($curl);
    }

    public function getWards($districtId)
    {
        // TODO: Implement getDistricts() method.
        $url = $this->endpoint . "/categories/listWards";
        $curl = Curl::to($url)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withData([
                'districtId' => $districtId,
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();

        return $this->returnMethod($curl);
    }

    public function getOrder(SaleOrderShipping $order)
    {
        return $order->toArray();
    }

    public function createOrder(SaleOrder $order, ShippingStore $store, ShippingService $service = null)
    {
        if ($service == null) {
            $service = ShippingService::where('is_often', true)->first();
        }

        if (empty($service)) {
            throw new \Exception('Viettel Post chưa được cấu hình dịch vụ');
        }

        $ward = ShippingWardByUnit::where('ward_name', $order->ward)
            ->where('district_name', $order->district)
            ->where('province_name', $order->province)
            ->where('unit_id', $this->shippingUnit->id)
            ->first();

        if ($ward->partner_id == 0) {
            throw new \Exception('Viettel Post không hỗ trợ giao địa điểm này');
        }

        $data = [
            "GROUPADDRESS_ID" => $store->partner_id,
            "CUS_ID" => $store->data['cusId'],
            "DELIVERY_DATE" => now()->format('d/m/Y H:i:s'),
            "SENDER_FULLNAME" => $store->name,
            "SENDER_ADDRESS" => $store->data['address'],
            "SENDER_PHONE" => $store->data['phone'],
            "SENDER_EMAIL" => $store->data['email'] ?? "",
            "SENDER_WARD" => $store->data['wardsId'],
            "SENDER_DISTRICT" => $store->data['districtId'],
            "SENDER_PROVINCE" => $store->data['provinceId'],
            "SENDER_LATITUDE" => 0,
            "SENDER_LONGITUDE" => 0,
            "RECEIVER_FULLNAME" => $order->customer_name,
            "RECEIVER_ADDRESS" => $order->customer_address,
            "RECEIVER_PHONE" => $order->customer_phone,
            "RECEIVER_EMAIL" => "",
            "RECEIVER_WARD" => $ward->partner_id,
            "RECEIVER_DISTRICT" => $ward->partner_district_id,
            "RECEIVER_PROVINCE" => $ward->partner_province_id,
            "RECEIVER_LATITUDE" => 0,
            "RECEIVER_LONGITUDE" => 0,
            "PRODUCT_NAME" => $order->details->map(function ($d, $key) {
                return $d->quantity . " " . $d->product->packing . " " . $d->detail_code;
            })->join(', '),
            "PRODUCT_DESCRIPTION" => "",
            "PRODUCT_QUANTITY" => $order->details->map(function ($d, $key) {
                return $d->quantity;
            })->sum(),
            "PRODUCT_PRICE" => $order->amount,
            "PRODUCT_WEIGHT" => $order->details->map(function ($d, $key) {
                return $d->product->weight * $d->quantity;
            })->sum(),
            "PRODUCT_LENGTH" => 0,
            "PRODUCT_WIDTH" => 0,
            "PRODUCT_HEIGHT" => 0,
            "PRODUCT_TYPE" => "HH",
            "ORDER_PAYMENT" => 3,
            "ORDER_SERVICE" => $service->code,
            "ORDER_SERVICE_ADD" => "",
            "ORDER_VOUCHER" => "",
            "ORDER_NOTE" => $service->data['default_note'] ?? "",
            "MONEY_COLLECTION" => $order->cod_fee,
            "MONEY_TOTALFEE" => 0,
            "MONEY_FEECOD" => $order->cod_fee,
            "MONEY_FEEVAS" => 0,
            "MONEY_FEEINSURRANCE" => 0,
            "MONEY_FEE" => 0,
            "MONEY_FEEOTHER" => 0,
            "MONEY_TOTALVAT" => 0,
            "MONEY_TOTAL" => $order->total_amount,
            "LIST_ITEM" => $order->details->map(function ($d, $key) {
                return [
                    "PRODUCT_NAME" => $d->product->name,
                    "PRODUCT_PRICE" => $d->unit_price,
                    "PRODUCT_WEIGHT" => $d->product->weight,
                    "PRODUCT_QUANTITY" => $d->quantity,

                ];
            })->all()
        ];

        $url = $this->endpoint . "/order/createOrder";
        Log::info($url);
        $curl = Curl::to($url)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token
            ])
            ->withData($data)
            ->asJson()
            ->returnResponseObject()
            ->post();

        Log::info(print_r($curl, true));

        if ($curl->status == 200) {
            if ($curl->content->status == 200) {
                $responseData = $curl->content->data;
                $data = new \stdClass();
                $data->order_code = $responseData->ORDER_NUMBER;
                $data->total_fee = $responseData->MONEY_TOTAL;
                $data->expected_delivery_time = now()->addDays(4)->toDateTimeString();
                return $data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến ' . $this->name);
        }
    }

    public function updateOrder(string $code, SaleOrder $order, ShippingService $service = null)
    {
        throw new \Exception('Viettel Post chưa hỗ trợ');
    }

    public function cancelOrder(SaleOrderShipping $order)
    {
        $data = [
            'TYPE' => 4,
            'ORDER_NUMBER' => $order->code,
            'NOTE' => ""
        ];
        $url = $this->endpoint . "/order/UpdateOrder";
        $curl = Curl::to($url)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token
            ])
            ->withData($data)
            ->asJson()
            ->returnResponseObject()
            ->post();

        return $this->returnMethod($curl);
    }

    public function returnOrder(SaleOrderShipping $order)
    {
        $data = [
            'TYPE' => 2,
            'ORDER_NUMBER' => $order->code,
            'NOTE' => ""
        ];
        $url = $this->endpoint . "/order/UpdateOrder";
        $curl = Curl::to($url)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token
            ])
            ->withData($data)
            ->asJson()
            ->returnResponseObject()
            ->post();

        return $this->returnMethod($curl);
    }

    public function storingOrder(SaleOrderShipping $order)
    {
        // TODO: Implement storingOrder() method.
    }

}