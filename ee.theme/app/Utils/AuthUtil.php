<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 9/28/2017
 * Time: 3:55 PM
 */

namespace App\Utils;


use Illuminate\Database\Eloquent\Model;

class AuthUtil
{
    protected $model;

    public function __construct()
    {
    }

    public static function getInstance()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new AuthUtil();
        }
        return $instance;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }
}