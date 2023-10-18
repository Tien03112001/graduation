<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Common\WhereClause;
use App\Http\Controllers\Controller;
use App\Jobs\FacebookConversionJob;
use App\Models\MetaData;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductVariantRepositoryInterface;
use App\Utils\HtmlUtil;
use App\Utils\StructureDataUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ProductController extends Controller
{
    protected $productRepository;
    protected $categoryRepository;
    protected $varientRepository;

    public function __construct(ProductVariantRepositoryInterface $varientRepository, ProductRepositoryInterface $productRepository, ProductCategoryRepositoryInterface $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->varientRepository = $varientRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', '9');

        $clauses = [WhereClause::query('published', 1)];
        $orderBy = $request->input('orderBy', 'id:desc');
        $with = ['category', 'variants'];
        $withCount = [];
        if ($request->has('availability')) {
            array_push($clauses, WhereClause::query('availability', $request->availability));
        }
        if ($request->has('sort_by')) {
            if ($request->sort_by == 'manual') {
                $orderBy = $request->input('orderBy', 'id:desc');
            }
            if ($request->sort_by == 'asc') {
                $orderBy = $request->input('orderBy', 'price:asc');
            }
            if ($request->sort_by == 'desc') {
                $orderBy = $request->input('orderBy', 'price:desc');
            }
        }
        if ($request->has('start_price')) {
            if ($request->input('start_price') != null) {
                array_push($clauses, WhereClause::query('price', $request->start_price, '>='));
            }
        }
        if ($request->has('end_price')) {
            if ($request->input('end_price') != null) {
                array_push($clauses, WhereClause::query('price', $request->end_price, '<='));
            }
        }
        $sizeFind = [];
        if ($request->has('SizeS')) {
            array_push($sizeFind, "S");
        }
        if ($request->has('SizeM')) {
            array_push($sizeFind, "M");
        }
        if ($request->has('SizeL')) {
            array_push($sizeFind, "L");
        }
        if ($request->has('SizeXL')) {
            array_push($sizeFind, "XL");
        }
        if ($request->has('SizeXXL')) {
            array_push($sizeFind, "XXL");
        }

        if (!empty($sizeFind)) {
            foreach ($sizeFind as $size) {
                array_push($clauses, WhereClause::queryRelationHas('variants', function ($q) use ($size) {
                    $q->where('name', $size);
                }));
            }
        }
        $pagination = $this->productRepository->paginate($limit, $clauses, $orderBy, $with, $withCount);

        $category = new ProductCategory(['name' => 'Tất cả sản phẩm']);
        $page = new SEOPage($category->name, new MetaData(['canonical' => URL::current()]), []);
        $page->pushStructure(\App\Utils\StructureDataUtil::getInstance()->getBreadcrumbOfProductCategory($category));

        FacebookConversionJob::dispatch(
            FacebookConversionEvent::createProductCategoryEvent(
                $category,
                $pagination->items(),
                $request
            )
        );

        $productCategories = $this->categoryRepository->getAll();

        $view = view('theme.product.all', compact('pagination', 'page', 'productCategories'));
        return HtmlUtil::optimize($view);
    }

    public function detail($categorySlug, $slug, Request $request)
    {
        //Sản phẩm xem
        $product = (new Product())
            ->whereSlug($slug)
            ->whereCategorySlug($categorySlug)
            ->wherePublished(1)
            ->with('article', 'tags', 'meta', 'category', 'additional_attributes', 'public_comments', 'public_gallery_images', 'related_products.relation')
            ->first();
        if (empty($product)) {
            abort(404);
        }
        $varientsProduct = $this->varientRepository->get([WhereClause::query('product_id', $product->id)], 'id:asc', ['inventory_products'], []);
        // Sản phẩm liên quan cùng loại
        if ($product) {
            $related_products = (new Product())
                ->wherePublished(1)
                ->where('category_id', $product->category_id)
                ->where('id', '<>', $product->id)
                ->orderBy('id', 'DESC')
                ->with('article', 'tags', 'meta', 'category')
                ->limit(4)
                ->get();
        } else {
            $related_products = [];
        }

        $page = new SEOPage($product->name, $product->meta, $product->structured_datas);
        $page->pushStructure(StructureDataUtil::getInstance()->getBreadcrumbOfProduct($product));

        FacebookConversionJob::dispatch(FacebookConversionEvent::createProductViewEvent($product, $request));


        return HtmlUtil::optimize(
            view('theme.product.detail', compact('product', 'related_products', 'page', 'varientsProduct'))
        );
    }
}
