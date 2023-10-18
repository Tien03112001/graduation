<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Http\Controllers\RestController;
use App\Common\WhereClause;
use App\Utils\Logistics\GiaoHangTietKiemUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ShippingUnit;
use App\Models\ShippingStore;
use App\Models\ShippingService;
use App\Repository\ShippingUnitRepositoryInterface;
use App\Repository\ShippingStoreRepositoryInterface;
use App\Repository\ShippingServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ixudra\Curl\Facades\Curl;
use App\Utils\Logistics\GiaoHangNhanhUtil;

class ShippingUnitController extends RestController
{

    protected $shippingStore;
    protected $shippingService;
    public function __construct(ShippingUnitRepositoryInterface $repository,ShippingStoreRepositoryInterface $shippingStore,
    ShippingServiceRepositoryInterface $shippingService)
    {
        parent::__construct($repository);
        $this->shippingStore = $shippingStore;
        $this->shippingService = $shippingService;

    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['shipping_strores','shipping_services'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:desc');
        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }
        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
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
        $validator = $this->validateRequest($request, [
            'name' => ['required','max:255'],
            'username' => ['required','max:255'],
            'password' => ['required','max:255'],
            'token' => ['required'],
            'logo' => ['required'],
            'endpoint' => ['required'],
            'config' => ['required'],
            'class_name' => ['required'],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $name_test = $this->repository->get([WhereClause::query('name', $request->input('name'))],[],[],[])->first();
        if ($name_test) {
            return $this->errorClient('Đối tác đã tồn tại');
        }
        $attributes = $request->only([
            'name',
            'username',
            'password',
            'logo',
            'token',
            'endpoint',
            'config',
            'class_name',
        ]);

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
    public function createUnitPartner(Request $request)
    {
        $unit=$this->repository->find([WhereClause::query('name','Tự giao')]);
        $id=$unit->id;
        $validator = $this->validateRequest($request, [
            'store' => ['required','max:255'],
            'service' => ['required','max:255'],

        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
            $attributesStore['unit_id']=$id;
            $attributesStore['name']=$request->store;
            $attributesStore['partner_id']=rand(1,1000000);
            $attributesStore['data']=json_encode([]);
            $attributesStore['is_often']=1;

            $attributesService['unit_id']=$id;
            $attributesService['name']=$request->service;
            $attributesService['code']=rand(1,1000000);
            $attributesService['data']=json_encode([]);
            $attributesService['is_often']=1;
        try {
            DB::beginTransaction();
            $this->shippingStore->create($attributesStore);
            $this->shippingService->create($attributesService);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof ShippingUnit)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'name' => ['required','max:255'],
            'username' => ['required','max:255'],
            'password' => ['required','max:255'],
            'logo' => ['required'],
            'token' => ['required'],
            'endpoint' => ['required'],
            'config' => ['required'],
            'class_name' => ['required'],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }


        if ($request->has('name')) {
            $attributes['name'] = $request->name;
        }

        if ($request->has('username')) {
            $attributes['username'] = $request->username;
        }

        if ($request->has('password')) {
            $attributes['password'] = $request->password;
        }

        if ($request->has('token')) {
            $attributes['token'] = $request->token;
        }

        if ($request->has('endpoint')) {
            $attributes['endpoint'] = $request->endpoint;
        }
        if ($request->has('config')) {
            $attributes['config'] = $request->config;
        }
        if ($request->has('class_name')) {
            $attributes['class_name'] = $request->class_name;
        }
        if ($request->has('logo')) {
            $attributes['logo'] = $request->logo;
        }

        $name_test = $this->repository->get([WhereClause::query('name', $request->input('name')),WhereClause::queryDiff('id', $model->id)],[],[],[])->first();
        if ($name_test) {
            return $this->errorClient('Đối tác đã tồn tại');
        }
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
    {   $model=$this->repository->findById($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        else
        {
            $this->repository->delete($id,['shipping_strores','shipping_services']);
            return $this->success([]);
        }

    }

    public function synchronized($id)
    {

        $model = $this->repository->findById($id);
        try {
            if($model->endpoint=='https://online-gateway.ghn.vn/shiip/public-api')
            {
                $giaohangnhanhUtils=new GiaoHangNhanhUtil();
                $stores=$giaohangnhanhUtils->getStores();
                $services=$giaohangnhanhUtils->getServices();

                foreach ($stores as $key => $item)
                {
                    if(!$this->shippingStore->find([WhereClause::query('partner_id',$item['partner_id'])]))
                    {
                        $attributes['unit_id']=$item['unit_id'];
                        $attributes['name']=$item['name'];
                        $attributes['partner_id']=$item['partner_id'];
                        $attributes['data']=json_encode($item['data']);
                        $attributes['is_often']=$item['is_often'];
                        $this->shippingStore->create($attributes);
                    }
                }
                foreach ($services as $item)
                {
                    if(!$this->shippingService->find([WhereClause::query('code',$item['code'])]))
                    {
                        $attributes1['unit_id']=$item['unit_id'];
                        $attributes1['name']=$item['name'];
                        $attributes1['code']=$item['code'];
                        $attributes1['data']=json_encode($item['data']);
                        $attributes1['is_often']=$item['is_often'];
                        $this->shippingService->create($attributes1);
                    }
                }
                return $this->success([]);
            }
            if($model->endpoint=='https://services.giaohangtietkiem.vn')
            {
                $gttk=new GiaoHangTietKiemUtil();
                $storesTK=$gttk->getStores();
                $servicesTK=$gttk->getServices();
                foreach ($storesTK as $item)
                {
                    if(!$this->shippingStore->find([WhereClause::query('partner_id',$item['partner_id'])]))
                    {
                        $attributes3['unit_id']=$item['unit_id'];
                        $attributes3['name']=$item['name'];
                        $attributes3['partner_id']=$item['partner_id'];
                        $attributes3['data']=json_encode($item['data']);
                        $attributes3['is_often']=$item['is_often'];
                        $this->shippingStore->create($attributes3);
                    }
                }
                foreach ($servicesTK as $item)
                {
                    if(!$this->shippingService->find([WhereClause::query('code',$item['code'])]))
                    {
                        $attributes4['unit_id']=$item['unit_id'];
                        $attributes4['name']=$item['name'];
                        $attributes4['code']=$item['code'];
                        $attributes4['data']=json_encode($item['data']);
                        $attributes4['is_often']=$item['is_often'];
                        $this->shippingService->create($attributes4);

                    }
                }
                return $this->success([]);
            }

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorClient('Bạn đã đồng bộ trước đó ');
        }
}
}
