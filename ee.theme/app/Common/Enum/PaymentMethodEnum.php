<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/26/2022
 * Time: 19:02
 */

namespace App\Common\Enum;


class PaymentMethodEnum
{
    const COD = 'Thanh toán khi nhận hàng';
    const MANUAL = 'Chuyển khoản';
    const MOMO = 'Momo';
    const VNPAY = 'VNPay';
    const PAYPAL = 'Paypal';
    const QR = 'QR-code';
}
