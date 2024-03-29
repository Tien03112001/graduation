<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 3/14/2021
 * Time: 12:36 AM
 */

namespace App\Utils\Logistics;

use App\Models\ProductVariant;
use App\Models\SaleOrder;
use App\Models\SaleOrderShipping;
use App\Models\ShippingService;
use App\Models\ShippingStore;
use App\Models\ShippingWard;
use App\Models\ShippingWardByUnit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Ixudra\Curl\Facades\Curl;

class GiaoHangNhanhUtil extends GiaoHangAbstractUtil
{
    public function __construct($input = null)
    {
        if ($input == null) {
            $input = 'Giao Hàng Nhanh';
        }
        parent::__construct($input);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getProvinces()
    {
        $curl = Curl::to($this->endpoint . '/master-data/province')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }
    }

    public function getDistricts($provinceId)
    {
        $curl = Curl::to($this->endpoint . '/master-data/district')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token
            ])
            ->withData([
                'province_id' => $provinceId
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }
    }

    public function getWards($districtId)
    {
        $curl = Curl::to($this->endpoint . '/master-data/ward')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token
            ])
            ->withData([
                'district_id' => $districtId
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }

    }

    public function getOrder(SaleOrderShipping $order)
    {
        $curl = Curl::to($this->endpoint . '/v2/shipping-order/detail')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token
            ])
            ->withData([
                'order_code' => $order->code
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }
    }

    public function createOrder(SaleOrder $order, ShippingStore $store, ShippingService $service = null)
    {
        $toWard = ShippingWardByUnit::where('ward_name', 'like', $order->ward)
        ->where('district_name', 'like', $order->district)
        ->where('province_name', 'like', $order->province)
        ->where('unit_id', $this->shippingUnit->id)
        ->first();
        if (empty($toWard)) {
            throw new \Exception(sprintf('Địa chỉ %s không đúng', $order->customer_address));
        }
        $data = [
            'from_name' => $store->name,
            'to_name' => $order->customer_name,
            'to_phone' => $order->customer_phone,
            'to_address' => $order->customer_address,
            'to_ward_name' => $toWard->partner_name,
            'to_district_name' => $toWard->partner_district_name,
            'to_province_name' => $toWard->partner_province_name,
            'return_name' => $store->name,
            'return_phone' => $store->data['phone'],
            'return_address' => $store->data['address'],
            'return_ward_code' => $store->data['ward_code'],
            'return_district_id' => $store->data['district_id'],
            'client_order_code' => $order->code,
            'cod_amount' => $order->cod_fee,
            'service_id' => (int)$service->code,
            'service_type_id' => $service->data['service_type_id'],
            'payment_type_id' => (int)$service->data['payment_type_id'] ?? 1,
            'note' =>  $order->customer_request,
            'required_note' => $service->data['default_note'] ?? '',
            'length' => $service->data['default_length'] ?? 10,
            // 'weight' => '',
            'width' => $service->data['default_width'] ?? 10,
            'height' => $service->data['default_height'] ?? 10,
            'items' => []
        ];

        $total_weight = 0;
        foreach ($order->details as $d) {
            $variant = ProductVariant::whereProductId($d->product_id)->whereId($d->variant_id)->first();
            $total_weight +=  $variant->weight * $d->quantity * 1000;
            array_push($data['items'], [
                'name' => $d->product->name,
                'code' => $d->detail_code,
                'quantity' => $d->quantity,
            ]);
        }
        $data['weight']=$total_weight;

        $curl = Curl::to($this->endpoint . '/v2/shipping-order/create')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
                'ShopId' => $store->partner_id
            ])
            ->withData($data)
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                $responseData = $curl->content->data;
                $data = new \stdClass();
                $data->order_code = $responseData->order_code;
                $data->total_fee = $responseData->total_fee;
                $data->expected_delivery_time = Carbon::parse($responseData->expected_delivery_time)->toDateTimeString();
                return $data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception($curl->content->message);
        }
    }

    public function updateOrder(string $code, SaleOrder $order, ShippingService $service = null)
    {
        $toWard = ShippingWard::where('name', 'like', $order->ward)
            ->whereHas('district', function (Builder $q) use ($order) {
                $q->where('name', 'like', $order->district)
                    ->whereHas('province', function (Builder $q2) use ($order) {
                        $q2->where('name', 'like', $order->province);
                    });
            })->first();
        if (empty($toWard)) {
            throw new \Exception(sprintf('Địa chỉ %s không đúng', $order->customer_address));
        }
        $data = [
            'to_name' => $order->customer_name,
            'to_phone' => $order->customer_phone,
            'to_address' => $order->customer_address,
            'to_ward_code' => $toWard->code,
            'to_district_id' => $toWard->district_id,
            'client_order_code' => $order->code,
            'cod_amount' => $order->cod_fee,
            'content' => $order->customer_request,
            'weight' => $order->details->map(function ($d, $key) {
                return $d->product->weight * $d->quantity * 1000;
            })->sum(),
            'length' => $service->data['default_length'] ?? 10,
            'width' => $service->data['default_width'] ?? 10,
            'height' => $service->data['default_height'] ?? 10,
            'service_type_id' => $service->data['service_type_id'] ?? 2,
            'payment_type_id' => $service->data['payment_type_id'] ?? 1,
            'note' => $order->customer_request,
            'required_note' => $service->data['default_note'] ?? '',
            'items' => []
        ];

        foreach ($order->details as $d) {
            array_push($data['items'], [
                'name' => $d->product->name,
                'code' => $d->detail_code,
                'quantity' => $d->quantity,
            ]);
        }

        $curl = Curl::to($this->endpoint . '/v2/shipping-order/update')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
                'ShopId' => $this->config['SHOP_ID']
            ])
            ->withData(
                array_merge(['order_code' => $code], $data)
            )
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }
    }

    public function updateCoD(string $code, int $cod_fee)
    {
        $curl = Curl::to($this->endpoint . '/v2/shipping-order/updateCOD')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
                'ShopId' => $this->config['SHOP_ID']
            ])
            ->withData(
                [
                    'order_code' => $code,
                    'cod_amount' => $cod_fee
                ]
            )
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }
    }

    public function cancelOrder(SaleOrderShipping $order)
    {
        $curl = Curl::to($this->endpoint . '/v2/switch-status/cancel')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
                'ShopId' => $order->store->partner_id
            ])
            ->withData(
                [
                    'order_codes' => [$order->code],
                ]
            )
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data[0]->result;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }
    }

    public function returnOrder(SaleOrderShipping $order)
    {
        $curl = Curl::to($this->endpoint . '/v2/switch-status/return')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
                'ShopId' => $order->store->partner_id
            ])
            ->withData(
                [
                    'order_codes' => [$order->code],
                ]
            )
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data[0]->result;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }
    }

    public function storingOrder(SaleOrderShipping $order)
    {
        $curl = Curl::to($this->endpoint . '/v2/switch-status/storing')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
                'ShopId' => $order->store->partner_id
            ])
            ->withData(
                [
                    'order_codes' => [$order->code],
                ]
            )
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data[0]->result;
            }
        }
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }
    }


    public function authenticate($account)
    {
        // TODO: Implement authenticate() method.
    }

    public function getServices()
    {
        $curl = json_decode('{"code": 200,"message": "Success","data":[
    {"service_id":53319,"short_name":"Nhanh","service_type_id":1},
    {"service_id":53320,"short_name":"Chuẩn","service_type_id":2},
    {"service_id":53321,"short_name":"Tiết kiệm","service_type_id":3}
    ]}', false);
        if ($curl->code == 200) {
            $services = [];
            $respData = $curl->data;

            foreach ($respData as $index => $d) {
                $data = (array)$d;
                $data['default_length'] = 10;
                $data['default_width'] = 10;
                $data['default_height'] = 10;
                $data['payment_type_id'] = 1;
                $data['default_note'] = 'KHONGCHOXEMHANG';
                $isOften = $index == 0 ? 1 : 0;
                array_push($services, [
                    'unit_id' => $this->getShippingUnit()->id,
                    'name' => $data['short_name'],
                    'code' => $data['service_id'],
                    'data' => $data,
                    'is_often' => $isOften
                ]);
            }
            return $services;
        } else {
            throw new \Exception($curl->content->message);
        }
    }

    public function getStores()
    {
        $curl = Curl::to($this->endpoint . '/v2/shop/all')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
            ])
            ->withData(
                [
                    'offset' => 0,
                    'limit' => 200,
                    'clientphone' => ''
                ]
            )
            ->asJson()
            ->returnResponseObject()
            ->get();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                $respData = $curl->content->data->shops;
                $shops = [];
                foreach ($respData as $index => $rd) {
                    $data = (array)$rd;
                    $isOften = $index == 0 ? 1 : 0;
                    array_push($shops, [
                        'unit_id' => $this->getShippingUnit()->id,
                        'name' => $rd->name,
                        'partner_id' => $rd->_id,
                        'data' => $data,
                        'is_often' => $isOften
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
}
