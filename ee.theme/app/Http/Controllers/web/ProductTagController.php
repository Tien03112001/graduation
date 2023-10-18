<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Common\WhereClause;
use App\Http\Controllers\Controller;
use App\Jobs\FacebookConversionJob;
use App\Models\MetaData;
use App\Models\ProductCategory;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductTagRepositoryInterface;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductTagController extends Controller
{

    protected $productRepository;
    protected $tagRepository;

    public function __construct(ProductRepositoryInterface $productRepository, ProductTagRepositoryInterface $tagRepository)
    {
        $this->productRepository = $productRepository;
        $this->tagRepository = $tagRepository;
    }

    public function detail(Request $request, $slug)
    {
        $tag = $this->tagRepository->find([
            WhereClause::query('slug', $slug)
        ], null, ['meta']);
        if (empty($tag)) {
            throw new NotFoundHttpException();
        }
        $limit = $request->input('limit', 10);
        $pagination = $this->productRepository->paginate($limit, [
            WhereClause::query('published', true),
            WhereClause::queryWhereHas('tags', [WhereClause::query('id', $tag->id)])
        ], 'order:asc,id:desc', ['article', 'category']);

        $tags = $this->tagRepository->getAll();

        $category = new ProductCategory(['name' => 'Thẻ sản phẩm - ' . $tag->name]);
        $page = new SEOPage($category->name, new MetaData(['canonical' => URL::current()]), []);
        $page->pushStructure(\App\Utils\StructureDataUtil::getInstance()->getBreadcrumbOfProductCategory($category));

        FacebookConversionJob::dispatch(
            FacebookConversionEvent::createProductCategoryEvent(
                $category,
                $pagination->items(),
                $request
            )
        );

        return HtmlUtil::optimize(view('theme.product.tag', compact('tag', 'pagination', 'page', 'tags')));
    }
}
