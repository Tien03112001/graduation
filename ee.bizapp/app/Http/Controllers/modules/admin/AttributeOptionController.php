<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use App\Models\ContentAttributeOption;
use Illuminate\Http\Request;

class AttributeOptionController extends Controller
{
    public function index(Request $request)
    {
        $attribute_id = $request->input('attribute_id');
        $data = ContentAttributeOption::where('content_attribute_id', $attribute_id)->get();
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $data = new ContentAttributeOption();
        $data->option = $request->input('option');
        $data->content_attribute_id = $request->input('content_attribute_id');
        $data->color_code = $request->input('color_code');
        $data->save();
        return $this->success($data);
    }

    public function show($id)
    {
        $data = ContentAttributeOption::where('content_attribute_id', $id)->get();
        return $this->success($data);
    }

    public function update($id, Request $request)
    {
        $data = (new ContentAttributeOption())->find($id);
        if (!$data) {
            return $this->error('Không tồn tại');
        }
        $data->option = $request->input('option');
        $data->color_code = $request->input('color_code');
        $data->save();
        return $this->success($data);
    }

    public function destroy($id)
    {
        $data = ContentAttributeOption::find($id);
        $data->delete();
        return $this->success($data);
    }
}
