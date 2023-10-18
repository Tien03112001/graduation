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
use App\Utils\CommandUtil;
use Illuminate\Http\Request;

class CommandController extends RestController
{

    public function __construct(AppSettingRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'method' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $method = $request->input('method');
        $message = '';
        try {
            if ($method == 'setup') {
                $message .= CommandUtil::pullCode();
                $message .= CommandUtil::composerUpdate();
                $message .= CommandUtil::migrate();
                $message .= CommandUtil::dbSeed();
                $message .= CommandUtil::cacheClear();
            } else if ($method == 'update') {
                $message .= CommandUtil::pullCode();
                $message .= CommandUtil::cacheClear();
            } else if ($method == 'cache:clear') {
                $message .= CommandUtil::cacheClear();
            } else if ($method == 'upgrade') {
                $message .= CommandUtil::pullCode();
                $message .= CommandUtil::composerUpdate();
                $message .= CommandUtil::migrate();
                $message .= CommandUtil::cacheClear();
            } else {
                $message .= CommandUtil::runCommand($method);
            }
            return $this->success(compact('message'));
        } catch (\Exception $e) {
            $message .= '-------ERROR-------' . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
            return $this->error($message);
        }
    }


}