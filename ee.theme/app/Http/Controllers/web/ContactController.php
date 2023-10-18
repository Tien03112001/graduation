<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Http\Controllers\Controller;
use App\Jobs\FacebookConversionJob;
use Illuminate\Http\Request;
use App\Utils\Caches\DynamicTableUtil;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $page = new SEOPage('Liên hệ');
        $contact = DynamicTableUtil::getInstance()->getCachedValue('Thông tin liên hệ');
        $map = DynamicTableUtil::getInstance()->getCachedValue('Địa chỉ bản đồ');

        FacebookConversionJob::dispatch(FacebookConversionEvent::createCustomPageViewEvent('Liên hệ', $request));

        return view('theme.page.contact', compact('contact', 'page', 'map'));
    }
}
