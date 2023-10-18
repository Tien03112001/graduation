<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingDefault extends Model
{
    static function get_all(){
        $elements = SettingDefault::all();
        $config = [];
        foreach ($elements as $d) {
            $config[$d['name']] = $d['value'];
        }
        return $config;
    }
}
