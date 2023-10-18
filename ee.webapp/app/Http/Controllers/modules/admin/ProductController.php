<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Product;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductTagRepositoryInterface;
use App\Repository\ProductVariantRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProductController extends RestController
{
    protected $categoryRepository;
    protected $tagRepository;
    protected $variantRepository;
    protected $promotionRepository;
    public function __construct(ProductRepositoryInterface $repository,
                                ProductCategoryRepositoryInterface $categoryRepository,
                                ProductTagRepositoryInterface $tagRepository,
                                ProductVariantRepositoryInterface $variantRepository,
                                PromotionRepositoryInterface $promotionRepository)
    {
        parent::__construct($repository);
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->variantRepository = $variantRepository;
        $this->promotionRepository = $promotionRepository;
    }

    public function index(Request $request)
    {
        $searchCategoryId = $request->input('category_id');
        $search = $request->input('search');
        $published = $request->input('published');
        $tag_id = $request->input('tag_id');

        $whereClauses = [];
        if (isset($searchCategoryId)) {
            array_push($whereClauses, WhereClause::query('category_id', $searchCategoryId));
        }
        if (isset($search)) {
            array_push($whereClauses, WhereClause::queryLike('name', $search));
        }
        if (isset($published)) {
            array_push($whereClauses, WhereClause::query('published', $published));
        }
        if (isset($tag_id)) {
            array_push($whereClauses, WhereClause::queryRelationHas('tags', function ($que) use ($tag_id) {
                $que->where('tag_id', $tag_id);
            }));
        }
        $orderBy = $request->input('orderBy', 'id:desc,published:desc');

        return $this->indexDefault($request, $whereClauses, ['gallery','category', 'article', 'meta','variants','inventories'], [], $orderBy);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'category_id' => ['required', 'numeric'],
            'code' => 'required|max:255|unique:App\Models\Product,code',
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:App\Models\Product,slug',
            'image' => 'required|file',
            'price' => 'required|numeric',
            'published' => 'nullable|boolean',
            'summary' => 'nullable'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $categoryId = $request->input('category_id');
        $category = $this->categoryRepository->findById($categoryId);
        if (empty($category)) {
            return $this->errorClient('Danh mục không tìm thấy');
        }

        $attributes = $request->only([
            'category_id',
            'name',
            'code',
            'slug',
            'summary',
            'price',
            'published',
        ]);

        $attributes = array_merge($attributes, [
            'category_slug' => $category->slug,
            // 'image' => FileStorageUtil::getInstance()->putFile('images', $request->file('image')),
            'order' => 0,
        ]);
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = 'http://localhost:8000/storage/'.time() . '.' . $extenstion;

            $file->move('storage', $filename);
            $attributes['image'] = $filename;
        }

        $attributes['sku'] = $request->input('sku', '0');
        $attributes['brand'] = $request->input('brand', '0');

        $lastProduct = $this->repository->find([], 'order:desc');
        if (isset($lastProduct)) {
            $attributes['order'] = $lastProduct->order + 1;
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes, ['category']);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            FileStorageUtil::getInstance()->deleteFile($attributes['image']);
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateRequest($request, [
            'category_id' => 'nullable|numeric',
            'code' => ['nullable', 'max:255', Rule::unique('products', 'code')->ignore($id)],
            'name' => 'nullable|max:255',
            'slug' => ['nullable', 'max:255', Rule::unique('products', 'slug')->ignore($id)],
            'image' => 'nullable',
            'price' => 'nullable|numeric',
            'summary' => 'nullable'
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $attributes = [];

        if ($request->has('category_id')) {
            $categoryId = $request->input('category_id');
            $category = $this->categoryRepository->findById($categoryId);
            if (empty($category)) {
                return $this->errorClient('Danh mục không tìm thấy');
            }
            $attributes['category_id'] = $categoryId;
            $attributes['category_slug'] = $category->slug;
        }

        if ($request->has('code')) {
            $attributes['code'] = $request->code;
        }

        if ($request->has('name')) {
            $attributes['name'] = $request->name;
        }

        if ($request->has('slug')) {
            $attributes['slug'] = $request->slug;
        }

        if ($request->has('summary')) {
            $attributes['summary'] = $request->summary;
        }

        if ($request->has('price')) {
            $attributes['price'] = $request->price;
        }

        if ($request->has('published')) {
            if($request->published == 'true'){
                $attributes['published'] = '1';
            }
            if($request->published == 'false'){
                $attributes['published'] = '0';
            }
        }

        // if ($request->hasFile('image')) {
        //     $attributes['image'] = FileStorageUtil::getInstance()->putFile('images', $request->file('image'));
        // }
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = 'http://localhost:8000/storage/'.time() . '.' . $extenstion;

            $file->move('storage', $filename);
            $attributes['image'] = $filename;
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes, ['category']);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            if (isset($attributes['image'])) {
                FileStorageUtil::getInstance()->deleteFile($attributes['image']);
            }
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id,['inventories']);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        foreach ($model->inventories as $inventory) {
            if($inventory->quantity > 0) {
                return $this->errorClient("Không thể xóa sản phẩm do còn hàng");
            }
        }
        if (!($model instanceof Product)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $this->repository->delete($model, ['variants', 'inventories']);
            FileStorageUtil::getInstance()->deleteFile($model->image);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function enable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Sản phẩm không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id,
                ['published' => true]
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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Sản phẩm không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id,
                ['published' => false]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function up($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Sản phẩm không tồn tại');
        }

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<')]);

        if (empty($swapModel)) {
            return $this->errorClient('Không thể tăng thứ hạng');
        }
        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update($id,
                ['order' => $swapModel->order]
            );
            $swapModel = $this->repository->update($swapModel->id,
                ['order' => $order]
            );
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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Sản phẩm không tồn tại');
        }

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>')]);

        if (empty($swapModel)) {
            return $this->errorClient('Không thể giảm thứ hạng');
        }
        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update($id,
                ['order' => $swapModel->order]
            );
            $swapModel = $this->repository->update($swapModel->id,
                ['order' => $order]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function syncTags($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'tag_ids' => ['required'],
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $tagIds = array_map(function ($v) {
            return intval(trim($v));
        }, explode(',', $request->tag_ids));


        if ($this->repository instanceof ProductRepositoryInterface) {
            try {
                DB::beginTransaction();
                foreach ($tagIds as $tagId) {
                    if (empty($this->tagRepository->findById($tagId))) {
                        throw new \Exception("Thẻ sản phẩm $tagId không tồn tại");
                    }
                }
                $this->repository->syncTags($model, $tagIds);
                DB::commit();
                return $this->success($model->load('tags'));
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->errorClient($e->getMessage());
            }

        }

        return $this->error();

    }

    public function attachTags($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'tag_ids' => ['required'],
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $tagIds = array_map(function ($v) {
            return intval(trim($v));
        }, explode(',', $request->tag_ids));


        if ($this->repository instanceof ProductRepositoryInterface) {
            try {
                DB::beginTransaction();
                foreach ($tagIds as $tagId) {
                    if ($this->tagRepository->findById($tagId)) {
                        $this->repository->attachTag($model, $tagId);
                    } else {
                        throw new \Exception("Thẻ sản phẩm $tagId không tồn tại");
                    }
                }
                DB::commit();
                return $this->success($model->load('tags'));
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->errorClient($e->getMessage());
            }

        }

        return $this->error();

    }

    public function detachTags($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'tag_ids' => ['required'],
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $tagIds = array_map(function ($v) {
            return intval(trim($v));
        }, explode(',', $request->tag_ids));


        if ($this->repository instanceof ProductRepositoryInterface) {
            try {
                DB::beginTransaction();
                foreach ($tagIds as $tagId) {
                    if ($this->tagRepository->findById($tagId)) {
                        $this->repository->detachTag($model, $tagId);
                    } else {
                        throw new \Exception("Thẻ sản phẩm $tagId không tồn tại");
                    }
                }
                DB::commit();
                return $this->success($model->load('tags'));
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->errorClient($e->getMessage());
            }

        }
        return $this->error();
    }
    public function loadAvailableProducts($id, Request $request){

        $model = $this->promotionRepository->findById($id);
        $whereClauses=[];
        foreach ($model->products as $product) {
            if($product){
                array_push($whereClauses, WhereClause::queryDiff('id',$product->id));
            }
        }
        if(isset($request->param['search']))
        {
            if($request->param['search'] != "")
            {
                array_push($whereClauses, WhereClause::query('id',(int)$request->param['search']));
            }
        }
        $orderBy = $request->input('orderBy', 'id:desc');
        return $this->indexDefault($request, $whereClauses, ['gallery','category', 'article', 'meta','variants','inventories','promotions'], [], $orderBy);
    }

}
