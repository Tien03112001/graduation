<?php

namespace App\Http\Controllers\modules\definition;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $data = PaymentType::orderBy('priority')->get();
        return $this->success($data);
    }
}
