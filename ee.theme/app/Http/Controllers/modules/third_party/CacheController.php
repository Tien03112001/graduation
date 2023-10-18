<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 7/25/2022
 * Time: 16:10
 */

namespace App\Http\Controllers\modules\third_party;

use App\Http\Controllers\RestController;
use App\Repository\AppSettingRepositoryInterface;
use App\Utils\ProcessUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CacheController extends RestController
{

    public function __construct(AppSettingRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request)
    {
        $result = ProcessUtil::shellSync('php artisan optimize:clear');
        if ($result) {
            return $this->success($request);
        } else {
            return $this->errorClient();
        }

    }
}