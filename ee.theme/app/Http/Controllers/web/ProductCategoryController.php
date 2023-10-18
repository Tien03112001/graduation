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
use App\Models\ProductVariant;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductVariantRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use App\Utils\HtmlUtil;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ProductCategoryController extends Controller
{
    protected $productRepository;
    protected $categoryRepository;
    protected $promotionRepository;

    public function __construct(ProductRepositoryInterface $productRepository, ProductCategoryRepositoryInterface $categoryRepository,
    PromotionRepositoryInterface $promotionRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->promotionRepository = $promotionRepository;
    }

    public function detail(Request $request, $slug)
    {

        $category = ProductCategory::whereSlug($slug)
            ->with([
                'meta', 'products'
            ])
            ->first();

        $limit = $request->input('limit', 12);
        $clauses = [WhereClause::query('published', 1)];
        $orderBy = $request->input('orderBy', 'id:desc');
        $with = ['category', 'variants'];
        $withCount = [];
        if ($slug) {
            array_push($clauses, WhereClause::query('category_id', $category->id));
        }
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
        $clauseVariant = [];
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
        $productCategories = $this->categoryRepository->getAll();

        $page = new SEOPage($category->name, $category->meta);
        $page->pushStructure(\App\Utils\StructureDataUtil::getInstance()->getBreadcrumbOfProductCategory($category));

        FacebookConversionJob::dispatch(
            FacebookConversionEvent::createProductCategoryEvent(
                $category,
                $pagination->items(),
                $request
            )
        );

        $view = view('theme.product.category', compact('category', 'pagination', 'page', 'productCategories'));
        return HtmlUtil::optimize($view);
    }

    public function promotion_products(Request $request)
    {
        $promotion=$this->promotionRepository->findById($request->promotion_id)->first();
        $promotion_id=$promotion->id;

        $limit = $request->input('limit', '12');
        $clauses = [WhereClause::query('published', 1)];
        $orderBy = $request->input('orderBy', 'id:desc');
        $with = ['category', 'variants'];
        $withCount = [];
        // if($slug)
        // {
        //     array_push($clauses, WhereClause::query('category_id', $category->id));
        // }
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
        if ($request->has('promotion')) {
            if ($request->input('promotion') != null) {
                array_push($clauses, WhereClause::query('price', $request->promotion, '>='));
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
        if (!empty($promotion)) {

            array_push($clauses, WhereClause::queryRelationHas('promotions', function ($q) use ($promotion_id) {
                $q->where('id', $promotion_id);
            }));
        }
        $pagination = $this->productRepository->paginate($limit, $clauses, $orderBy, $with, $withCount);

        $productCategories = $this->categoryRepository->getAll();
        $page = new SEOPage($promotion->name, $promotion->meta);
//        $page->pushStructure(\App\Utils\StructureDataUtil::getInstance()->getBreadcrumbOfProductCategory($category));
        $view = view('theme.product.promotion_products', compact('pagination', 'productCategories','page'));
        return HtmlUtil::optimize($view);
    }
}
