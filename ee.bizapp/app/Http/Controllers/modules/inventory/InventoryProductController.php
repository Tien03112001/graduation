<?php

namespace App\Http\Controllers\modules\inventory;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Product;
use App\Repository\InventoryProductRepositoryInterface;
use App\Repository\ProductVariantRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Utils\AuthUtil;
use App\Utils\CacheUtil;
use App\Utils\OfficeUtil;
use App\Models\ImportingNote;
use App\Models\InventoryProduct;
use App\Models\ProductVariant;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryProductController extends RestController
{
    protected $variantRepository;

    public function __construct(InventoryProductRepositoryInterface $repository, ProductVariantRepositoryInterface $variantRepository)
    {
        parent::__construct($repository);
        $this->variantRepository = $variantRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:desc');
        $with = ['variant'];
        $withCount = [];

        if ($request->has('product_id') && Str::length($request->product_id) > 0) {
            array_push($clauses, WhereClause::query('product_id', $request->product_id));
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
        // TODO: Implement store() method.
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->error('Sản phẩm không tồn tại');
        }
        $validator = $this->validateRequest($request, [
            'quantity' => 'nullable|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'quantity',
        ]);
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function addQuantity(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->error('Sản phẩm không tồn tại');
        }
        $validator = $this->validateRequest($request, [
            'add_quantity' => 'nullable|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes['quantity'] = $request->add_quantity + $model->quantity;
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        // TODO: Implement show() method.
    }

    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return $this->errorClient('Không có file');
        }
        $file = $request->file('file');
        if ($file->getClientOriginalExtension() != 'xlsx') {
            return $this->errorClient('Không đúng định dạng file .xlsx');
        }

        $newData = OfficeUtil::readXLSX($file->getRealPath(), 0, 2, 'A', -1, 'D');

        if (!empty($newData)) {
            $all = Product::all();
            $dict_products = [];
            foreach ($all as $c) {
                $dict_products[$c->code] = $c;
            }
            foreach ($newData as $key => $row) {
                $i = $key + 1;
                $codeValue = trim($row[0]);
                $variantValue = $row[2];
                $quantity = intval($row[3]);
                if (empty($codeValue) || empty($variantValue) || !array_key_exists($codeValue, $dict_products)) {
                    return $this->errorClient('Lỗi dữ liệu dòng ' . $i);
                }
                $product = $dict_products[$codeValue];
                $variant = ProductVariant::whereName($variantValue)->whereProductId($product->id)->first();
                if (empty($variant)) {
                    return $this->errorClient('Lỗi dữ liệu dòng ' . $i);
                }
            }
            try {
                DB::beginTransaction();
                foreach ($newData as $row) {
                    $codeValue = trim($row[0]);
                    $variantValue = $row[2];
                    $quantity = intval($row[3]);
                    $product = $dict_products[$codeValue];
                    $variant = ProductVariant::whereName($variantValue)->whereProductId($product->id)->first();
                    $ip = InventoryProduct::where('product_id', $product->id)
                        ->where('variant_id', $variant->id)
                        ->first();
                    if (empty($ip)) {
                        $ip = new InventoryProduct();
                        $ip->product_id = $product->id;
                        $ip->quantity = 0;
                        $ip->variant_id = $variant->id;
                        $ip->size = 0;
                    }
                    $ip->product_code = $codeValue;
                    $ip->code = $codeValue . '-' . $variantValue;
                    $ip->quantity += $quantity;
                    $ip->save();
                    DB::commit();
                    $dict_products[$product->code] = $product;
                }
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->error($e->getMessage());
            }
            sleep(0.5);
        }
    }

    public function export()
    {
        $xlsx = [
            'data' => [['Mã', 'Tên SP', 'Size', 'Số lượng', 'Ghi chú']]
        ];

        $data = (new InventoryProduct())
            ->with('product', 'variant')
            ->orderBy('product_code')->get();

        foreach ($data as $row) {
            array_push($xlsx['data'], [$row->product->code, $row->product->name, $row->variant->name, $row->quantity, null]);
        }

        try {
            $writer = OfficeUtil::writeXLSX($xlsx);
            $response = new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="ket_qua_' . time() . '.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
            return $response;
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function printBarcode($id, Request $request)
    {
        $ip = InventoryProduct::find($id);
        if (empty($ip)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $shopName = CacheUtil::getAppSetting()['master_name'];
        $quantity = $request->input('quantity', 1);
        try {
            return $this->success([
                'src' => view('barcode.inventory_product', compact('shopName', 'ip', 'quantity'))->render()
            ]);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }
}
