<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\RestController;
use App\Repository\OrderRepositoryInterface;
use Illuminate\Http\Request;

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
}
