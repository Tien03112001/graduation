<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Jobs\FacebookConversionJob;
use App\Models\Page;
use App\Repository\PageRepositoryInterface;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;

class PageController extends RestController
{

    protected $categoryRepository;
    protected $tagRepository;
    protected $productRepository;
    protected $postCategoryRepository;
    protected $promotionRepository;
    protected $postRepository;


    public function __construct(PageRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail($slug, Request $request)
    {
        $pageDetail = $this->repository->find(
            [
                WhereClause::query('slug', $slug)
            ],
            null,
            ['meta', 'structure_data'],
            []
        );

        if (empty($pageDetail) || !($pageDetail instanceof Page)) {
            abort(404);
        }

        FacebookConversionJob::dispatch(FacebookConversionEvent::createPageViewEvent($pageDetail, $request));

        $page = new SEOPage($pageDetail->name, $pageDetail->meta, $pageDetail->structured_datas);

        return HtmlUtil::optimize(view('theme.page.detail', compact('page', 'pageDetail')));
    }
}
