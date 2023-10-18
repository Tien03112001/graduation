<?php

namespace App\Http\Controllers\modules\inventory;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ExportingNote;
use App\Models\ExportingNoteDetail;
use App\Models\InventoryProduct;
use App\Utils\AuthUtil;
use App\Utils\CacheUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExportingNoteController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $search = $request->input('search');
        $sql = (new ExportingNote())
            ->when(isset($search), function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%');
            })
            ->with('details')
            ->orderBy('id', 'DESC');
        if ($limit) {
            $data = $sql->paginate($limit);
        } else {
            $data = $sql->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'details' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $data = $request->input('details');
        $lines = explode("\n", $data);

        try {
            DB::beginTransaction();
            $details = [];
            foreach ($lines as $index => $line) {
                $numRow = $index + 1;
                $values = preg_split('/[^\-\w]+/', $line, -1, PREG_SPLIT_NO_EMPTY);

                if (count($values) < 3) {
                    throw new \Exception(sprintf('Dòng thứ %d sai định dạng', $numRow));
                }

                if (!is_numeric($values[2])) {
                    throw new \Exception(sprintf('Dòng thứ %d, SỐ LƯỢNG sai định dạng', $numRow));
                }

                $productCode = $values[0];
                $size = $values[1];
                $quantity = intval($values[2]);

                $product = Product::where('code', $productCode)->first();
                if (empty($product)) {
                    throw new \Exception(sprintf('Dòng %d - Mã hàng %s chưa có trên web', $numRow, $productCode));
                }

                $inventoryProduct = InventoryProduct::where('product_code', $productCode)
                    ->where('size', $size)
                    ->first();

                if (!empty($inventoryProduct)) {
                    if ($inventoryProduct->quantity <= 0) {
                        throw new \Exception(sprintf('Dòng %d - Mã hàng %s không đủ số lượng', $numRow, $productCode));
                    }
                    $d = new ExportingNoteDetail();
                    $d->name = $productCode . '-' . $size;
                    $d->product_code = $productCode;
                    $d->size = $size;
                    $d->quantity = $quantity;
                    array_push($details, $d);
                } else {
                    throw new \Exception(sprintf('Dòng %d - Mã hàng %s chưa có số lượng', $numRow, $productCode));
                }

            }
            $note = new ExportingNote();
            $note->name = $request->name;
            $user = AuthUtil::getInstance()->getModel();
            $note->creator_id = $user->id;
            $note->creator_name = $user->name;
            $note->is_approved = false;
            $note->save();
            $note->details()->saveMany($details);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        $model = ExportingNote::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->delete();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function printBill($id)
    {
        $model = ExportingNote::with('details')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $settings = CacheUtil::getAppSetting();
        try {
            $src = view('exporting.default', compact('model', 'settings'))->render();
            $file_name = sprintf('print_html/phieu_xuat_kho_%d.html', now()->getTimestamp());
            file_put_contents(storage_path('app/public/' . $file_name), $src);
            $link = Storage::disk('public')->url($file_name);
            return $this->success(compact('link'));
        } catch (\Throwable $e) {
            return $this->error('Không thể sinh file');
        }
    }

    public function approve($id)
    {
        $model = ExportingNote::with('details')->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->is_approved = 1;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function disapprove($id)
    {
        $model = ExportingNote::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->is_approved = 2;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
