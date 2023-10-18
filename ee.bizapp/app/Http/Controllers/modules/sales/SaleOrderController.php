<?php

namespace App\Http\Controllers\modules\sales;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Jobs\ChangeStatus;
use App\Repository\SaleOrderRepositoryInterface;
use App\Repository\SaleOrderDetailRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Models\SaleOrder;
use App\Repository\InventoryProductRepositoryInterface;
use App\Repository\SaleCustomerRepositoryInterface;
use App\Repository\VoucherRepositoryInterface;
use App\Utils\AuthUtil;
use App\Utils\Logistics\GiaoHangTietKiemUtil;
use App\Utils\GiaoHangUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Utils\FileStorageUtil;
use Illuminate\Support\Str;


class SaleOrderController extends RestController
{
    protected $productRepository;
    protected $detailRepository;
    protected $inventoryRepository;
    protected $customerRepository;
    protected $voucherRepository;

    public function __construct(
        SaleOrderRepositoryInterface $repository,
        ProductRepositoryInterface $productRepository,
        SaleOrderDetailRepositoryInterface $detailRepository,
        InventoryProductRepositoryInterface $inventoryRepository,
        SaleCustomerRepositoryInterface $customerRepository,
        VoucherRepositoryInterface $voucherRepository
    ) {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
        $this->detailRepository = $detailRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->customerRepository = $customerRepository;
        $this->voucherRepository = $voucherRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sale = AuthUtil::getInstance()->getModel();
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'updated_at:desc');
        $with = ['details.variant', 'details.product.variants', 'shipping', 'payment', 'voucher'];
        $withCount = [];

