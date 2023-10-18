<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Jobs\FacebookConversionJob;
use App\Models\Page;
use App\Repository\PageRepositoryInterface;
use App\Repository\PostCategoryRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductTagRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use App\Utils\Caches\DynamicTableUtil;
use Illuminate\Http\Request;


class HomeController extends RestController
{
    protected $categoryRepository;
    protected $tagRepository;
    protected $productRepository;
    protected $postCategoryRepository;
    protected $promotionRepository;
    protected $postRepository;


    public function __construct(PromotionRepositoryInterface $promotionRepository, PostCategoryRepositoryInterface $postCategoryRepository, PageRepositoryInterface $repository, ProductCategoryRepositoryInterface $categoryRepository, ProductTagRepositoryInterface $tagRepository, ProductRepositoryInterface $productRepository, PostRepositoryInterface $postRepository)
    {
        parent::__construct($repository);
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->productRepository = $productRepository;
        $this->postRepository = $postRepository;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->promotionRepository = $promotionRepository;
    }

    public function index(Request $request)
    {
        $homePage = $this->repository->find(
            [
                WhereClause::query('slug', 'trang-chu')
            ],
            null,
            ['meta', 'structured_datas'],
            []
        );
        if (empty($homePage) || !($homePage instanceof Page)) {
            return redirect('error');
        }
        $tags = $this->tagRepository->getAll();
        $tagsFilters = $this->tagRepository->get([], null, ['products.additional_attributes',], []);
        $policies = DynamicTableUtil::getInstance()->getCachedValue('Chính sách');
        $flash_sale = $this->promotionRepository->get([WhereClause::query('name', 'Flash Sale'), WhereClause::query('enable', 1)], null, ['products'], [])->first();
        $comments = DynamicTableUtil::getInstance()->getCachedValue('Phản hồi khách hàng');
        $posts = $this->postRepository->get([WhereClause::query('category_slug', 'blog'), WhereClause::query('published', 1)], 'id:desc', [], []);
        $BlogCategory = $this->postCategoryRepository->get([WhereClause::query('slug', 'blog'), WhereClause::query('published', 1)], null, [], [])->first();
        $tag_name = "Mới";
        $products = $this->productRepository->get([WhereClause::queryRelationHas('tags', function ($q) use ($tag_name) {
            $q->where('summary', $tag_name);
        })], 'id:desc', ['tags']);

        FacebookConversionJob::dispatch(FacebookConversionEvent::createPageViewEvent($homePage, $request));

        $page = new SEOPage('Trang chủ', $homePage->meta, $homePage->structured_datas);

        $view = view('theme.index', compact('page', 'tags', 'tagsFilters', 'products', 'policies', 'comments', 'posts', 'BlogCategory', 'flash_sale'));
        return $view;
    }
}
