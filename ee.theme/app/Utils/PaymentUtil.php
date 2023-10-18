<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 9/22/2022
 * Time: 13:59
 */

namespace App\Utils;


use App\Common\Config\ManualPayConfig;
use App\Common\Enum\PaymentMethodEnum;
use App\Common\SingletonPattern;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Utils\Payments\VnPayUtil;

class PaymentUtil extends SingletonPattern
{

    protected $redirectUrl;
    protected $accounts = [];

    /**
     * @return PaymentUtil
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * Thực hiện thanh toán
     * @param PaymentMethod $method
     * @param Order $order
     * @return PaymentUtil|array
     * @throws \Exception
     */
    public function process(PaymentMethod $method, Order $order)
    {

        if ($method->name == PaymentMethodEnum::MOMO) {
            //thanh toan qua MOMO
            $this->processMomo($order, $method);
        } elseif ($method->name == PaymentMethodEnum::VNPAY) {
            //thanh toan qua VNPAY
            $this->processVnPay($order, $method);
        } elseif ($method->name == PaymentMethodEnum::QR) {
            //thanh toan qua QR
        } elseif ($method->name == PaymentMethodEnum::PAYPAL) {
            //thanh toan qua PAYPAL
            $this->processPaypal($order, $method);
        } else if ($method->name == PaymentMethodEnum::MANUAL) {
            //chuyen khoan
            $config = new ManualPayConfig(json_decode($method->config, true));
            $this->accounts = $config->getAccounts();
        } else if ($method->name == PaymentMethodEnum::COD) {
            //CoD
        } else {
            throw new \Exception('Không hỗ trợ phương thức thanh toán này');
        }
        return $this;
    }

    /**
     * @param Order $order
     * @param PaymentMethod $paymentMethod
     * @throws \Exception
     */
    private function processMomo(Order $order, PaymentMethod $paymentMethod)
    {
        //Step 1: lấy cấu hình config
        //Step 2: Sinh redirect url
        $this->redirectUrl = '';
    }

    /**
     * @param Order $order
     * @param PaymentMethod $paymentMethod
     * @throws \Exception
     */
    private function processVnPay(Order $order, PaymentMethod $paymentMethod)
    {
        $this->redirectUrl = VnPayUtil::getInstance()->createRequest($order, $paymentMethod);
    }

    /**
     * @param Order $order
     * @param PaymentMethod $paymentMethod
     * @throws \Exception
     */
    private function processPaypal(Order $order, PaymentMethod $paymentMethod)
    {
        //Step 1: lấy cấu hình config
        //Step 2: Sinh redirect url
        $this->redirectUrl = '';
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @return array
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

}