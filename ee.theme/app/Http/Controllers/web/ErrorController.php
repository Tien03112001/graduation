<?php

namespace App\Http\Controllers\web;

use App\Common\SEO\SEOPage;
use App\Http\Controllers\Controller;
use App\Models\MetaData;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function index(Request $request)
    {
        $message = $request->input('message');
        $page = new SEOPage('Lỗi hệ thống', new MetaData(['robots' => 'noindex']));
        return view('theme.error', compact('page', 'message'));
    }
}
