<?php

namespace App\Http\Controllers\amp;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Repository\PostCategoryRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Utils\HtmlUtil;

class PostController extends Controller
{
    protected $postRepository;
    protected $categoryRepository;

    public function __construct(PostRepositoryInterface $postRepository, PostCategoryRepositoryInterface $categoryRepository)
    {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function detail($categorySlug, $slug)
    {
        $post = (new Post())
            ->whereSlug($slug)
            ->whereCategorySlug($categorySlug)
            ->wherePublished(1)
            ->with('article', 'tags', 'public_comments', 'meta', 'category')
            ->withCount('public_comments')
            ->first();


        if ($post) {
            $related_posts = (new Post())
                ->wherePublished(1)
                ->where('category_id', $post->category_id)
                ->where('id', '<>', $post->id)
                ->orderBy('id', 'DESC')
                ->with('article', 'meta', 'tags')
                ->limit(3)
                ->get();
        } else {
            $related_posts = [];
        }
        $meta = ['title' => '', 'description' => '', 'keywords' => '', 'image' => null];
        if (isset($post->meta)) {
            $meta['title'] = $post->meta->title;
            $meta['description'] = $post->meta->description;
            $meta['keywords'] = $post->meta->keywords;
        }
        if (!$meta['title']) $meta['title'] = $post->article->title;
        if (!$meta['description']) $meta['description'] = HtmlUtil::extractShortText($post->article->content, '160', '...');
        if (!$meta['keywords']) $meta['keywords'] = $post->article->title;
        $meta = (object)$meta;

        $postCategories = (new PostCategory())
            ->where('published', true)
            ->withCount('posts')
            ->get();

        $page = (object)['slug' => $post->slug, 'name' => $post->article->title];
        $breadcrumbs = [];
        if (!empty($post->category)) {
            $breadcrumbs = [(object)['full_path' => $post->category->full_path, 'name' => $post->category->name]];
        }

        $tags = (new PostTag())->get();

        return view('amp.post.detail', compact('post', 'meta', 'related_posts', 'postCategories', 'breadcrumbs', 'page', 'tags'));
    }

    public function json($id)
    {
        $post = (new Post())
            ->find($id);
        if ($post) {
            $related_posts = (new Post())
                ->wherePublished(1)
                ->where('category_id', $post->category_id)
                ->where('id', '<>', $post->id)
                ->orderBy('id', 'DESC')
                ->with('article', 'meta', 'tags')
                ->limit(3)
                ->get();
        } else {
            $related_posts = [];
        }

        return [
            'items' => $related_posts
        ];
    }
}
