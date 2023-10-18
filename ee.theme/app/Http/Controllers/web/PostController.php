<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Common\WhereClause;
use App\Http\Controllers\Controller;
use App\Jobs\FacebookConversionJob;
use App\Models\MetaData;
use App\Models\Post;
use App\Models\PostCategory;
use App\Repository\PostCategoryRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Utils\HtmlUtil;
use App\Utils\StructureDataUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PostController extends Controller
{
    protected $postRepository;
    protected $categoryRepository;

    public function __construct(PostRepositoryInterface $postRepository, PostCategoryRepositoryInterface $categoryRepository)
    {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $numPage = $request->input('page', 1);
        $pagination = (new Post())
            ->wherePublished(true)
            ->where('category_slug', 'blog')
            ->with('article', 'category', 'public_comments')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(3, ['*'], 'page', $numPage);
        $postLastest = $this->postRepository->get([WhereClause::query('category_slug', 'blog'), WhereClause::query('published', 1)], 'id:desc');
        $postCategories = $this->categoryRepository->getAll();

        $category = new PostCategory(['name' => 'Tất cả bài viết']);
        $page = new SEOPage($category->name, new MetaData(['canonical' => URL::current()]), []);
//        $page->pushStructure(\App\Utils\StructureDataUtil::getInstance()->getBreadcrumbOfPostCategory($category->name));

        $view = view('theme.post.blog', compact('page', 'postLastest', 'pagination', 'postCategories'));
        return HtmlUtil::optimize($view);
    }

    public function detail($categorySlug, $slug, Request $request)
    {
        $post = (new Post())
            ->whereSlug($slug)
            ->whereCategorySlug($categorySlug)
            ->wherePublished(1)
            ->with('article', 'tags', 'public_comments', 'meta', 'category')
            ->withCount('public_comments')
            ->first();

        if (empty($post)) {
            abort(404);
        }

        if ($post) {
            $related_posts = (new Post())
                ->wherePublished(1)
                ->where('category_id', $post->category_id)
                ->where('id', '<>', $post->id)
                ->orderBy('id', 'desc')
                ->with('article', 'meta', 'tags')
                ->limit(3)
                ->get();
        } else {
            $related_posts = [];
        }


        $page = new SEOPage($post->name, $post->meta, $post->structured_datas, $post->amp_path);
        $page->pushStructure(StructureDataUtil::getInstance()->getBreadcrumbOfPost($post));
        $page->pushStructure(StructureDataUtil::getInstance()->getArticle($post));

        FacebookConversionJob::dispatch(FacebookConversionEvent::createPostViewEvent($post, $request));

        return HtmlUtil::optimize(
            view('theme.post.detail', compact('post', 'page', 'related_posts'))
        );
    }

}
