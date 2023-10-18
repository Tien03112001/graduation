<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use App\Models\SettingMenuType;

class SettingMenuTypeController extends Controller
{
    public function index()
    {
        $menu_supports = SettingMenuType::all();
        $datas = [];
        $datas['types'] = [];
        array_push($datas['types'], ['name' => 'Link nhập tay', 'type' => 'other']);
        foreach ($menu_supports as $menu_support) {
            $data = null;
            eval($menu_support->code);
            $datas[$menu_support->type] = $data;
            array_push($datas['types'], ['name' => $menu_support->name, 'type' => $menu_support->type]);
        }
        return $this->success($datas);
    }
}
