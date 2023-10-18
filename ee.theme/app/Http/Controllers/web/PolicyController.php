<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Http\Controllers\Controller;
use App\Jobs\FacebookConversionJob;
use App\Models\Post;
use Illuminate\Http\Request;


class PolicyController extends Controller
{
    public function detail($slug, Request $request)
    {
        $post = (new Post())
            ->whereSlug($slug)
            ->whereCategorySlug('chinh-sach')
            ->wherePublished(1)
            ->with('article', 'meta')
            ->first();

        if (empty($post) || !($post instanceof Post)) {
            abort(404);
        }

        FacebookConversionJob::dispatch(
            FacebookConversionEvent::createPostViewEvent($post, $request)
        );

        $page = new SEOPage($post->name, $post->meta, $post->structured_datas, $post->amp_path);
        return view('theme.page.policy', compact('post', 'page'));
    }
}
