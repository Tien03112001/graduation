<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use App\Models\ContentProduct;
use App\Models\TagProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductTagController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = TagProduct::when($search, function ($q) use ($search) {
            return $q->where('name', 'like', '%' . $search . '%');
        })->with(['meta'])->orderBy('order', 'DESC');
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        try {
            DB::beginTransaction();
            $model = new TagProduct();
            $model->name = $request->input('name');
            $model->slug = Str::slug($model->name);
            $model->order = 0;
            $model->summary = $request->input('summary');
            $model->save();
            DB::commit();
            $model->load(['meta', 'products']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        $model = TagProduct::with([
            'meta',
            'products' => function ($q) {
                $q->orderBy('pivot_order', 'ASC')->orderBy('id', 'DESC');
            }
        ])->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        return $this->success($model);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $model = (new TagProduct())->find($id);
            $model->name = $request->input('name', $model->name);
            $model->slug = Str::slug($model->name);
            $model->summary = $request->input('summary');
            $model->save();
            DB::commit();
            $model->load(['meta']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = TagProduct::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->products()->detach();
        $model->delete();
        return $this->success($model);
    }

    public function assign($id, Request $request)
    {
        $tag = $request->all();
        $model = ContentProduct::find($id);
        $tag_id = [];
        foreach ($tag as $value) {
            array_push($tag_id, $value['id']);
        }
        $model->tags()->detach();
        $model->tags()->attach($tag_id);
        $model->load('tags');
        return $this->success($model);
    }

    public function change_order(Request $request, $id)
    {
        $data = TagProduct::find($id);
        $data->order = $request->order;
        $data->save();
        return $this->success($data);
    }

    public function change_tag_order(Request $request, $id, $product_id)
    {
        $data = DB::table('product_tag')
            ->where('tag_id', $id)
            ->where('product_id', $product_id)
            ->update(['order' => $request->order]);
        return $this->success($data);
    }

    public function delete_product(Request $request, $id, $product_id)
    {
        $data = TagProduct::find($id);
        if (!$data) {
            return $this->error('Not found');
        }
        $data->products()->detach($product_id);
        return $this->success($data);
    }

    public function load_product(Request $request)
    {
        $search = $request->input('search');
        $data = ContentProduct::when($search, function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')->orWhere('code', 'like', '%' . $search . '%');
        })->get();
        foreach ($data as $item) {
            $item['full_name'] = $item['name'] . '-' . $item['code'];
        }
        return response()->json($data);
    }

    public function add_product(Request $request)
    {
        $tag_id = $request->input('tag_id');
        $products = $request->input('products');
        $tag = TagProduct::with('products')->find($tag_id);
        $productIds = [];
        foreach ($tag->products as $p) {
            array_push($productIds, $p->id);
        }
        $savedProductIds = array_unique(array_merge($products, $productIds));
        $tag->products()->sync($savedProductIds);
        return $this->success($tag);
    }

    public function delete_product_all($id)
    {
        $tag = TagProduct::find($id);
        if (empty($tag)) {
            return $this->error('Không tìm thấy đối tượng');
        }
        $tag->products()->detach();
        return $this->success([]);
    }

    public function change_order_product_all($id, Request $request)
    {

        $tag = TagProduct::find($id);
        if (empty($tag)) {
            return $this->error('Không tìm thấy đối tượng');
        }
        $value = intval($request->input('value', 0));
        if ($value > 0) {
            DB::table('product_tag')
                ->where('tag_id', $id)
                ->update([
                    'order' => DB::raw('`order` + ' . $value)
                ]);
        } else {
            $value = -$value;
            DB::table('product_tag')
                ->where('tag_id', $id)
                ->update([
                    'order' => DB::raw('`order` - ' . $value)
                ]);
        }
        return $this->success([]);
    }

}
