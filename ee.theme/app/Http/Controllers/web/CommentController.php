<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\RestController;
use App\Models\Article;
use App\Repository\ArticleRepositoryInterface;
use App\Repository\CommentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommentController extends RestController
{
    protected $articleRepository;

    public function __construct(CommentRepositoryInterface $repository, ArticleRepositoryInterface $articleRepository)
    {
        parent::__construct($repository);
        $this->articleRepository = $articleRepository;
    }


    public function store(Request $request)
    {
        if ($request->author != null) {
            $validator = $this->validateRequest($request, [
                'article_id' => 'required|numeric',
                'author' => 'required|max:255',
                'email' => 'required|email',
                'content' => 'required'
            ]);
        } else {
            $validator = $this->validateRequest($request, [
                'article_id' => 'required|numeric',
                'authorM' => 'required|max:255',
                'emailM' => 'required|email',
                'contentM' => 'required'
            ]);
        }
        if ($validator) {
            throw new BadRequestHttpException();
        }
        $article = $this->articleRepository->findById($request->article_id);

        if (empty($article)) {
            abort(404);
        }

        if (!($article instanceof Article)) {
            abort(404);
        }

        $content = $request->input('content');


        $bHasLink = strpos($content, 'http') !== false || strpos($content, 'www.') !== false;
        if ($bHasLink) {
            abort(400);
        }
        try {
            if ($content != null) {
                $this->repository->create([
                    'article_id' => $article->id,
                    'parent_id' => 0,
                    'published' => 1,
                    'author' => $request->author . ',' . $request->email,
                    'content' => $content,
                ]);
            }
            if ($content == null) {
                $this->repository->create([
                    'article_id' => $article->id,
                    'parent_id' => 0,
                    'published' => 1,
                    'author' => $request->authorM . ',' . $request->emailM,
                    'content' => $request->contentM,
                ]);
            }

        } catch (\Exception $e) {
            Log::error($e);
            abort(500);
        }
        return Redirect::back();
    }

}
