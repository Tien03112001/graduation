<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\FacebookFanpage;
use App\Repository\FacebookFanpageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FacebookFanpageController extends RestController
{
    public function __construct(FacebookFanpageRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        $with = [];
        $withCount = [];

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id,);
        $model->agents()->detach();
        return parent::destroyDefault($id);
    }


    public function assign($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model) || !($model instanceof FacebookFanpage)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'agent_ids' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $agentIds = explode(',', $request->input('agent_ids'));

        try {
            DB::beginTransaction();
            $model->agents()->sync($agentIds);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
