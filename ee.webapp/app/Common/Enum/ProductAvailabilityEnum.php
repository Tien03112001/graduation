<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/26/2022
 * Time: 19:02
 */

namespace App\Common\Enum;


class ProductAvailabilityEnum
{
    const IN_STOCK = 'Còn hàng';
    const OUT_OF_STOCK = 'Hết hàng';
    const ONLINE_ONLY = 'Chỉ online';
    const IN_STORE_ONLY = 'Chỉ cửa hàng';
    const PRE_ORDER = 'Đặt trước';
    const PRE_SALE = 'Bán trước';
    const LIMITED = 'Giới hạn';
    const SOLD_OUT = 'Bán hết';
    const DISCONTINUED = 'Ngừng kinh doanh';
}