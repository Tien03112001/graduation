<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Page;
use App\Models\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BlockController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'page_id' => 'required'
            ],
            [
                'page_id.required' => 'Page không được định danh'
            ]
        );
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = (new Block())
            ->where('page_id', $request->input('page_id'))
            ->when(isset($search), function (Builder $q) use ($search) {
                return $q->where('widget_name', 'like', '%' . $search . '%');
            })->orderBy('order', 'asc');
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'page_id' => 'required',
                'widget_id' => 'required'
            ],
            [
                'page_id.required' => 'Page không xác định',
                'widget_id.required' => 'Widget không xác định'
            ]
        );
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        $page = (new Page())->withCount('blocks')->find($request->input('page_id'));
        if (empty($page)) {
            return $this->error('Page không xác định');
        }

        $widget = (new Widget)->find($request->input('widget_id'));
        if (empty($page)) {
            return $this->error('Widget không xác định');
        }

        $model = new Block();
        $model->page_id = $request->input('page_id');
        $model->widget_id = $request->input('widget_id');
        $model->widget_name = $widget->name;
        $model->order = $page->blocks_count + 1;
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
        try {
            $model = (new Block())->find($id);
            if (empty($model)) {
                return $this->error('Not found');
            }
            $model->delete();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->success($model);
    }

    public function up($id)
    {
        $model = (new Block())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $swapModel = (new Block())
            ->where('order', '<', $model->order)
            ->wherePageId($model->page_id)
            ->orderBy('order', 'desc')
            ->first();
        if (empty($swapModel)) {
            return $this->error('Không thể tăng');
        }
        try {
            DB::beginTransaction();
            $modelOrder = $model->order;
            $swapModelOrder = $swapModel->order;
            list($modelOrder, $swapModelOrder) = array($swapModelOrder, $modelOrder);
            $model->order = $modelOrder;
            $swapModel->order = $swapModelOrder;
            $model->save();
            $swapModel->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function down($id)
    {
        $model = (new Block())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $swapModel = (new Block())
            ->where('order', '>', $model->order)
            ->wherePageId($model->page_id)
            ->orderBy('order', 'asc')
            ->first();
        if (empty($swapModel)) {
            return $this->error('Không thể giảm');
        }
        try {
            DB::beginTransaction();
            $modelOrder = $model->order;
            $swapModelOrder = $swapModel->order;
            list($modelOrder, $swapModelOrder) = array($swapModelOrder, $modelOrder);
            $model->order = $modelOrder;
            $swapModel->order = $swapModelOrder;
            $model->save();
            $swapModel->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
