<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Http\Controllers\Controller;
use App\Jobs\FacebookConversionJob;
use App\Models\MetaData;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Utils\HtmlUtil;
use App\Utils\StructureDataUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Common\WhereClause;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductCategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;

class ProductSearchController extends Controller
{
    protected $productRepository;
    protected $categoryRepository;

    public function __construct(ProductRepositoryInterface $productRepository, ProductCategoryRepositoryInterface $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return Redirect::back();
        }

        $limit = $request->input('limit', '12');
        $clauses = [WhereClause::query('published', 1)];
        $orderBy = $request->input('orderBy', 'id:desc');
        $with = ['category', 'variants'];
        $withCount = [];
        if ($request->has('availability')) {
            array_push($clauses, WhereClause::query('availability', $request->availability));
        }
        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
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
        $productCategories = $this->categoryRepository->getAll();

        $category = new ProductCategory(['name' => 'Tìm kiếm sản phẩm']);
        $page = new SEOPage($category->name, new MetaData(['canonical' => URL::current()]), []);
        $page->pushStructure(StructureDataUtil::getInstance()->getBreadcrumbOfProductCategory($category));

        FacebookConversionJob::dispatch(
            FacebookConversionEvent::createProductCategoryEvent(
                $category,
                $pagination->items(),
                $request
            )
        );

        return view('theme.product.search', compact('page', 'pagination', 'productCategories'));
    }
}
