<?php

namespace App\Http\Controllers\modules\admin;

use App\Models\Customer;
use App\Models\OpportunityNote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
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
        $sql = (new Customer())
            ->when($search, function ($q) use($search){
                return $q->where('name', 'like', '%'.$search.'%');
            });
        if($limit){
            $data = $sql->paginate($limit);
        }else{
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $model = new Customer();
            $model->name = $request->input('name');
            $model->phone = $request->input('phone');
            $model->email = $request->input('email');
            $model->facebook = $request->input('facebook');
            $model->zalo = $request->input('zalo');
            $model->province_id = $request->input('province_id');
            $model->address = $request->input('address');
            $model->save();
            DB::commit();
            $model->load(['province']);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $model = (new Customer())->find($id);
            $model->name = $request->input('name', $model->name);
            $model->phone = $request->input('phone', $model->phone);
            $model->email = $request->input('email', $model->email);
            $model->facebook = $request->input('facebook');
            $model->zalo = $request->input('zalo');
            $model->province_id = $request->input('province_id', $model->province_id);
            $model->address = $request->input('address', $model->address);
            $model->save();
            DB::commit();
            $model->load(['province']);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Customer::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $opportunities = $model->opportunities;
        foreach ($opportunities as $o) {
            OpportunityNote::where('opportunity_id', $o->id)->delete();
        }
        $model->opportunities()->delete();
        $model->delete();
        return $this->success($model);
    }

    public function check(Request $request)
    {
        $id = $request->input('id', null);
        $phone = $request->input('phone', null);
        $email = $request->input('email');
        $facebook = $request->input('facebook');
        $zalo = $request->input('zalo');
        $data = (new Customer())
            ->when($phone, function ($q) use ($phone) {
                return $q->orWhere('phone', $phone);
            })
            ->when($facebook, function ($q) use ($facebook) {
                return $q->orWhere('facebook', $facebook);
            })
            ->when($email, function ($q) use ($email) {
                return $q->orWhere('email', $email);
            })
            ->when($zalo, function ($q) use ($zalo) {
                return $q->orWhere('zalo', $zalo);
            })
            ->with(['province'])
            ->get();
        if (count($data) > 0) {
            if (isset($id)) {
                foreach ($data as $d) {
                    if ($d->id != $id) {
                        return $this->success(['exist' => true, 'data' => $d]);
                    }
                }
            }
            return $this->success(['exist' => true, 'data' => $data[0]]);
        }
        return $this->success(['exist' => false]);
    }
}
