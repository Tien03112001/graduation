<?php

namespace App\Http\Controllers\modules\web_hook;

use App\Http\Controllers\RestController;
use App\Repository\SystemFacebookRequestWebHookRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FacebookWebHookController extends RestController
{

    public function __construct(SystemFacebookRequestWebHookRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function unsubscribe(Request $request)
    {
        try {
            DB::beginTransaction();
            $model = $this->repository->create([
                'data' => json_encode($request->all()),
                'type' => 'unsubscribe'
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function clearData(Request $request)
    {
        try {
            DB::beginTransaction();
            $model = $this->repository->create([
                'data' => json_encode($request->all()),
                'type' => 'clearData'
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
