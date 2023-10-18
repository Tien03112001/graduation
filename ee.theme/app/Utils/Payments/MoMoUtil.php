<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 10/22/2022
 * Time: 01:54
 */

namespace App\Utils\Payments;


use App\Common\Exceptions\ObjectInvalidCastException;
use App\Common\Exceptions\ObjectNotFoundException;
use App\Common\SingletonPattern;
use App\Common\WhereClause;
use App\Models\Order;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class MoMoUtil extends SingletonPattern
{

    /* @var VnPayConfig */
    protected $config;

    protected $errorCode;

    /**
     * @return MoMoUtil
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    protected function __construct()
    {
        try {
            $this->setDefaultConfig();
        } catch (ObjectInvalidCastException $e) {
            Log::error($e);
        } catch (ObjectNotFoundException $e) {
            Log::error($e);
        }
    }

    public function setConfig(VnPayConfig $config)
    {
        $this->config = $config;
        $this->errorCode = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
            '75' => 'Ngân hàng thanh toán đang bảo trì.',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)',
        ];
    }

    /**
     * @throws ObjectNotFoundException
     * @throws ObjectInvalidCastException
     */
    public function setDefaultConfig()
    {
        $repository = App::make(VnPayConfigRepositoryInterface::class);
        if ($repository instanceof VnPayConfigRepositoryInterface) {
            $config = $repository->find([
                WhereClause::query('enable', 1)
            ]);
            if (isset($config)) {
                $this->setConfig($config);
            } else {
                throw new ObjectNotFoundException();
            }
        } else {
            throw new ObjectInvalidCastException(VnPayConfigRepositoryInterface::class);
        }
    }


    /**
     * Tu thong tin don hang thanh url goi don hang
     * @param Order $order
     * @return string
     * @throws \Exception
     */
    public function createRequest(Order $order)
    {
        $this->checkConfig();

        $vnp_Url = $this->config->vnp_Url;
        $vnp_Returnurl = $order->vn_pay_transaction->return_url;
        $vnp_TmnCode = $this->config->vnp_TmnCode;//Mã website tại VNPAY
        $vnp_HashSecret = $this->config->vnp_HashSecret; //Chuỗi bí mật

        $vnp_TxnRef = $order->code; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = $order->vn_pay_transaction->description;
        $vnp_OrderType = $order->vn_pay_transaction->product_type;
        $vnp_Amount = $order->total_amount * 100;
        $vnp_Locale = $this->config->vnp_Locale;
        $vnp_IpAddr = $order->vn_pay_transaction->ip;
        /*//Add Params of 2.0.1 Version
        $vnp_ExpireDate = $_POST['txtexpire'];
        //Billing
        $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
        $vnp_Bill_Email = $_POST['txt_billing_email'];
        $fullName = trim($_POST['txt_billing_fullname']);
        if (isset($fullName) && trim($fullName) != '') {
            $name = explode(' ', $fullName);
            $vnp_Bill_FirstName = array_shift($name);
            $vnp_Bill_LastName = array_pop($name);
        }
        $vnp_Bill_Address = $_POST['txt_inv_addr1'];
        $vnp_Bill_City = $_POST['txt_bill_city'];
        $vnp_Bill_Country = $_POST['txt_bill_country'];
        $vnp_Bill_State = $_POST['txt_bill_state'];
        // Invoice
        $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
        $vnp_Inv_Email = $_POST['txt_inv_email'];
        $vnp_Inv_Customer = $_POST['txt_inv_customer'];
        $vnp_Inv_Address = $_POST['txt_inv_addr1'];
        $vnp_Inv_Company = $_POST['txt_inv_company'];
        $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'];
        $vnp_Inv_Type = $_POST['cbo_inv_type'];*/
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => $order->currency_code,
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            /*"vnp_ExpireDate" => $vnp_ExpireDate,
            "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            "vnp_Bill_Email" => $vnp_Bill_Email,
            "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            "vnp_Bill_LastName" => $vnp_Bill_LastName,
            "vnp_Bill_Address" => $vnp_Bill_Address,
            "vnp_Bill_City" => $vnp_Bill_City,
            "vnp_Bill_Country" => $vnp_Bill_Country,
            "vnp_Inv_Phone" => $vnp_Inv_Phone,
            "vnp_Inv_Email" => $vnp_Inv_Email,
            "vnp_Inv_Customer" => $vnp_Inv_Customer,
            "vnp_Inv_Address" => $vnp_Inv_Address,
            "vnp_Inv_Company" => $vnp_Inv_Company,
            "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
            "vnp_Inv_Type" => $vnp_Inv_Type*/
        );


        $query = $this->createQuery($inputData);
        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = $this->hashData($inputData, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        return $vnp_Url;

    }


    /**
     * @param array $inputData
     * @param Order $order
     * @return array
     * @throws \Exception
     */
    public function updateIPN(array $inputData, Order $order)
    {
        $this->checkConfig();
        $status = 0;
        $message = $this->errorCode[$inputData['vnp_ResponseCode']];
//        $orderCode = $inputData['vnp_TxnRef'];
//        $order = $this->orderRepository->find([
//            WhereClause::query('code', $orderCode)
//        ]);
        try {
            //Kiểm tra checksum của dữ liệu
            if ($this->checkSum($inputData)) {
                if (isset($order)) {
                    $vnp_Amount = $inputData['vnp_Amount'] / 100;
                    if ($order->total_amount == $vnp_Amount) {
                        if ($order->status == 0) {
                            if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                                $status = 1; // Trạng thái thanh toán thành công
                            } else {
                                $status = 2; // Trạng thái thanh toán thất bại / lỗi
                            }
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                        } else {
                            $returnData['RspCode'] = '02';
                            $returnData['Message'] = 'Order already confirmed';
                        }
                    } else {
                        $returnData['RspCode'] = '04';
                        $returnData['Message'] = 'invalid amount';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid signature';
            }
        } catch (\Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }
        return [
            'status' => $status,
            'message' => $message,
            'response' => json_encode($returnData)
        ];
    }

    /**
     * @throws \Exception
     */
    private function checkConfig()
    {
        if (empty($this->config)) {
            throw new \Exception('Chưa có cấu hình VNPAY');
        }
    }

    private function createQuery(array $inputData)
    {
        $query = "";
        ksort($inputData);
        foreach ($inputData as $key => $value) {
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        return $query;
    }

    private function hashData(array $inputData, $vnp_HashSecret)
    {
        ksort($inputData);
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        return hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    }

    private function checkSum(array $inputData)
    {
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        $secureHash = $this->hashData($inputData, $this->config->vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            return true;
        }
        return false;
    }
}