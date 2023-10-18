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
use App\Repository\PromotionRepositoryInterface;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    protected $promotionRepository;
    protected $productRepository;

    public function __construct(PromotionRepositoryInterface $promotionRepository,
                                ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->promotionRepository = $promotionRepository;
    }

    public function detail($slug, Request $request)
    {
        $promotion = $this->promotionRepository->find([
            WhereClause::query('slug', $slug),
            WhereClause::query('enable', 1)
        ], null, ['article', 'products', 'meta'], ['products']);

        if (empty($promotion)) {
            abort(404);
        }

        $meta = ['title' => $promotion->name, 'description' => $promotion->name, 'keywords' => null, 'image' => null];
        if (isset($promotion->meta)) {
            $meta['title'] = $promotion->meta->title;
            $meta['description'] = $promotion->meta->description;
            $meta['keywords'] = $promotion->meta->keywords;
        }
        if (isset($promotion->article)) {
            if (!$meta['title']) $meta['title'] = $promotion->article->title;
            if (!$meta['description']) $meta['description'] = HtmlUtil::extractShortText($promotion->article->content, '160', '...');
            if (!$meta['keywords']) $meta['keywords'] = $promotion->article->title;
        }
        $meta = (object)$meta;

        $limit = $request->input('limit', 10);

        $pagination = $this->productRepository->paginate($limit, [
            WhereClause::query('published', true),
            WhereClause::queryWhereHas('promotions', [WhereClause::query('id', $promotion->id)])
        ], 'order:asc,id:desc', ['article', 'category']);

        $category = new ProductCategory(['name' => 'CTKM ' . $promotion->name]);
        $page = new SEOPage($category->name, new MetaData(['canonical' => $promotion->slug]), []);
        $page->pushStructure(\App\Utils\StructureDataUtil::getInstance()->getBreadcrumbOfProductCategory($category));

        FacebookConversionJob::dispatch(
            FacebookConversionEvent::createProductCategoryEvent(
                $category,
                $pagination->items(),
                $request
            )
        );

        return HtmlUtil::optimize(
            view('theme.promotion.detail', compact('promotion', 'meta', 'pagination', 'page'))
        );
    }

}
