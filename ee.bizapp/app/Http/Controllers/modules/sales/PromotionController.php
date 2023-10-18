<?php

namespace App\Http\Controllers\modules\sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Promotion;

class PromotionController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $sql = (new Promotion())
            ->whereEnable(1)
            ->orderBy('id', 'DESC');
        if ($limit) {
            $data = $sql->paginate($limit);
        } else {
            $data = $sql->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        return $this->error('Not support for this method');
    }

    public function show($id)
    {
        return $this->error('Not support for this method');
    }

    public function update(Request $request, $id)
    {
        return $this->error('Not support for this method');
    }

    public function destroy($id)
    {
        return $this->error('Not support for this method');
    }

}
