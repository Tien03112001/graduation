<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Http\Controllers\Controller;
use App\Jobs\FacebookConversionJob;
use App\Models\MetaData;
use App\Models\Post;
use App\Models\PostCategory;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class PostSearchController extends Controller
{
    public function search(Request $request)
    {
        $numPage = $request->input('page', 1);
        $search = $request->input('search');
        if (empty($search)) {
            return Redirect::back();
        }
        $pagination = (new Post())
            ->wherePublished(true)
            ->with('article')
            ->whereHas('article', function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('content', 'like', "%$search%");
            })
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(6, ['*'], 'page', $numPage)
            ->appends(request()->query());

        $category = new PostCategory(['name' => 'Tìm kiếm bài viết']);
        $page = new SEOPage($category->name, new MetaData(['canonical' => URL::current()]), []);
        $page->pushStructure(\App\Utils\StructureDataUtil::getInstance()->getBreadcrumbOfPostCategory($category));

        FacebookConversionJob::dispatch(
            FacebookConversionEvent::createPostCategoryViewEvent(
                $category, $request
            )
        );

        return HtmlUtil::optimize(view('theme.post.search', compact('page', 'pagination')));
    }
}
