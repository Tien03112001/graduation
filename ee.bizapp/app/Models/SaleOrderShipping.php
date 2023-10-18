<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrderShipping extends Model
{
    protected $guarded = [];


    public function order()
    {
        return $this->belongsTo(SaleOrder::class, 'order_id');
    }

    public function unit()
    {
        return $this->belongsTo(ShippingUnit::class, 'deliver_id');
    }

    public function store()
    {
        return $this->belongsTo(ShippingStore::class, 'store_id');
    }

    public function service()
    {
        return $this->belongsTo(ShippingService::class, 'service_id');
    }

    public static function setDone(SaleOrderShipping $order)
    {
        $order->status = 'Đã đối soát';
        $order->status_id = 6;
    }

    public static function setCanceled(SaleOrderShipping $order)
    {
        $order->status = 'Hủy đơn hàng';
        $order->status_id = -1;
    }

    public static function setReturn(SaleOrderShipping $order)
    {
        $order->status = 'Hoàn hàng';
        $order->status_id = 20;
    }

    public static function setReceivedRefund(SaleOrderShipping $order)
    {
        $order->status = 'Đã trả hàng';
        $order->status_id = 21;
    }
}
