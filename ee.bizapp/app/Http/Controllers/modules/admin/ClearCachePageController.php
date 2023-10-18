<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ClearCachePageController extends Controller
{
    public function index(Request $request)
    {
        Artisan::call('cache:clear');
        return $this->success('Successful');
    }
}
