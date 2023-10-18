<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Http\Controllers\Controller;
use App\Jobs\FacebookConversionJob;
use App\Models\MetaData;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Repository\PostRepositoryInterface;
use App\Repository\PostTagRepositoryInterface;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostTagController extends Controller
{

    protected $postRepository;
    protected $tagRepository;

    public function __construct(PostRepositoryInterface $postRepository, PostTagRepositoryInterface $tagRepository)
    {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
    }

    public function detail(Request $request, $slug)
    {
        $tag = PostTag::whereSlug($slug)
            ->with([
                'meta',
                'structured_datas'
            ])
            ->first();
        if (empty($tag)) {
            throw new NotFoundHttpException();
        }
        $numPage = $request->input('page', 1);
        $pagination = (new Post())
            ->wherePublished(true)
            ->with('article', 'category')
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('id', $tag->id);
            })
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(9, ['*'], 'page', $numPage);

        $tags = $this->tagRepository->getAll();

        $category = new PostCategory(['name' => 'Thẻ bài viết ' . $tag->name]);
        $page = new SEOPage($category->name, new MetaData(['canonical' => URL::current()]), []);
        $page->pushStructure(\App\Utils\StructureDataUtil::getInstance()->getBreadcrumbOfPostCategory($category));

        FacebookConversionJob::dispatch(
            FacebookConversionEvent::createPostCategoryViewEvent(
                $category, $request
            )
        );

        return HtmlUtil::optimize(view('theme.post.tag', compact('tag', 'pagination', 'page', 'tags')));
    }
}
