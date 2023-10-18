<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Common\WhereClause;
use App\Http\Controllers\Controller;
use App\Jobs\FacebookConversionJob;
use App\Models\Post;
use App\Models\PostCategory;
use App\Repository\PostCategoryRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    protected $postRepository;
    protected $categoryRepository;

    public function __construct(PostRepositoryInterface $postRepository, PostCategoryRepositoryInterface $categoryRepository)
    {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function detail(Request $request, $slug)
    {
        $category = PostCategory::whereSlug($slug)
            ->with([
                'meta',
                'structured_datas'
            ])
            ->first();
        if (empty($category)) {
            abort(404);
        }
        $numPage = $request->input('page', 1);
        $pagination = (new Post())
            ->wherePublished(true)
            ->whereCategoryId($category->id)
            ->with('article', 'category')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(9, ['*'], 'page', $numPage);

        $postCategories = $this->categoryRepository->get([WhereClause::query('published', 1)], 'id:asc', [], []);

        FacebookConversionJob::dispatch(FacebookConversionEvent::createPostCategoryViewEvent($category, $request));

        $page = new SEOPage($category->name, $category->meta, $category->structured_datas);
        $page->pushStructure(\App\Utils\StructureDataUtil::getInstance()->getBreadcrumbOfPostCategory($category));

        $view = view('theme.post.category', compact('category', 'pagination', 'page', 'postCategories'));
        return HtmlUtil::optimize($view);
    }
}
