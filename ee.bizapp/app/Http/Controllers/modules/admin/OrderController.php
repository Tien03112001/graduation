<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use App\Models\ContentProduct;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Utils\OfficeUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $status = $request->input('status');
        $code = $request->input('code');
        $date = $request->input('date');
        $search = $request->input('search');
        $sql = (new Order())
            ->when(isset($status), function ($q) use ($status) {
                return $q->where('status', '=', $status);
            })
            ->when(isset($date), function ($q) use ($date) {
                $from_date = Carbon::parse($date . ' 00:00:00')->timestamp;
                $to_date = Carbon::parse($date . ' 23:59:59')->timestamp;
                return $q->where('date', '>=', $from_date)
                    ->where('date', '<=', $to_date);
            })
            ->when(isset($search), function ($q) use ($search) {
                return $q->where('note', 'like', '%' . $search . '%')
                    ->orWhere('request', 'like', '%' . $search . '%')
                    ->orWhere('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('customer_phone', 'like', '%' . $search . '%')
                    ->orWhere('customer_address', 'like', '%' . $search . '%');
            })
            ->when(isset($code), function ($q) use ($code) {
                return $q->where('code', 'like', '%' . $code . '%');
            })
            ->with('order_details.product', 'order_details', 'customer')
            ->orderBy('id', 'DESC');
        if ($limit) {
            $data = $sql->paginate($limit);
        } else {
            $data = $sql->get();
        }
        return $this->success($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_details' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $sub_total = 0;
        $customer_id = $request->input('customer_id', null);
        $order_detail = $request->input('order_details', null);
        foreach ($order_detail as $item) {
            $product = ContentProduct::find($item['product_id']);
            $price = $product->price ?? 0;
            $sub_total = $sub_total + $price * $item['quantity'];
        }
        $date = Carbon::now()->toDateString();
        $date = Carbon::parse($date)->timestamp;
        $order = new Order();
        $order->customer_id = $customer_id[0]['id'];
        $order->sub_total = $sub_total;
        $order->total = $sub_total;
        $order->date = $date;
        $order->status = $request->input('status');
        $order->payment_status = $request->input('payment_status');
        $order->save();
        foreach ($order_detail as $item) {
            $detail = new OrderDetail();
            $detail->product_id = $item['product_id'];
            $detail->order_id = $order->id;
            $detail->quantity = $item['quantity'];
            $product = ContentProduct::find($item['product_id']);
            $detail->sub_total = $product->price;
            $detail->total = $product['price'] * $item['quantity'];
            $detail->save();
        }
        return $this->success($order->load('order_details.product', 'customer'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Order::with('order_details.product', 'customer')->find($id);
        $data->total = $request->input('total');
        $data->note = $request->input('note');
        $data->status = $request->input('status');
        $data->payment_status = $request->input('payment_status');
        $data->save();
        $data->load('order_details.product', 'customer');
        return $this->success($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Order::find($id);
        $data->order_details()->delete();
        $data->delete();
        return $this->success($data);
    }

    public function export(Request $request)
    {
        $from_date = Carbon::parse($request->input('from_date', now()->toDateString()))->timestamp;
        $to_date = Carbon::parse($request->input('to_date', now()->toDateString()))->addDay()->timestamp;
        $status = $request->input('status', null);
        $orders = Order::where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->when(!empty($status), function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->with('order_details.product')
            ->get();

        $data = [
            'orders' => [
                [
                    'Ngày',
                    'Mã',
                    'Khách hàng',
                    'SĐT',
                    'Địa chỉ',
                    'Mã sp',
                    'Size',
                    'Số lượng',
                    'Giá',
                    'Phí sp',
                    'Tổng',
                    'Phí ship',
                    'Tổng tiền'
                ]
            ]
        ];
        $rows = [];

        foreach ($orders as $order) {
            $row = [
                Carbon::createFromTimestamp($order->date)->toDateString(),
                $order->code,
                $order->customer_name,
                $order->customer_phone,
                $order->customer_address,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                $order->sub_total,
                $order->shipping_fee,
                $order->total,
            ];
            array_push($rows, $row);

            foreach ($order->order_details as $detail) {
                $row = [
                    null,
                    null,
                    null,
                    null,
                    null,
                    $detail->product->code ?? $detail->name,
                    $detail->size,
                    $detail->quantity,
                    $detail->sub_total,
                    $detail->total,
                    $order->shipping_fee,
                    $order->total,
                    null,
                    null,
                    null
                ];
                array_push($rows, $row);
            }

        }

        $data['orders'] = array_merge($data['orders'], $rows);
        try {
            $writer = OfficeUtil::writeXLSX($data);
            $response = new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="export.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
            return $response;
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
