<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Promotion;
use App\Repository\Eloquent\PromotionRepository;
use App\Repository\ProductRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PromotionController extends RestController
{
    protected $productRepository;

    public function __construct(PromotionRepositoryInterface $repository, ProductRepositoryInterface $productRepository)
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'expired_date_from' => 'nullable|date',
            'expired_date_to' => 'nullable|date',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');
        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }
        if ($request->has('promotion_id')) {
            array_push($clauses, WhereClause::queryLike('id', $request->promotion_id));
        }

        if ($request->has('enable')) {
            array_push($clauses, WhereClause::query('enable', $request->enable));
        }

        if ($request->has('expired_date_from')) {
            array_push($clauses, WhereClause::query('expired_date', $request->expired_date_from, '>='));
        }

        if ($request->has('expired_date_to')) {
            array_push($clauses, WhereClause::query('expired_date', $request->expired_date_to, '<='));
        }

        $with = ['products'];
        $withCount = [];

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
            'name' => 'required|max:255',
            'expired_date' => 'required|'
        ]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        if ($validator) {
            return $this->errorClient($validator);
        }
        if(date('d-m-y h:i:s', strtotime($request->expired_date)) < date('d-m-y h:i:s'))
        {
            return $this->errorClient('Thời gian hết hạn không đúng');
        }
        $attributes = $request->only([
            'name',
            'enable',
            'expired_date',
        ]);
        if($request->input('discount_value')!=null) {
            $attributes['discount_value']=$request->discount_value;
        }
        else{
            $attributes['discount_value']=0;
        }
        if($request->input('discount_percent')!=null) {
            $attributes['discount_percent']=$request->discount_percent;
        }
        else{
            $attributes['discount_percent']=0;
        }
        if($request->input('min_products_count')!=null) {
            $attributes['min_products_count']=$request->min_products_count;
        }
        else{
            $attributes['min_products_count']=0;
        }
        if($request->input('min_order_value')!=null) {
            $attributes['min_order_value']=$request->min_order_value;
        }
        else{
            $attributes['min_order_value']=0;
        }
        if($request->input('same_price')!=null) {
            $attributes['same_price']=$request->same_price;
        }
        else{
            $attributes['same_price']=0;
        }
        if($request->has('type')){
                $attributes['type']=$request->type;
        }

        try {
            DB::beginTransaction();
            if($this->repository->get([WhereClause::query('type',(int)$request->type),WhereClause::query('name',$request->name)],)->first()){
                return $this->errorClient('Chương trình khuyến mãi đã tồn tại');
            }
            $model = $this->repository->create($attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Promotion)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255',
            'expired_date' => 'nullable|date'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'name',
            'enable',
            'expired_date',
        ]);
        if($request->has('type')){
            $attributes['type']=$request->type;
        }
        if($request->type=="1"){
            $attributes['same_price']=0;
            $attributes['min_products_count']=0;
            $attributes['min_order_value']=0;
            $attributes['discount_value']=0;
            $attributes['discount_percent']=$request->discount_percent;
        }
        if($request->type=="2"){
            $attributes['discount_percent']=0;
            $attributes['discount_value']=0;
            $attributes['min_order_value']=0;
            $attributes['min_products_count']=$request->min_products_count;
            $attributes['same_price']=$request->same_price;
        }
        if($request->type=="3"){

            $attributes['discount_percent']=0;
            $attributes['discount_value']=0;
            $attributes['same_price']=0;
            $attributes['min_products_count']=$request->min_products_count;
            $attributes['min_order_value']=$request->min_order_value;

        }
        if($request->type=="4"){
            $attributes['discount_percent']=0;
            $attributes['min_products_count']=0;
            $attributes['same_price']=0;
            $attributes['discount_value']=$request->discount_value;
            $attributes['min_order_value']=$request->min_order_value;
        }
        if(date('d-m-y h:i:s', strtotime($request->expired_date)) < date('d-m-y h:i:s'))
        {
            return $this->errorClient('Thời gian hết hạn không đúng');
        }
        if($this->repository->get([WhereClause::query('type',(int)$request->type),WhereClause::query('name',$request->name),WhereClause::queryDiff('id', $model->id)])->first()){
            return $this->errorClient('Chương trình khuyến mãi đã tồn tại');
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            $this->syncPrice($model->id);
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
        $model=$this->repository->get([WhereClause::query('id',$id)],null,['products'],null)->first();
        foreach ($model->products as $product)
            {
                $this->repository->detachProduct($model, $product->id);
                $attributes['sale_price']=null;
                $this->productRepository->update($product->id,$attributes);


            }
        return $this->destroyDefault($id);
    }
    public function syncProducts($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'products_ids' => ['required'],
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $productIds = array_map(function ($v) {
            return intval(trim($v));
        }, explode(',', $request->products_ids));


        if ($this->repository instanceof PromotionRepositoryInterface) {
            try {
                DB::beginTransaction();
                foreach ($productIds as $productId) {
                    if (empty($this->productRepository->findById($productId))) {
                        throw new \Exception("Thẻ sản phẩm $productId không tồn tại");
                    }
                    else
                    {
                        if($model->enable==1 && $model->expired_date_to<today())
                        {
                            $product_price_change=$this->productRepository->findById($productId)->first();
                            $attributes['sale_price']=(int)($product_price_change->price-$model->discount_value)*((100-$model->discount_percent)/100);
                            $this->productRepository->update($productId, $attributes);
                        }
                    }
                }
                $this->repository->syncProducts($model, $productIds);

                DB::commit();
                return $this->success($model->load('products'));
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->errorClient($e->getMessage());
            }

        }

        return $this->error();

    }

    public function attachProducts($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Promotion)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'product_ids' => ['required'],
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $productIds = array_map(function ($v) {
            return intval(trim($v));
        }, explode(',', $request->product_ids));


        if ($this->repository instanceof PromotionRepositoryInterface) {
            try {
                DB::beginTransaction();
                foreach ($productIds as $productId) {
                    $product=$this->productRepository->findById($productId,'promotions');
                    if ($product) {
                        if(isset($product->promotions[0])){
                            $model_detach= $this->repository->findById($product->promotions[0]->id);
                            $this->repository->detachProduct($model_detach, $product->id);
                        }
                        if($model->type==1)
                        {
                            $attributes['sale_price']=$product->price-($product->price* $model->discount_percent)/100;
                            $this->productRepository->update($product->id,$attributes);
                        }
                        if($model->type==2)
                        {
                            $attributes['sale_price']=$model->same_price;
                            $this->productRepository->update($product->id,$attributes);
                        }

                        $this->repository->attachProduct($model, $productId);

                    } else {
                        throw new \Exception("Sản phẩm $productId không tồn tại");
                    }
                }
                DB::commit();
                return $this->success($model->load('products'));
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->errorClient($e->getMessage());
            }

        }

        return $this->error();

    }

    public function detachProducts($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Promotion)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'product_id' => ['required'],
        ]);
        if ($validator) {
            return $this->error($validator);
        }
        $productId=$request->product_id;


        if ($this->repository instanceof PromotionRepository) {
            try {
                $product=$this->productRepository->findById($productId);
                DB::beginTransaction();
                if($product) {
                    $attributes['sale_price']=null;
                    $this->productRepository->update($product->id,$attributes);
                    $this->repository->detachProduct($model, $productId);
                }
                DB::commit();
                return $this->success($model->load('products'));
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->errorClient($e->getMessage());
            }
        }

        return $this->error();

    }

    public function enable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $this->syncPrice($model->id);
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id,
                ['enable' => true]
            );
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
        $model=$this->repository->get([WhereClause::query('id',$id)],null,['products'],null)->first();
        if (empty($model)) {
            return $this->errorNotFound();
        }
        foreach ($model->products as $product)
        {
            $attributes['sale_price']=null;
            $this->productRepository->update($product->id,$attributes);
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id,
                ['enable' => false]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
    public function syncPrice($id){
        $model= $this->repository->findById($id,['products']);
        $attributes=[];
        foreach ($model->products as $product)
        {
            if($model->type==1)
            {
                $attributes['sale_price']=$product->price-($product->price* $model->discount_percent)/100;
                $this->productRepository->update($product->id,$attributes);
            }
            if($model->type==2)
            {
                $attributes['sale_price']=$model->same_price;
                $this->productRepository->update($product->id,$attributes);
            }
        }
    }
}
