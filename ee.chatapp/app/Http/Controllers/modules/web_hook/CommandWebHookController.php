<?php

namespace App\Http\Controllers\modules\web_hook;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\UserRepositoryInterface;
use App\Utils\ProcessUtil;
use Illuminate\Http\Request;

class CommandWebHookController extends RestController
{

    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'token' => 'required',
            'commands' => 'required'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $token = $request->input('token');

        $user = $this->repository->find([
            WhereClause::query('remember_token', $token)
        ]);
        if (empty($user)) {
            return $this->errorClient('Token invalid');
        }

        $message = '';
        $lines = $request->input('commands');
        if (isset($lines)) {
            $lines = preg_split('/\r\n|[\r\n]/', $lines);
            foreach ($lines as $line) {
                try {
                    $message .= PHP_EOL . ProcessUtil::shellSync($line, base_path());
                } catch (\Exception $e) {
                    return $this->errorClient($e->getMessage() . PHP_EOL . $e->getTraceAsString());
                }
            }
        }
        return $this->success($message);
    }

}
