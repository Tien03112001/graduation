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
use Illuminate\Http\Request;

class RobotFileController extends RestController
{
    public function __construct(AppSettingRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $content = file_get_contents(public_path('robots.txt'));
        return $this->success($content);
    }


    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'content' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $robotFile = public_path('robots.txt');
        file_put_contents($robotFile, $request->input('content'));
        return $this->success(file_get_contents($robotFile));
    }
}