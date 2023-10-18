<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentAttribute;
use App\Models\ContentAttributeOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContentAttributeController extends Controller implements ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $sql = (new ContentAttribute())
            ->when($search, function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%');
            })->with('options');
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $model = new ContentAttribute();
        $model->name = $request->input('name');
        $model->code = $request->input('code');
        $options = [];
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

    public function storeOption(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $model = new ContentAttributeOption();
        $model->option = $request->input('option');
        $model->content_attribute_id = $id;
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ContentAttribute::where('id', $id)->with('options')->first();
        return $this->success($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = ContentAttribute::find($id);
        $model->name = $request->input('name');
        $model->code = $request->input('code');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = ContentAttribute::find($id);
        $model->options()->delete();
        $model->delete();
        return $this->success('success');
    }

    public function show_option($id, Request $request){
        $limit = $request->input('limit');
        $data = ContentAttributeOption::where('content_attribute_id', $id);
        if ($limit){
            $data = $data->paginate($limit);
        }else {
            $data = $data->get();
        }
        return $this->success($data);
    }
}
