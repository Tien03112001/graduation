<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function index()
    {
        $status = Cache::store('theme')->clear();
        if ($status) {
            return $this->success([]);
        } else {
            return $this->error();
        }
    }
}