        array_push($clauses, WhereClause::query('seller_id', $sale->id));
        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('customer_name', $request->search));
        }
        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }
        if ($request->has('customer_phone')) {
            array_push($clauses, WhereClause::queryLike('customer_phone', $request->customer_phone));
        }
        if ($request->has('channel')) {
            array_push($clauses, WhereClause::query('channel', $request->channel));
        }
        if ($request->has('created_date')) {
            array_push($clauses, WhereClause::queryDate('created_at', $request->created_date));
        }
        if ($request->has('status')) {
            array_push($clauses, WhereClause::query('status', $request->status));
        }
        if ($request->has('code')) {
            array_push($clauses, WhereClause::queryLike('code', $request->code));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $validator = Validator::make($request->all(), [
            'channel' => 'required',
            'customer_phone' => 'required',
            'customer_name' => 'required',
            'customer_address' => 'required',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'ship_fee' => 'required|numeric',
            'cod_fee' => 'required|numeric',
            'payment_type' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $prefix = 'SPX_';
        $code = $prefix . rand(10000, 100000);
        while (SaleOrder::whereCode($code)->exists()) {
            $code = $prefix . rand(10000, 100000);
        }
        $attributes['seller_id'] = $sellerId;
        $attributes['code'] = $code;
        $attributes['channel'] = $request->input('channel');
        $attributes['customer_name'] = $request->input('customer_name');
        $attributes['customer_phone'] = $request->input('customer_phone');
        $attributes['customer_address'] = $request->input('customer_address');
        $attributes['province'] = $request->input('province');
        $attributes['district'] = $request->input('district');
        $attributes['ward'] = $request->input('ward');
        $attributes['customer_request'] = $request->input('customer_request');
        $attributes['ship_fee'] = $request->input('ship_fee', 0);
        $attributes['cod_fee'] = $request->input('cod_fee', 0);
        $attributes['payment_type'] = $request->input('payment_type');
        $attributes['pre_charged'] = $request->input('pre_charged', 0);
        $attributes['banking_sms'] = $request->input('banking_sms');
        $attributes['note'] = $request->input('note');
        if ($request->status == 'Lưu nháp') {
            $attributes['status'] = SaleOrder::$TRANG_THAI_LUU_NHAP;
        } else {
            $attributes['status'] = SaleOrder::$TRANG_THAI_XAC_THUC;
        }
        if($request->customer_request == 'null') {
            $attributes['customer_request'] = null;
        }
        if($request->banking_sms == 'null') {
            $attributes['banking_sms'] = null;
        }
        if($request->note == 'null') {
            $attributes['note'] = null;
        }
        $attributes['banking_img'] = '';
        $createdImages = [];
        if ($request->file('banking_img') != null) {
            $banking = FileStorageUtil::getInstance()->putFile('banking_img', $request->file('banking_img'));
            array_push($createdImages, $banking);
            $attributes['banking_img'] = $banking;
        }

        $products = json_decode($request->product, true);
        if ($products == null) {
            return $this->errorClient('Chưa chọn sản phẩm nào');
        }

        try {
            DB::beginTransaction();
            // Kiểm tra người dùng
            $customer = $this->customerRepository->find([WhereClause::query('phone', $request->input('customer_phone'))]);
            if ($customer == null) {
                $attributeCustomers['name'] = $request->input('customer_name');
                $attributeCustomers['phone'] = $request->input('customer_phone');
                $attributeCustomers['address'] = $request->input('customer_address');
                $attributeCustomers['province'] = $request->input('province');
                $attributeCustomers['district'] = $request->input('district');
                $attributeCustomers['ward'] = $request->input('ward');
                $this->customerRepository->create($attributeCustomers);
            }
            //Kiểm tra
            foreach ($products as $p) {
                //Kiểm tra giá trị số
                if ($p['quantity'] <= 0 || $p['quantity'] == null) {
                    return $this->errorClient('Số lượng sản phẩm ' . $p['product']['code'] . ' không hợp lệ');
                }
                if ($p['unit_price'] <= 0 || $p['unit_price'] == null) {
                    return $this->errorClient('Giá sản phẩm ' . $p['product']['code'] . ' không hợp lệ');
                }
                if ($p['variant_id'] == 0) {
                    return $this->errorClient('Sản phẩm ' . $p['product']['code'] . ' chưa chọn biến thể');
                }
                // Tìm số biến thể trong kho
                $inventory = $this->inventoryRepository->find([WhereClause::query('product_id', $p['product']['id']), WhereClause::query('variant_id', $p['variant_id'])]);
                if ($inventory->quantity == 0) {
                    return $this->errorClient('Sản phẩm ' . $p['product']['code'] . ' không đủ hàng');
                }
            }
            if ($request->has('voucher')) {
                $voucher = $this->voucherRepository->findById($request->voucher);
                if (empty($voucher)) {
                    return $this->error('Mã giảm giá không đúng');
                }
                $this->voucherRepository->update($voucher->id, ['quantity' => $voucher->quantity - 1]);
                $attributes['voucher_id'] = $request->voucher;
            }
            $attributes['amount'] = $request->amount;
            $attributes['total_amount'] = $request->amount + $attributes['ship_fee'];
            $order = $this->repository->create($attributes);

            //Tạo chi tiết đơn hàng
            foreach ($products as $p) {
                $attributeDetails['order_id'] = $order->id;
                $attributeDetails['detail_code'] = $order->id;
                $attributeDetails['size'] = $request->input('size', 0);
                $attributeDetails['product_id'] = $p['product']['id'];
                $attributeDetails['product_code'] = $p['product']['code'];
                $attributeDetails['variant_id'] = $p['variant_id'];
                $attributeDetails['quantity'] = $p['quantity'];
                $attributeDetails['unit_price'] = $p['unit_price'];
                $attributeDetails['amount'] = $attributeDetails['quantity'] * $attributeDetails['unit_price'];
                $this->detailRepository->create($attributeDetails);
            }
            DB::commit();
            ChangeStatus::dispatch($order->id)->delay(now()->addMinutes(60))->afterCommit();
            return $this->success($order);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            if ($attributes['banking_img'] != '') {
                FileStorageUtil::getInstance()->deleteFile($attributes['banking_img']);
            }
            return $this->error($ex->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $order = $this->repository->findById($id, ['details']);

        if (empty($order)) {
            return $this->error('Đơn hàng không tồn tại');
        }
        $validator = Validator::make($request->all(), [
            'channel' => 'nullable',
            'customer_phone' => 'nullable',
            'customer_name' => 'nullable',
            'customer_address' => 'nullable',
            'province' => 'nullable',
            'district' => 'nullable',
            'ward' => 'nullable',
            'ship_fee' => 'nullable|numeric',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $attributes['channel'] = $request->input('channel');
        $attributes['customer_name'] = $request->input('customer_name');
        $attributes['customer_phone'] = $request->input('customer_phone');
        $attributes['customer_address'] = $request->input('customer_address');
        $attributes['province'] = $request->input('province');
        $attributes['district'] = $request->input('district');
        $attributes['ward'] = $request->input('ward');
        $attributes['customer_request'] = $request->input('customer_request');
        $attributes['ship_fee'] = $request->input('ship_fee', 0);
        $attributes['cod_fee'] = $request->input('cod_fee', 0);
        $attributes['payment_type'] = $request->input('payment_type', 0);
        $attributes['pre_charged'] = $request->input('pre_charged', 0);
        $attributes['banking_sms'] = $request->input('banking_sms');
        $attributes['note'] = $request->input('note');
        if ($request->status == 'Lưu nháp') {
            $attributes['status'] = SaleOrder::$TRANG_THAI_LUU_NHAP;
        } else {
            $attributes['status'] = SaleOrder::$TRANG_THAI_LEN_DON;
        }
        if($request->customer_request == 'null') {
            $attributes['customer_request'] = null;
        }
        if($request->banking_sms == 'null') {
            $attributes['banking_sms'] = null;
        }
        if($request->note == 'null') {
            $attributes['note'] = null;
        }
        $createdImages = [];
        if ($request->file('banking_img') != null) {
            if ($order->banking_img != null) {
                FileStorageUtil::getInstance()->deleteFile($order->banking_img);
            }
            $banking = FileStorageUtil::getInstance()->putFile('banking_img', $request->file('banking_img'));
            array_push($createdImages, $banking);
            $attributes['banking_img'] = $banking;
        }

        $products = json_decode($request->product, true);
        if ($products == null) {
            return $this->errorClient('Chưa chọn sản phẩm nào');
        }

        try {
            DB::beginTransaction();
            //Kiểm tra xem có xóa đơn khum
            $variant = [];
            foreach ($products as $p) {
                array_push($variant, $p['variant_id']);
            }

            foreach ($order->details as $d) {
                $check = in_array($d->variant_id, $variant);
                if (!$check) {
                    // Xóa detail chưa có
                    $this->detailRepository->delete($d->id);
                }
            }

            // Lặp sản phẩm
            foreach ($products as $p) {
                //Kiểm tra giá trị số
                if ($p['quantity'] <= 0 || $p['quantity'] == null) {
                    return $this->errorClient('Số lượng sản phẩm ' . $p['product']['code'] . ' không hợp lệ');
                }
                if ($p['unit_price'] <= 0 || $p['unit_price'] == null) {
                    return $this->errorClient('Giá sản phẩm ' . $p['product']['code'] . ' không hợp lệ');
                }
                if ($p['variant_id'] == 0) {
                    return $this->errorClient('Sản phẩm ' . $p['product']['code'] . ' chưa chọn biến thể');
                }
                // Tìm số biến thể trong kho
                $inventory = $this->inventoryRepository->find([
                    WhereClause::query('product_id', $p['product']['id']),
                    WhereClause::query('variant_id', $p['variant_id'])
                ]);
                if ($inventory->quantity == 0) {
                    return $this->errorClient('Sản phẩm ' . $p['product']['code'] . ' không đủ hàng');
                }

                // Tìm xem đơn hàng trước có sản phẩm đấy ko
                $order_detail = $this->detailRepository->find([
                    WhereClause::query('order_id', $order->id),
                    WhereClause::query('product_id', $p['product']['id']),
                    WhereClause::query('variant_id', $p['variant_id'])
                ]);

                if (empty($order_detail)) {
                    // Thông tin đơn chi tiết
                    $attributeDetails['order_id'] = $order->id;
                    $attributeDetails['detail_code'] = $order->id;
                    $attributeDetails['size'] = $request->input('size', 0);
                    $attributeDetails['product_id'] = $p['product']['id'];
                    $attributeDetails['product_code'] = $p['product']['code'];
                    $attributeDetails['variant_id'] = $p['variant_id'];
                    $attributeDetails['quantity'] = $p['quantity'];
                    $attributeDetails['unit_price'] = $p['unit_price'];
                    $attributeDetails['amount'] = $attributeDetails['quantity'] * $attributeDetails['unit_price'];
                    $this->detailRepository->create($attributeDetails);
                } else {
                    // Thông tin chi tiết đơn
                    $attributeDetails['size'] = $request->input('size', 0);
                    $attributeDetails['product_id'] = $p['product']['id'];
                    $attributeDetails['product_code'] = $p['product']['code'];
                    $attributeDetails['variant_id'] = $p['variant_id'];
                    $attributeDetails['quantity'] = $p['quantity'];
                    $attributeDetails['unit_price'] = $p['unit_price'];
                    $attributeDetails['amount'] = $attributeDetails['quantity'] * $attributeDetails['unit_price'];
                    $this->detailRepository->update($order_detail->id, $attributeDetails);
                }
            }

            if ($request->has('voucher')) {
                if ($request->voucher == 'null') {
                    if ($order->voucher_id != null) {
                        $voucher = $this->voucherRepository->findById($order->voucher_id);
                        if (empty($voucher)) {
                            return $this->errorClient('Mã giảm giá không đúng');
                        }
                        $this->voucherRepository->update($voucher->id, ['quantity' => $voucher->quantity + 1]);
                    }
                    $attributes['voucher_id'] = null;
                } else {
                    if ($order->voucher_id != null) {
                        if ($request->voucher == $order->voucher_id) {
                            $attributes['voucher_id'] = $order->voucher_id;
                        } else {
                            $voucherOld = $this->voucherRepository->findById($order->voucher_id);
                            if (empty($voucherOld)) {
                                return $this->errorClient('Mã giảm giá không đúng');
                            }
                            $this->voucherRepository->update($voucherOld->id, ['quantity' => $voucherOld->quantity + 1]);

                            $voucherNew = $this->voucherRepository->findById($request->voucher);
                            if (empty($voucherNew)) {
                                return $this->errorClient('Mã giảm giá không đúng');
                            }
                            $this->voucherRepository->update($request->voucher, ['quantity' => $voucherNew->quantity - 1]);
                            $attributes['voucher_id'] = $request->voucher;
                        }
                    } else {
                        $voucher = $this->voucherRepository->findById($request->voucher);
                        if (empty($voucher)) {
                            return $this->errorClient('Mã giảm giá không đúng');
                        }
                        $this->voucherRepository->update($request->voucher, ['quantity' => $voucher->quantity - 1]);
                        $attributes['voucher_id'] = $request->voucher;
                    }
                }
            }
            $attributes['amount'] = $request->amount;
            $attributes['total_amount'] = $request->amount + $attributes['ship_fee'];

            //Cập nhật đơn hàng
            $order = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($order);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            if ($request->file('banking_img') != null) {
                FileStorageUtil::getInstance()->deleteFile($attributes['banking_img']);
            }
            return $this->error($ex->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }

        if ($model->status == SaleOrder::$TRANG_THAI_LEN_DON) {
            $model->delete();
            return $this->success($model);
        }

        return $this->error('Đơn hàng này không thể xóa');
    }

    // Xác nhận đơn thường
    public function verify($id, Request $request)
    {
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $model->cod_fee = $request->input('cod_fee');
        $model->payment_type = $request->input('payment_type');
        $model->banking_sms = $request->input('banking_sms');
        $model->pre_charged = $model->total_amount - $model->cod_fee;
        $model->note = $request->input('note');
        if($request->banking_sms == 'null') {
            $model->banking_sms = null;
        }
        if($request->note == 'null') {
            $model->note = null;
        }
        $model->status = SaleOrder::$TRANG_THAI_XAC_THUC;
        $createdImages = [];
        if ($request->file('banking_img') != null) {
            if ($model->banking_img != null) {
                FileStorageUtil::getInstance()->deleteFile($model->banking_img);
            }
            $banking = FileStorageUtil::getInstance()->putFile('banking_img', $request->file('banking_img'));
            array_push($createdImages, $banking);
            $model->banking_img = $banking;
        }
        try {
            $model->save();
            ChangeStatus::dispatch($id)->delay(now()->addMinutes(60));
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            if ($request->file('banking_img') != null) {
                FileStorageUtil::getInstance()->deleteFile($model->banking_img);
            }
            return $this->error($e->getMessage());
        }
    }

    // Xác nhận đơn nháp
    public function draftverify(Request $request, $id)
    {
        $order = $this->repository->findById($id, ['details']);

        if (empty($order)) {
            return $this->error('Đơn hàng không tồn tại');
        }

        $validator = Validator::make($request->all(), [
            'channel' => 'nullable',
            'customer_phone' => 'nullable',
            'customer_name' => 'nullable',
            'customer_address' => 'nullable',
            'province' => 'nullable',
            'district' => 'nullable',
            'ward' => 'nullable',
            'ship_fee' => 'nullable|numeric',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $attributes['channel'] = $request->input('channel');
        $attributes['customer_name'] = $request->input('customer_name');
        $attributes['customer_phone'] = $request->input('customer_phone');
        $attributes['customer_address'] = $request->input('customer_address');
        $attributes['province'] = $request->input('province');
        $attributes['district'] = $request->input('district');
        $attributes['ward'] = $request->input('ward');
        $attributes['customer_request'] = $request->input('customer_request');
        $attributes['ship_fee'] = $request->input('ship_fee', 0);
        $attributes['cod_fee'] = $request->input('cod_fee', 0);
        $attributes['payment_type'] = $request->input('payment_type', 0);
        $attributes['pre_charged'] = $request->input('pre_charged', 0);
        $attributes['banking_sms'] = $request->input('banking_sms');
        $attributes['note'] = $request->input('note');
        $attributes['status'] = SaleOrder::$TRANG_THAI_XAC_THUC;
        if($request->customer_request == 'null') {
            $attributes['customer_request'] = null;
        }
        if($request->banking_sms == 'null') {
            $attributes['banking_sms'] = null;
        }
        if($request->note == 'null') {
            $attributes['note'] = null;
        }
        $createdImages = [];
        if ($request->file('banking_img') != null) {
            if ($order->banking_img != null) {
                FileStorageUtil::getInstance()->deleteFile($order->banking_img);
            }
            $banking = FileStorageUtil::getInstance()->putFile('banking_img', $request->file('banking_img'));
            array_push($createdImages, $banking);
            $attributes['banking_img'] = $banking;
        }

        //Tính tổng tiền
        $products = json_decode($request->product, true);
        if ($products == null) {
            return $this->errorClient('Chưa chọn sản phẩm nào');
        }

        try {
            DB::beginTransaction();
            // Kiểm tra người dùng
            $customer = $this->customerRepository->find([WhereClause::query('phone', $request->input('customer_phone'))]);
            if ($customer == null) {
                $attributeCustomers['name'] = $request->input('customer_name');
                $attributeCustomers['phone'] = $request->input('customer_phone');
                $attributeCustomers['address'] = $request->input('customer_address');
                $attributeCustomers['province'] = $request->input('province');
                $attributeCustomers['district'] = $request->input('district');
                $attributeCustomers['ward'] = $request->input('ward');
                $this->customerRepository->create($attributeCustomers);
            }
            //Kiểm tra xem có xóa đơn khum
            $variant = [];
            foreach ($products as $p) {
                array_push($variant, $p['variant_id']);
            }

            foreach ($order->details as $d) {
                $check = in_array($d->variant_id, $variant);
                if (!$check) {
                    // Xóa detail chưa có
                    $this->detailRepository->delete($d->id);
                }
            }

            // Lặp sản phẩm
            foreach ($products as $p) {
                //Kiểm tra giá trị số
                if ($p['quantity'] <= 0 || $p['quantity'] == null) {
                    return $this->errorClient('Số lượng sản phẩm ' . $p['product']['code'] . ' không hợp lệ');
                }
                if ($p['unit_price'] <= 0 || $p['unit_price'] == null) {
                    return $this->errorClient('Giá sản phẩm ' . $p['product']['code'] . ' không hợp lệ');
                }
                if ($p['variant_id'] == 0) {
                    return $this->errorClient('Sản phẩm ' . $p['product']['code'] . ' chưa chọn biến thể');
                }

                // Tìm số biến thể trong kho
                $inventory = $this->inventoryRepository->find([
                    WhereClause::query('product_id', $p['product']['id']),
                    WhereClause::query('variant_id', $p['variant_id'])
                ]);
                if ($inventory->quantity == 0) {
                    return $this->errorClient('Sản phẩm ' . $p['product']['code'] . ' không đủ hàng');
                }

                // Tìm xem đơn hàng trước có sản phẩm đấy ko
                $order_detail = $this->detailRepository->find(
                    [
                        WhereClause::query('order_id', $order->id),
                        WhereClause::query('product_id', $p['product']['id']),
                        WhereClause::query('variant_id', $p['variant_id'])
                    ]
                );
                if (empty($order_detail)) {
                    // Thông tin đơn chi tiết
                    $attributeDetails['order_id'] = $order->id;
                    $attributeDetails['detail_code'] = $order->id;
                    $attributeDetails['size'] = $request->input('size', 0);
                    $attributeDetails['product_id'] = $p['product']['id'];
                    $attributeDetails['product_code'] = $p['product']['code'];
                    $attributeDetails['variant_id'] = $p['variant_id'];
                    $attributeDetails['quantity'] = $p['quantity'];
                    $attributeDetails['unit_price'] = $p['unit_price'];
                    $attributeDetails['amount'] = $attributeDetails['quantity'] * $attributeDetails['unit_price'];
                    $this->detailRepository->create($attributeDetails);
                } else {
                    // Thông tin chi tiết đơn
                    $attributeDetails['size'] = $request->input('size', 0);
                    $attributeDetails['product_id'] = $p['product']['id'];
                    $attributeDetails['product_code'] = $p['product']['code'];
                    $attributeDetails['variant_id'] = $p['variant_id'];
                    $attributeDetails['quantity'] = $p['quantity'];
                    $attributeDetails['unit_price'] = $p['unit_price'];
                    $attributeDetails['amount'] = $attributeDetails['quantity'] * $attributeDetails['unit_price'];
                    $this->detailRepository->update($order_detail->id, $attributeDetails);
                }
            }

            if ($request->has('voucher')) {
                if ($request->voucher == 'null') {
                    if ($order->voucher_id != null) {
                        $voucher = $this->voucherRepository->findById($order->voucher_id);
                        if (empty($voucher)) {
                            return $this->errorClient('Mã giảm giá không đúng');
                        }
                        $this->voucherRepository->update($voucher->id, ['quantity' => $voucher->quantity + 1]);
                    }
                    $attributes['voucher_id'] = null;
                } else {
                    if ($order->voucher_id != null) {
                        if ($request->voucher == $order->voucher_id) {
                            $attributes['voucher_id'] = $order->voucher_id;
                        } else {
                            $voucherOld = $this->voucherRepository->findById($order->voucher_id);
                            if (empty($voucherOld)) {
                                return $this->errorClient('Mã giảm giá không đúng');
                            }
                            $this->voucherRepository->update($voucherOld->id, ['quantity' => $voucherOld->quantity + 1]);

                            $voucherNew = $this->voucherRepository->findById($request->voucher);
                            if (empty($voucherNew)) {
                                return $this->errorClient('Mã giảm giá không đúng');
                            }
                            $this->voucherRepository->update($request->voucher, ['quantity' => $voucherNew->quantity - 1]);
                            $attributes['voucher_id'] = $request->voucher;
                        }
                    } else {
                        $voucher = $this->voucherRepository->findById($request->voucher);
                        if (empty($voucher)) {
                            return $this->errorClient('Mã giảm giá không đúng');
                        }
                        $this->voucherRepository->update($request->voucher, ['quantity' => $voucher->quantity - 1]);
                        $attributes['voucher_id'] = $request->voucher;
                    }
                }
            }
            $attributes['amount'] = $request->amount;
            $attributes['total_amount'] = $request->amount + $attributes['ship_fee'];

            //Cập nhật đơn hàng
            $order = $this->repository->update($id, $attributes);
            DB::commit();
            ChangeStatus::dispatch($order->id)->delay(now()->addMinutes(60))->afterCommit();
            return $this->success($order);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            if ($request->file('banking_img') != null) {
                FileStorageUtil::getInstance()->deleteFile($attributes['banking_img']);
            }
            return $this->error($ex->getMessage());
        }
    }

    public function confirm($id, Request $request)
    {
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $model->status = SaleOrder::$TRANG_THAI_CHUAN_BI_HANG;
        $model->note = $request->input('note');
        if($request->note == 'null') {
            $model->note = null;
        }
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function recreate($id, Request $request)
    {
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $model->status = SaleOrder::$TRANG_THAI_LEN_DON;
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    // Hủy đơn hàng
    public function cancel($id, Request $request)
    {
        $model = SaleOrder::with('details', 'shipping')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            SaleOrder::setCanceled($model);
            if ($model->shipping) {
                $ghUtil = new GiaoHangUtil($model);
                $response = $ghUtil->cancelOrder($model->shipping);
                if ($response) {
                    $model->shipping->save();
                } else {
                    throw new \Exception('Không thể hủy đơn hàng');
                }
            }
            $model->status = "Hủy đơn";
            $model->note = $request->input('note');
            if($request->note == 'null') {
                $model->note = null;
            }
            $model->save();
            DB::commit();
            $model->load('shipping');
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function complete($id, Request $request)
    {
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $model->status = SaleOrder::$TRANG_THAI_HOAN_THANH;
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function refundAmount($id, Request $request)
    {
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $model->status = SaleOrder::$TRANG_THAI_HOAN_TIEN;
        $model->note = $request->input('note');
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }
}
