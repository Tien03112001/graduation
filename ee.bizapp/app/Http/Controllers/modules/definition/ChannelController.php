<?php

namespace App\Http\Controllers\modules\definition;

use App\Http\Controllers\Controller;
use App\Models\Channel;

class ChannelController extends Controller
{
    public function index()
    {
        $data = Channel::orderBy('priority')->get();
        return $this->success($data);
    }
}
