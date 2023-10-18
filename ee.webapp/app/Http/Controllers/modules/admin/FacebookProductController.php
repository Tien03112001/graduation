<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\FacebookProduct;
use App\Repository\FacebookProductRepositoryInterface;
use App\Repository\InventoryProductRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use App\Common\Enum\StatusEnum;

class FacebookProductController extends RestController
{
    protected $productRepository;
    protected $inventoryRepository;

    public function __construct(FacebookProductRepositoryInterface $repository, ProductRepositoryInterface $productRepository, InventoryProductRepositoryInterface $inventoryRepository)
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
        $this->inventoryRepository = $inventoryRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['category'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('product_id', $request->search));
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
        $facebook = $this->repository->getAll();
        $products = $this->productRepository->get([], 'id:desc', ['variants', 'category', 'tags', 'promotions', 'article', 'gallery_images'], ['variants', 'gallery_images', 'article']);

        $arrProduct = [];
        $arrVariant = [];
        foreach ($products as $product) {
            //Kiểm tra có biến thể không
            if ($product->variants) {
                foreach ($product->variants as $v) {
                    $inventory = $this->inventoryRepository->find([WhereClause::query('product_id', $product->id), WhereClause::query('variant_id', $v->id)]);
                    $code = $product->code.$inventory->code;
                    array_push($arrVariant, $code);
                }
            } else {
                array_push($arrProduct, $product->code);
            }
        }

        foreach ($facebook as $f) {
            if ($f->item_group_id != null) {
                $check = in_array($f->fid, $arrVariant);
                if (!$check) {
                    $this->repository->delete($f->id);
                }
            } else {
                $check = in_array($f->product_id, $arrProduct);
                if (!$check) {
                    $this->repository->delete($f->id);
                }
            }
        }

