<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Jobs\FacebookConversionJob;
use App\Utils\Caches\DynamicTableUtil;
use Illuminate\Http\Request;
use App\Repository\PageRepositoryInterface;

class AboutController extends RestController
{
    public function __construct(PageRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $page = new SEOPage('Giới thiệu');
        $post = $this->repository->get([WhereClause::query('slug', 'gioi-thieu')], null, ['article'], [])->first();
        $policies = DynamicTableUtil::getInstance()->getCachedValue('Chính sách');
        $product_values = DynamicTableUtil::getInstance()->getCachedValue('Giá trị sản phẩm');
        $comments = DynamicTableUtil::getInstance()->getCachedValue('Phản hồi khách hàng');

        FacebookConversionJob::dispatch(FacebookConversionEvent::createCustomPageViewEvent('About', $request));
        return view('theme.page.about', compact('product_values', 'page', 'post', 'policies', 'comments'));
    }
}
