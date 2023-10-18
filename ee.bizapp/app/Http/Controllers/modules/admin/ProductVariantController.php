<?php

namespace App\Http\Controllers\modules\admin;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductVariantController extends Controller
{
    public function index(Request $request){
        $limit = $request->input('limit');
        $sql = (new ProductVariant());
        if ($limit){
            $data = $sql->paginate($limit);
        }else{
            $data = $sql->get();
        }
        return $this->success($data);
    }

    public function get_product($product_id){
        $data = (new ProductVariant())->where('product_id', $product_id)
            ->with(['color', 'size'])
            ->get();
        return $this->success($data);
    }

    public function destroy($id){
        $data = ProductVariant::find($id);
        if (!$data){
            return $this->error('Not found');
        }
        $data->delete();
        return $this->success($data);
    }

    public function add_variant(Request $request){
        $color = $request->input('color_id');
        $size = $request->input('size_id');
        $product_id = $request->input('product_id');
        if ($color && $size && $product_id){
            for ($i = 0; $i < count($color); $i++){
                for ($j = 0; $j < count($size); $j++){
                    ProductVariant::updateOrCreate([
                        'product_id' => $product_id,
                        'color_id' => $color[$i]['id'],
                        'size_id' => $size[$j]['id']
                    ]);
                }
            }
        }else{
            return $this->error('Dữ liệu không hợp lệ');
        }
        return $this->success('Thành công');
    }
}