        foreach ($products as $product) {
            $attributes['product_id'] = $product->code;
            $attributes['title'] = $product->name;
            $attributes['description'] = $product->summary;
            $attributes['availability'] = $product->availability;
            $attributes['condition'] = $product->item_condition;
            $attributes['price'] = $product->price;
            $attributes['link'] = $product->full_path;
            $attributes['image_link'] = $product->image;
            $attributes['brand'] = $product->brand;
            $attributes['sale_price'] = $product->sale_price;
            // Trạng thái
            if ($product->published == 1) {
                $attributes['status'] = 'active';
            }
            if ($product->published == 0) {
                $attributes['status'] = 'archived';
            }
            // Có khuyến mãi
            if (isset($product->promotions[0])) {
                $attributes['sale_price_effective_date'] = $product->promotions[0]->expired_date;
            } else {
                $attributes['sale_price_effective_date'] = null;
            }
            //Có bài viết
            if ($product->article_count > 0) {
                $attributes['rich_text_description'] = $product->article->content;
            } else {
                $attributes['rich_text_description'] = null;
            }
            // Có list ảnh
            if ($product->gallery_images_count > 0) {
                $images = null;
                foreach ($product->gallery_images as $key => $image) {
                    $images = $images . ' ' . $image->path;
                    if($key == 19){
                        break;
                    };
                }
                $attributes['additional_image_link'] = trim($images);
            } else {
                $attributes['additional_image_link'] = null;
            }
            $attributes['custom_label_0'] = $product->category->name;
            //Có hẻ tag
            if (isset($product->tags[0])) {
                $attributes['custom_label_1'] = $product->tags[0]->name;
            } else {
                $attributes['custom_label_1'] = null;
            }
            if (isset($product->tags[1])) {
                $attributes['custom_label_2'] = $product->tags[1]->name;
            } else {
                $attributes['custom_label_2'] = null;
            }
            if (isset($product->tags[2])) {
                $attributes['custom_label_3'] = $product->tags[2]->name;
            } else {
                $attributes['custom_label_3'] = null;
            }
            if (isset($product->tags[3])) {
                $attributes['custom_label_4'] = $product->tags[3]->name;
            } else {
                $attributes['custom_label_4'] = null;
            }

            // Có biến thể
            if ($product->variants_count > 0) {
                foreach ($product->variants as $variant) {
                    $inventory = $this->inventoryRepository->find([WhereClause::query('product_id', $product->id), WhereClause::query('variant_id', $variant->id)]);
                    $code = $product->code.$inventory->code;
                    $facebook_product = $this->repository->find([WhereClause::query('product_id', $product->code), WhereClause::query('fid', $code)]);
                    $attributes['fid'] = $code;
                    $attributes['quantity_to_sell_on_facebook'] = $inventory->quantity;
                    $attributes['size'] = $variant->name;
                    $attributes['item_group_id'] = $product->code;
                    if ($facebook_product) {
                        try {
                            DB::beginTransaction();
                            $model = $this->repository->update($facebook_product->id, $attributes);
                            DB::commit();
                        } catch (\Exception $e) {
                            Log::error($e);
                            DB::rollBack();
                            return $this->error($e->getMessage());
                        }
                    } else {
                        try {
                            $attributes['enable'] = false;
                            DB::beginTransaction();
                            $model = $this->repository->create($attributes);
                            DB::commit();
                        } catch (\Exception $e) {
                            Log::error($e);
                            DB::rollBack();
                            return $this->error($e->getMessage());
                        }
                    }
                }
            } else {
                $inventory = $this->inventoryRepository->find([WhereClause::query('product_id', $product->id), WhereClause::query('variant_id', '0')]);
                if ($inventory) {
                    $code = $product->code.$inventory->code;
                    $facebook_product = $this->repository->find([WhereClause::query('product_id', $product->code), WhereClause::query('fid', $code)]);
                    $attributes['quantity_to_sell_on_facebook'] = $inventory->quantity;
                } else {
                    $facebook_product = $this->repository->find([WhereClause::query('product_id', $product->code)]);
                    $attributes['quantity_to_sell_on_facebook'] = null;
                }
                $attributes['fid'] = $product->code;
                $attributes['size'] = null;
                $attributes['item_group_id'] = null;
                if ($facebook_product) {
                    try {
                        DB::beginTransaction();
                        $model = $this->repository->update($facebook_product->id, $attributes);
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                        return $this->error($e->getMessage());
                    }
                } else {
                    try {
                        $attributes['enable'] = false;
                        DB::beginTransaction();
                        $model = $this->repository->create($attributes);
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                        return $this->error($e->getMessage());
                    }
                }
            }
        }
        return $this->success($model);
    }

    public function update(Request $request, $id)
    {
        $order = $this->repository->findById($id);

        if (empty($order)) {
            return $this->error('Đơn hàng không tồn tại');
        }

        $validator = Validator::make($request->all(), [
            'fb_product_category' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $attributes['fb_product_category'] = $request->fb_product_category;
        $attributes['color'] = $request->color;
        $attributes['gender'] = $request->gender;
        $attributes['age_group'] = $request->age_group;
        $attributes['material'] = $request->material;
        $attributes['pattern'] = $request->pattern;
        $attributes['height'] = $request->height;
        $attributes['length'] = $request->length;
        $attributes['width'] = $request->width;
        $attributes['finish'] = $request->finish;
        $attributes['volume'] = $request->volume;
        $attributes['scent'] = $request->scent;
        $attributes['decor_style'] = $request->decor_style;
        $attributes['gemstone'] = $request->gemstone;
        $attributes['ingredients'] = $request->ingredients;
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
    {
        // TODO: Implement destroy() method.
    }

    public function enable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Sản phẩm không tồn tại');
        }
        if ($model->fb_product_category == null) {
            return $this->errorClient('Sản phẩm chưa có danh mục');
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update(
                $id,
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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Sản phẩm không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update(
                $id,
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

    public function xmlFacebookCatalog()
    {
        $rows = [];

        $repository = App::make(FacebookProductRepositoryInterface::class);
        if ($repository instanceof FacebookProductRepositoryInterface) {
            $models = $repository->get([WhereClause::query('enable', StatusEnum::ACTIVE)], 'id:asc');
            foreach ($models as $model) {
                if ($model instanceof FacebookProduct) {
                    $product = $model->toArray();
                    unset($product['product_id']);
                    unset($product['enable']);
                    unset($product['fid']);
                    $product['id'] = $model->fid;
                    foreach ($product as $key => $value) {
                        if (empty($value)) {
                            unset($product[$key]);
                        }
                    }
                    array_push($rows, $product);
                }
            }
        }

        file_put_contents(public_path('xml/facebook_catalog.xml'), view('templates.facebook_catalog', compact('rows'))->render());
        return $this->success(env('APP_URL').'/xml/facebook_catalog.xml');
    }
}
