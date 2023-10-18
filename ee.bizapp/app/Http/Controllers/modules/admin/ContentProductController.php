<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentGallery;
use App\Models\ContentPhoto;
use App\Models\ContentProduct;
use App\Models\ContentProductCategory;
use App\Models\InventoryProduct;
use App\Models\ProductCombo;
use App\Models\ProductInventory;
use App\Models\SeoMeta;
use App\Models\TagProduct;
use App\Utils\AuthUtil;
use App\Utils\FileUtil;
use App\Utils\HtmlUtil;
use App\Utils\ImageUtil;
use App\Utils\OfficeUtil;
use App\Utils\ShoppingUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContentProductController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $category_id = $request->input('category_id', null);
        $query = (new ContentProduct())
            ->when($search, function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . Str::slug($search, '%') . '%');
            })
            ->when($category_id, function ($q) use ($category_id) {
                return $q->where('category_id', $category_id);
            })
            ->with('meta', 'category', 'article', 'gallery', 'inventories')
            ->orderBy('order');
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $auth = AuthUtil::getInstance()->getModel();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'code' => 'required|max:255'
        ]);
        $code = $request->input('code');
        if (ContentProduct::where('code', $code)->exists()) {
            return $this->error('Mã sản phẩm trùng');
        }
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $name = $request->input('name');
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Str::slug($name . ' ' . $code);
        }

        $tag = $request->input('tag');
        $options = $request->input('options');
        if (ContentProduct::where('slug', $slug)->exists()) {
            return $this->error('Tên sản phẩm trùng');
        }
        $model = new ContentProduct();
        $model->slug = $slug;
        $model->name = $request->name;
        $summary = $request->input('summary');
        if ($summary) {
            $createdImages = [];
            $summary = HtmlUtil::minify($summary, $createdImages);
        }

        $model->summary = $summary;


        $model->published = $request->input('published', 1);
        $model->order = $request->input('order');
        $model->price = $request->input('price');
        $model->code = $request->input('code');
        $model->price_old = $request->input('price_old');
        $model->category_id = $request->input('category_id', 0);
        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            $model->image = $url;
            $model->alt = $request->input('alt', $model->name);
        }

        if ($request->hasFile('image2')) {
            $url2 = Storage::disk('public')->put('images', $request->file('image2'));
            if (empty($url2)) {
                return $this->error('Not Found File');
            }
            $model->image2 = $url2;
            $model->alt2 = $request->input('alt2', $model->name);
        }

        $meta = new SeoMeta();
        $meta->description = $request->input('meta_description', HtmlUtil::getSummaryContent($model->summary));
        $meta->keywords = $request->input('meta_keywords');
        $meta->robots = $request->input('meta_robots');
        $meta->canonical = $request->input('meta_canonical');
        $meta->more = $request->input('other_code');

        $gallery = new ContentGallery();
        $gallery->name = $model->code;
        $gallery->published = 1;
        try {
            DB::beginTransaction();
            $model->save();
            $model->meta()->save($meta);
            $model->gallery()->save($gallery);
            if ($tag) {
                $tags = json_decode($tag);
                $tag_id = [];
                foreach ($tags as $value) {
                    array_push($tag_id, $value->id);
                }

                $model->tags()->sync($tag_id);
            }
            $size = $request->input('size');
            if ($size) {
                $sizes = json_decode($size);
                $size_id = [];
                foreach ($sizes as $value) {
                    array_push($size_id, $value->id);
                }
                $model->options()->attach($size_id);
            }
            $collect = $request->input('collect');
            if ($collect) {
                $collects = json_decode($collect);
                $collect_id = [];
                foreach ($collects as $value) {
                    array_push($collect_id, $value->id);
                }
                $model->options()->attach($collect_id);
            }
            if ($options) {
                $options = json_decode($options);
                foreach ($options as $o) {
                    $model->options()->attach($o->optionss->id);
                    if ($o->optionss->image) {
                        $url = FileUtil::base64ToImage($o->optionss->image);
                        if (empty($url)) {
                            return $this->error('Not Found File');
                        }
                        $model->options()->updateExistingPivot($o->optionss->id, ['image' => $url]);
                    }
                }
            }
            DB::commit();
            $model->load('meta', 'category');
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        $data = ContentProduct::with(['category', 'labels', 'meta', 'article', 'options.attribute', 'tags'])->find($id);
        foreach ($data->options as $row) {
            if (isset($row->pivot->image)) {
                $row->pivot['full_path'] = Storage::disk('public')->url($row->pivot->image);
            }
        }
        return $this->success($data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $tag = $request->input('tag');
        $name = $request->input('name');
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Str::slug($name);
        }

        $summary = $request->input('summary');
        if ($summary) {
            $createdImages = [];
            $summary = HtmlUtil::minify($summary, $createdImages);
        }


        $model = (new ContentProduct())->with('meta')->find($id);
        $model->slug = $slug;
        $model->name = $request->name;
        $model->category_id = $request->input('category_id');
        $model->weight = $request->input('weight');
        $model->summary = $summary;
        $model->published = $request->input('published');
        $model->order = $request->input('order');
        $model->price = $request->input('price');
        $model->code = $request->input('code');
        $model->price_old = $request->input('price_old');
        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            FileUtil::removePublicFile($model->image);
            $model->image = $url;
            $model->alt = $request->input('alt', $model->name);
        }

        if ($request->hasFile('image2')) {
            $url = Storage::disk('public')->put('images', $request->file('image2'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            FileUtil::removePublicFile($model->image2);
            $model->image2 = $url;
            $model->alt2 = $request->input('alt2', $model->name);
        }
        if ($tag) {
            $tags = json_decode($tag);
            $tag_id = [];
            foreach ($tags as $value) {
                array_push($tag_id, $value->id);
            }
            $model->tags()->sync($tag_id);
        } else {
            $model->tags()->detach();
        }
        try {
            DB::beginTransaction();
            $model->save();
            $model->meta()->update(
                [
                    'keywords' => $request->input('meta_keyword'),
                    'description' => $request->input('meta_description'),
                    'canonical' => $request->input('meta_canonical'),
                    'robots' => $request->input('meta_robots')
                ]);
            DB::commit();
            $model->load('meta', 'category');
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = ContentProduct::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }

    public function enable($id)
    {
        $model = ContentProduct::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->published = true;
            $model->created_at = now()->toDateTimeString();
            $model->save();
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
        $model = ContentProduct::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->published = false;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function change_order(Request $request, $id)
    {
        $data = ContentProduct::find($id);
        $data->order = $request->order;
        $data->save();
        return $this->success($data);
    }

    public function delete_product(Request $request)
    {
        $item = $request->input('item');
        $products = [];
        for ($i = 0; $i < count($item); $i++) {
            $product = ContentProduct::find($item[$i]);
            if ($product) {
                $product->delete();
                array_push($products, $product->code);
            }
        }
        return $this->success(join(',', $products));
    }

    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return $this->error('Không có file');
        }
        $file = $request->file('file');
        if ($file->getClientOriginalExtension() != 'xlsx') {
            return $this->error('Không đúng định dạng file');
        }
        $xlsx = [];

        $newData = OfficeUtil::readXLSX($file->getRealPath(), 'new', 2, 'A', -1, 'M');
        if (!empty($newData)) {
            $output = [];

            $all = ContentProductCategory::all();
            $dict_categories = [];
            foreach ($all as $c) {
                $dict_categories[mb_strtolower($c->name)] = $c;
            }

            $all = TagProduct::all();
            $dict_tags = [];
            foreach ($all as $c) {
                $dict_tags[$c->name] = $c;
            }

            $all = self::getAllProducts();
            $dict_products = [];
            foreach ($all as $c) {
                $dict_products[$c->code] = $c;
            }

            foreach ($newData as $row) {
                try {
                    $codeValue = trim($row[0]);
                    if (empty($codeValue)) {
                        throw new \Exception('Mã để trống');
                    }
                    $nameValue = $row[1];
                    if (empty($nameValue)) {
                        throw new \Exception('Tên để trống');
                    }
                    $categoryValue = $row[2];
                    if (empty($categoryValue)) {
                        throw new \Exception('Danh mục để trống');
                    }
                    $imageValue = $row[3];
                    if (empty($imageValue) || !Str::startsWith($imageValue, 'http')) {
                        throw new \Exception('Hình ảnh để trống hoặc k đúng định dạng');
                    }
                    $priceValue = null;
                    if (!empty($row[4])) {
                        $priceValue = intval($row[4]);
                    }

                    $oldPriceValue = intval($row[5]);
                    $summaryValue = $row[6];
                    $tagValue = $row[7];
                    $sizeValue = $row[8];
                    if (empty($sizeValue)) {
                        throw new \Exception('Size để trống');
                    }
                    $photoValues = array_slice($row, 9);
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi dòng dữ liệu: ' . $e->getMessage());
                    array_push($output, $row);
                    continue;
                }

                if (!array_key_exists(mb_strtolower($categoryValue), $dict_categories)) {
                    array_push($row, 'Lỗi không tìm thấy danh mục');
                    array_push($output, $row);
                    continue;
                }

                $categoryId = $dict_categories[mb_strtolower($categoryValue)]->id;

                if (array_key_exists($codeValue, $dict_products)) {
                    array_push($row, 'Lỗi mã sản phẩm đã tồn tại');
                    array_push($output, $row);
                    continue;
                }
                try {
                    $image = ImageUtil::downloadFromFBUrl($imageValue);
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi download hình ảnh');
                    array_push($output, $row);
                    continue;
                }
                try {
                    DB::beginTransaction();
                    $product = new ContentProduct();
                    $product->code = $codeValue;
                    $product->name = $nameValue;
                    $product->slug = Str::slug($product->name . ' ' . $codeValue);
                    $product->image = $image;
                    $product->alt = $product->name;
                    $product->image2 = $image;
                    $product->alt2 = $product->name . ' ảnh 2';
                    $product->price = $priceValue;
                    $product->price_old = $oldPriceValue;
                    $product->summary = nl2br($summaryValue);
                    $product->category_id = $categoryId;

                    $tagIds = [];
                    $textTags = explode(',', $tagValue);
                    foreach ($textTags as $textTag) {
                        $textTag = trim($textTag);
                        if (empty($textTag)) {
                            continue;
                        }
                        if (array_key_exists($textTag, $dict_tags)) {
                            $tag = $dict_tags[$textTag];
                        } else {
                            $tag = new TagProduct();
                            $tag->name = $textTag;
                            $tag->slug = Str::slug($tag->name);
                            $tag->save();
                            $dict_tags[$tag->name] = $tag;
                        }
                        array_push($tagIds, $tag->id);
                    }

                    $inventories = [];
                    $textSizes = preg_split('/[\|\,\.\?\-\+\;]+/', $sizeValue, -1, PREG_SPLIT_NO_EMPTY);
                    foreach ($textSizes as $textSize) {
                        $inventory = new InventoryProduct();
                        $inventory->product_code = $codeValue;
                        $inventory->size = $textSize;
                        $inventory->code = $codeValue . '-' . $textSize;
                        $inventory->quantity = 0;
                        array_push($inventories, $inventory);
                    }

                    $gallery = new ContentGallery();
                    $gallery->name = $product->code . ' - ' . $product->name;
                    $gallery->published = 1;
                    $gallery->galleryable_type = 'products';

                    $photos = [];
                    foreach ($photoValues as $value) {
                        if (empty($value)) {
                            continue;
                        }
                        $photo = new ContentPhoto();
                        try {
                            if (Str::startsWith($value, config('app.url'))) {
                                $photo->image = $value;
                            } else {
                                $photo->image = ImageUtil::downloadFromFBUrl($value);
                            }
                            $photo->alt = 'Ảnh của' . $gallery->name;
                            array_push($photos, $photo);
                            sleep(1);
                        } catch (\Exception $e) {
                            continue;
                        }
                    }

                    if (count($photos) > 0) {
                        $image2 = array_shift($photos);
                        $product->image2 = $image2->image;
                    }

                    $product->save();
                    $gallery->galleryable_id = $product->id;
                    $gallery->save();
                    $gallery->images()->saveMany($photos);
                    $product->tags()->attach($tagIds);
                    $product->inventories()->saveMany($inventories);
                    array_push($output, $row);
                    DB::commit();
                    $dict_products[$product->code] = $product;
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi hệ thống: ' . $e->getMessage());
                    array_push($output, $row);
                    DB::rollBack();
                }
                sleep(0.5);
            }

            $xlsx['new'] = $output;
        }

        $updateData = OfficeUtil::readXLSX($file->getRealPath(), 'update', 2, 'A', -1, 'M');
        if (!empty($updateData)) {
            $output = [];

            $all = ContentProductCategory::all();
            $dict_categories = [];
            foreach ($all as $c) {
                $dict_categories[mb_strtolower($c->name)] = $c;
            }

            $all = TagProduct::all();
            $dict_tags = [];
            foreach ($all as $c) {
                $dict_tags[$c->name] = $c;
            }

            $all = self::getAllProducts();
            $dict_products = [];
            foreach ($all as $c) {
                $dict_products[$c->code] = $c;
            }

            foreach ($updateData as $row) {
                try {
                    $codeValue = trim($row[0]);
                    if (empty($codeValue)) {
                        throw new \Exception('Mã để trống');
                    }
                    $nameValue = $row[1];
                    if (empty($nameValue)) {
                        throw new \Exception('Tên để trống');
                    }
                    $categoryValue = $row[2];
                    if (empty($categoryValue)) {
                        throw new \Exception('Danh mục để trống');
                    }
                    $imageValue = $row[3];
                    if (empty($imageValue) || !Str::startsWith($imageValue, 'http')) {
                        throw new \Exception('Hình ảnh để trống hoặc k đúng định dạng');
                    }
                    $priceValue = null;
                    if (!empty($row[4])) {
                        $priceValue = intval($row[4]);
                    }
                    $oldPriceValue = intval($row[5]);
                    $summaryValue = $row[6];
                    $tagValue = $row[7];
                    $sizeValue = $row[8];
                    if (empty($sizeValue)) {
                        throw new \Exception('Size để trống');
                    }
                    $photoValues = array_slice($row, 9);
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi dòng dữ liệu: ' . $e->getMessage());
                    array_push($output, $row);
                    continue;
                }

                if (!array_key_exists($codeValue, $dict_products)) {
                    array_push($row, 'Lỗi mã sản phẩm không tồn tại');
                    array_push($output, $row);
                    continue;
                }
                $product = $dict_products[$codeValue];

                if (!array_key_exists(mb_strtolower($categoryValue), $dict_categories)) {
                    array_push($row, 'Lỗi không tìm thấy danh mục');
                    array_push($output, $row);
                    continue;
                }

                $categoryIds = [$dict_categories[mb_strtolower($categoryValue)]->id];

                try {
                    $image = ImageUtil::downloadFromFBUrl($imageValue);
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi download hình ảnh');
                    array_push($output, $row);
                    continue;
                }
                try {
                    DB::beginTransaction();
                    $product->name = $nameValue;
                    $product->slug = Str::slug($product->name);
                    $product->image = $image;
                    $product->alt = $product->name;
                    $product->image2 = $image;
                    $product->alt2 = $product->name . ' ảnh 2';
                    $product->price = $priceValue;
                    $product->price_old = $oldPriceValue;
                    $product->summary = nl2br($summaryValue);


                    $tagIds = [];
                    $textTags = explode(',', $tagValue);
                    foreach ($textTags as $textTag) {
                        $textTag = trim($textTag);
                        if (array_key_exists($textTag, $dict_tags)) {
                            $tag = $dict_tags[$textTag];
                        } else {
                            $tag = new TagProduct();
                            $tag->name = $textTag;
                            $tag->slug = Str::slug($tag->name);
                            $tag->save();
                            $dict_tags[$tag->name] = $tag;
                        }
                        array_push($tagIds, $tag->id);
                    }

                    $inventories = [];
                    $textSizes = preg_split('/[\W_]+/', $sizeValue);
                    foreach ($textSizes as $textSize) {
                        $index = array_search($textSize, $product->all_sizes);
                        if ($index == false) {
                            $inventory = new ProductInventory();
                            $inventory->warehouse_id = 1;
                            $inventory->color = 'Only';
                            $inventory->size = $textSize;
                            $inventory->quantity = 1;
                            array_push($inventories, $inventory);
                        }
                    }

                    if (empty($product->gallery)) {
                        $gallery = new ContentGallery();
                        $gallery->name = $product->code . ' - ' . $product->name;
                        $gallery->published = 1;
                        $gallery->galleryable_type = 'products';
                        $gallery->galleryable_id = $product->id;
                        $gallery->save();
                    } else {
                        $gallery = $product->gallery;
                    }

                    $photos = [];
                    foreach ($photoValues as $value) {
                        if (empty($value)) {
                            continue;
                        }
                        $photo = new ContentPhoto();
                        try {
                            $photo->image = ImageUtil::downloadFromFBUrl($value);
                            $photo->alt = 'Ảnh của' . $gallery->name;
                            array_push($photos, $photo);
                            sleep(1);
                        } catch (\Exception $e) {
                            continue;
                        }
                    }

                    $product->save();
                    $gallery->images()->saveMany($photos);
                    $product->category()->sync($categoryIds);
                    $product->tags()->sync($tagIds);
                    $product->inventories()->saveMany($inventories);
                    array_push($output, $row);
                    DB::commit();
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi hệ thống: ' . $e->getMessage());
                    array_push($output, $row);
                    DB::rollBack();
                }
            }

            $xlsx['update'] = $output;
        }

        $priceUpdateData = OfficeUtil::readXLSX($file->getRealPath(), 'price_update', 2, 'A', -1, 'C');
        if (!empty($priceUpdateData)) {
            $output = [];

            $all = $all = self::getAllProducts();
            $dict_products = [];
            foreach ($all as $c) {
                $dict_products[$c->code] = $c;
            }

            foreach ($priceUpdateData as $row) {
                try {
                    $codeValue = (string)$row[0];
                    if (empty($codeValue)) {
                        throw new \Exception('Mã để trống');
                    }
                    $priceValue = intval($row[1]);
                    $oldPriceValue = empty($row[2]) ? -1 : intval($row[2]);
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi dòng dữ liệu: ' . $e->getMessage());
                    array_push($output, $row);
                    continue;
                }

                if (!array_key_exists($codeValue, $dict_products)) {
                    array_push($row, 'Lỗi mã sản phẩm không tồn tại');
                    array_push($output, $row);
                    continue;
                }
                $product = $dict_products[$codeValue];

                try {
                    DB::beginTransaction();
                    $product->price = $priceValue;
                    if ($oldPriceValue > 0) {
                        $product->price_old = $oldPriceValue;
                    }
                    $product->published = 1;
                    $product->save();
                    array_push($output, $row);
                    DB::commit();
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi hệ thống: ' . $e->getMessage());
                    array_push($output, $row);
                    DB::rollBack();
                }
            }

            $xlsx['price_update'] = $output;
        }

        $tagUpdateData = OfficeUtil::readXLSX($file->getRealPath(), 'tag_update', 2, 'A', -1, 'M');
        if (!empty($tagUpdateData)) {
            $output = [];

            $all = TagProduct::all();
            $dict_tags = [];
            foreach ($all as $c) {
                $dict_tags[$c->slug] = $c;
            }

            $all = self::getAllProducts();
            $dict_products = [];
            foreach ($all as $c) {
                $dict_products[$c->code] = $c;
            }

            $order = 0;
            foreach ($tagUpdateData as $row) {
                try {
                    $codeValue = (string)$row[0];
                    if (empty($codeValue)) {
                        throw new \Exception('Mã để trống');
                    }
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi dòng dữ liệu: ' . $e->getMessage());
                    array_push($output, $row);
                    continue;
                }

                if (!array_key_exists($codeValue, $dict_products)) {
                    array_push($row, 'Lỗi mã sản phẩm không tồn tại');
                    array_push($output, $row);
                    continue;
                }
                $product = $dict_products[$codeValue];

                try {
                    DB::beginTransaction();
                    $tagIds = [];
                    foreach (array_slice($row, 1) as $t) {
                        if (empty($t)) {
                            continue;
                        }
                        $slug = Str::slug($t);
                        if (array_key_exists($slug, $dict_tags)) {
                            $tag = $dict_tags[$slug];
                        } else {
                            $tag = new TagProduct();
                            $tag->name = $t;
                            $tag->slug = $slug;
                            $tag->save();
                            $dict_tags[$slug] = $tag;
                        }
                        $tagIds[$tag->id] = $order;
                    }
                    $product->tags()->syncWithoutDetaching($tagIds);
                    array_push($output, $row);
                    DB::commit();
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi hệ thống: ' . $e->getMessage());
                    array_push($output, $row);
                    DB::rollBack();
                }
                $order++;
            }

            $xlsx['tag_update'] = $output;
        }

        $stockUpdateData = OfficeUtil::readXLSX($file->getRealPath(), 'stock_update', 2, 'A', -1, 'C');
        if (!empty($stockUpdateData)) {
            $output = [];

            $all = self::getAllProducts();
            $dict_products = [];
            foreach ($all as $c) {
                $dict_products[$c->code] = $c;
            }

            foreach ($stockUpdateData as $row) {
                try {
                    $codeValue = (string)$row[0];
                    if (empty($codeValue)) {
                        throw new \Exception('Mã để trống');
                    }
                    $textSize = $row[1];
                    if (empty($textSize)) {
                        throw new \Exception('Size để trống');
                    }
                    $quantity = intval($row[2]);
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi dòng dữ liệu: ' . $e->getMessage());
                    array_push($output, $row);
                    continue;
                }


                if (!array_key_exists($codeValue, $dict_products)) {
                    array_push($row, 'Lỗi mã sản phẩm không tồn tại');
                    array_push($output, $row);
                    continue;
                }
                $product = $dict_products[$codeValue];
                $sizes = preg_split('/[\|\,\.\?\-\+\;]+/', $textSize, -1, PREG_SPLIT_NO_EMPTY);

                foreach ($sizes as $size) {
                    $cloneRow = $row;
                    $cloneRow[1] = $size;
                    try {
                        DB::beginTransaction();
                        $inventory = ProductInventory::whereProductId($product->id)->whereSize($size)->first();

                        if ($inventory) {
                            if ($quantity > 0) {
                                $inventory->quantity = $quantity;
                                $inventory->save();
                            } else {
                                $inventory->delete();
                            }
                        } else {
                            if ($quantity > 0) {
                                ProductInventory::create([
                                    'product_id' => $product->id,
                                    'warehouse_id' => 1,
                                    'color' => 'Only',
                                    'size' => $size,
                                    'quantity' => $quantity
                                ]);
                            }
                        }
                        array_push($output, $cloneRow);
                        DB::commit();
                    } catch (\Exception $e) {
                        array_push($cloneRow, 'Lỗi hệ thống: ' . $e->getMessage());
                        array_push($output, $cloneRow);
                        DB::rollBack();
                    }
                }
            }

            $xlsx['stock_update'] = $output;
        }

        $comboUpdateData = OfficeUtil::readXLSX($file->getRealPath(), 'combo_update', 2, 'A', -1, 'C');
        if (!empty($comboUpdateData)) {
            $output = [];

            $all = self::getAllProducts();
            $dict_products = [];
            foreach ($all as $c) {
                $dict_products[$c->code] = $c;
            }

            $all = ProductCombo::all();
            $dict_combos = [];
            foreach ($all as $c) {
                $dict_combos[$c->name] = $c;
            }

            foreach ($comboUpdateData as $row) {
                try {
                    $codeValue = (string)$row[0];
                    if (empty($codeValue)) {
                        throw new \Exception('Mã để trống');
                    }
                    $textCombo = $row[1];
                    if (empty($textCombo)) {
                        throw new \Exception('Combo để trống');
                    }
                    if (!array_key_exists($codeValue, $dict_products)) {
                        throw new \Exception('Lỗi mã sản phẩm không tồn tại');
                    }
                } catch (\Exception $e) {
                    array_push($row, 'Lỗi dòng dữ liệu: ' . $e->getMessage());
                    array_push($output, $row);
                    continue;
                }

                $product = $dict_products[$codeValue];

                $combos = preg_split('/[\|\,\.\;]+/', $textCombo, -1, PREG_SPLIT_NO_EMPTY);

                $comboIds = [];

                $rows = [];
                foreach ($combos as $combo) {
                    $cloneRow = $row;
                    $cloneRow[1] = $combo;
                    if (!array_key_exists($combo, $dict_combos)) {
                        array_push($cloneRow, 'Lỗi combo không tồn tại');
                        continue;
                    } else {
                        array_push($comboIds, $dict_combos[$combo]->id);
                    }
                    array_push($rows, $cloneRow);
                }

                try {
                    DB::beginTransaction();
                    $existIds = array_map(function ($x) {
                        return $x['id'];
                    }, $product->combos->toArray());
                    $diffIds = array_diff($comboIds, $existIds);
                    $product->combos()->attach($diffIds);
                    DB::commit();
                    $output = array_merge($output, $rows);
                } catch (\Exception $e) {
                    foreach ($rows as $cloneRow) {
                        array_push($cloneRow, 'Lỗi hệ thống: ' . $e->getMessage());
                        array_push($output, $cloneRow);
                    }
                    DB::rollBack();
                }
            }

            $xlsx['combo_update'] = $output;
        }

        try {
            $writer = OfficeUtil::writeXLSX($xlsx);
            $response = new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="ket_qua_' . time() . '.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
            return $response;
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return $this->error($e->getMessage());
        }

    }

    public static function getAllProducts()
    {
        $all = [];
        $offset = 0;
        $limit = 100;
        while (True) {
            $products = ContentProduct::offset($offset)->limit($limit)->get();
            if (count($products) == 0) {
                break;
            }
            array_push($all, ...$products);
            $offset += $limit;
        }
        return $all;
    }

    public function syncXml()
    {
        if (ShoppingUtil::generate_google_xml() && ShoppingUtil::generate_facebook_xml()) {
            return $this->success([]);
        } else {
            return $this->error('Lỗi sinh file');
        }
    }
}

