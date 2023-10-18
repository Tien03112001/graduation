<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentProduct;
use App\Models\ShoppingGProduct;
use App\Utils\OfficeUtil;
use App\Utils\ShoppingUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShoppingGProductController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = (new ShoppingGProduct())
            ->when($search, function ($q) use ($search) {
                return $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('gid', 'like', '%' . $search . '%');
            });
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $products = ContentProduct::with('g_shopping', 'inventories', 'category', 'tags')->get();
        try {
            DB::beginTransaction();
            foreach ($products as $product) {
                if ($product->g_shopping) {
                    $model = $product->g_shopping;
                } else {
                    $model = new ShoppingGProduct();
                }
                $model = ShoppingUtil::fill_g_product($product, $model);
                $model->save();
            }
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        $model = (new ShoppingGProduct())->find($id);
        if (empty($model)) {
            return $this->error('Not Found');
        }
        $model->gid = $request->gid;
        $model->description = $request->description;
        $model->availability_​​date = $request->availability_​​date;
        $model->google_product_category = $request->google_product_category;
        $model->product_type = $request->product_type;
        $model->brand = $request->brand;
        $model->gtin = $request->gtin;
        $model->MPN = $request->MPN;
        $model->condition = $request->condition;
        $model->adult = $request->adult;
        $model->age_group = $request->age_group;
        $model->color = $request->color;
        $model->gender = $request->gender;
        $model->material = $request->material;
        $model->pattern = $request->pattern;
        $model->size = $request->size;
        $model->size_type = $request->size_type;
        $model->item_group_id = $request->item_group_id;
        $model->promotion_id = $request->promotion_id;
        $model->custom_label_2 = $request->custom_label_2;
        $model->custom_label_3 = $request->custom_label_3;
        $model->custom_label_4 = $request->custom_label_4;

        try {
            DB::beginTransaction();
            $model->save();
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
        $model = ShoppingGProduct::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }

    public function enable($id)
    {
        $model = ShoppingGProduct::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->enable = true;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function disable($id)
    {
        $model = ShoppingGProduct::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->enable = false;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return $this->error('Không có file');
        }
        $file = $request->file('file');
        if ($file->getClientOriginalExtension() != 'xlsx') {
            return $this->error('Không đúng file excel');
        }
        $xlsx = [];

        $data = OfficeUtil::readXLSX($file->getRealPath(), 'data', 1, 'A', -1, '');
        if (empty($data)) {
            return $this->error('Không đúng định dạng file');
        }
        $columns = $data[0];
        $gProducts = [];
        for ($i = 1; $i < count($data); $i++) {
            $gProduct = [];
            for ($j = 0; $j < count($columns); $j++) {
                $gProduct[$columns[$j]] = $data[$i][$j];
            }
            array_push($gProducts, $gProduct);
        }

        $output = [];

        foreach ($gProducts as $g) {
            try {
                if (empty($g['id'])) {
                    throw new \Exception('ID để trống');
                }
                $model = ShoppingGProduct::find($g['id']);
                if (empty($model)) {
                    throw new \Exception('Đối tượng không tìm thấy');
                }
                $model->enable = $g['enable'] == 'TRUE';
                $model->gid = $g['gid'];
                $model->description = $g['description'];
                $model->availability_​​date = $g['availability_​​date'];
                $model->google_product_category = $g['google_product_category'];
                $model->product_type = $g['product_type'];
                $model->brand = $g['brand'];
                $model->gtin = $g['gtin'];
                $model->MPN = $g['MPN'];
                $model->condition = $g['condition'];
                $model->adult = $g['adult'];
                $model->age_group = $g['age_group'];
                $model->color = $g['color'];
                $model->gender = $g['gender'];
                $model->material = $g['material'];
                $model->pattern = $g['pattern'];
                $model->size = $g['size'];
                $model->size_type = $g['size_type'];
                $model->item_group_id = $g['item_group_id'];
                $model->promotion_id = $g['promotion_id'];
                $model->custom_label_2 = $g['custom_label_2'];
                $model->custom_label_3 = $g['custom_label_3'];
                $model->custom_label_4 = $g['custom_label_4'];
                $model->save();
            } catch (\Exception $e) {
                array_push($output, ['Lỗi dòng dữ liệu: ' . $e->getMessage()]);
                continue;
            }
            sleep(0.1);
        }

        $xlsx['new'] = $output;

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
        } catch
        (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return $this->error($e->getMessage());
        }

    }

    public function export()
    {
        $all = (new ShoppingGProduct())->get();
        $data = [];
        foreach ($all as $row) {
            array_push($data, $row->toArray());
        }

        $columns = array_keys($data[0]);
        $xlsx = [
            'data' => [$columns]
        ];

        foreach ($data as $d) {
            $row = [];
            foreach ($columns as $c) {
                array_push($row, $d[$c]);
            }
            array_push($xlsx['data'], $row);
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

    public function sync()
    {
        if (ShoppingUtil::generate_google_xml()) {
            return $this->success([]);
        } else {
            return $this->error('Hệ thống xảy ra lỗi');
        }
    }

}

