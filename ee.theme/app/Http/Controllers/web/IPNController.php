<?php

namespace App\Http\Controllers\web;

use App\Common\Enum\OrderPaymentStatusEnum;
use App\Common\Enum\OrderStatusEnum;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Order;
use App\Repository\OrderRepositoryInterface;
use App\Repository\PaymentMethodRepositoryInterface;
use App\Repository\PaymentTransactionRepositoryInterface;
use App\Utils\Core\VnPayUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IPNController extends RestController
{
    protected $transactionRepository;
    protected $paymentMethodRepository;

    public function __construct(OrderRepositoryInterface $repository,
                                PaymentMethodRepositoryInterface $paymentMethodRepository,
                                PaymentTransactionRepositoryInterface $transactionRepository)
    {
        parent::__construct($repository);
        $this->transactionRepository = $transactionRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function vnpay(Request $request)
    {
        $inputData = $request->all();
        $orderCode = $inputData['vnp_TxnRef'];
        $order = $this->repository->find([
            WhereClause::query('code', $orderCode)
        ], null, ['payment_method']);

        if (empty($order) || !($order instanceof Order)) {
            return VnPayUtil::responseOrderNotFoundToIPN();
        }

        if (empty($order->payment_method) || !($order->payment_method instanceof Order)) {
            return VnPayUtil::responseUnknownToIPN();
        }

        $payment = VnPayUtil::getInstance()->updateIPN($inputData, $order, $order->payment_method);
        if ($payment['status'] != OrderPaymentStatusEnum::PENDING) {
            try {
                DB::beginTransaction();
                $this->repository->update($order, [
                    'payment_status' => $payment['status'],
                ]);
                $this->transactionRepository->create([
                    'order_id' => $order->id,
                    'method_id' => $order->payment_method->id,
                    'redirect_url' => '',
                    'status' => $payment['status'] == OrderStatusEnum::COMPLETED ? 1 : 0,
                    'message' => $payment['message'],
                    'dump_data' => json_encode($inputData)
                ]);
                DB::commit();
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return VnPayUtil::responseUnknownToIPN();
            }
        }
        return $this->success($payment['response']);
    }

    public function momo(Request $request)
    {
    }

    public function paypal(Request $request)
    {
    }
}
