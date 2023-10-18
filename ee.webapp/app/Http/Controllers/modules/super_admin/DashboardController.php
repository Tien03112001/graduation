<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Http\Controllers\RestController;
use App\Repository\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends RestController
{

    public function __construct(OrderRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $data = [];
        return $this->success($data);
    }

    public function clear()
    {
        $cache = Artisan::call('cache:clear');
        if($cache == 0) {
            return $this->success('Xóa cache thành công');
        } else {
            return $this->errorClient('Xóa cache thất bại');
        }
    }
}
