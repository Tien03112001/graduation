<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function index(Request $request)
    {
        return $this->success();
    }
    public function clear()
    {
        $status = Cache::store('theme')->clear();
        if ($status) {
            return $this->success(["Xóa cache thành công"]);
        } else {
            return $this->error();
        }
    }
}
