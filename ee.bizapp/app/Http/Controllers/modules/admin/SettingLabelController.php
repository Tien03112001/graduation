<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SettingLabel;
use Illuminate\Http\Request;

class SettingLabelController extends Controller implements ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $search = $request->input('search', null);
        $sql = (new SettingLabel())->when($search, function ($q) use ($search) {
            return $q->where('name', 'like', '%' . $search . '%');
        });
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
        $data = new SettingLabel();
        $data->name = $request->input('name');
        $data->title = $request->input('title');
        $data->summary = $request->input('summary');
        $data->save();
        return $this->success($data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $data = SettingLabel::find($id);
        $data->name = $request->input('name');
        $data->title = $request->input('title');
        $data->summary = $request->input('summary');
        $data->save();
        return $this->success($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = SettingLabel::find($id);
        if (!$data) {
            return $this->error('Khong co du lieu');
        }
        $data->delete();
        return $this->success($data);
    }

    public function delete($label_id, $tour_id)
    {
        $data = SettingLabel::find($label_id)->first();
        return $this->success($data);
    }
}
