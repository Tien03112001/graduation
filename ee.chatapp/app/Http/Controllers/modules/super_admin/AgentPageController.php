<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Http\Controllers\RestController;
use App\Common\WhereClause;
use App\Repository\AgentPageRepositoryInterface;
use App\Repository\AgentRepositoryInterface;
use App\Repository\FacebookFanpageRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AgentPageController extends RestController
{
    protected $agentRepository;
    protected $pageRepository;

    public function __construct(AgentPageRepositoryInterface $repository, AgentRepositoryInterface $agentRepository, FacebookFanpageRepositoryInterface $pageRepository)
    {
        parent::__construct($repository);
        $this->pageRepository = $pageRepository;
        $this->agentRepository = $agentRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit',null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'page_id:asc');
        $with = ['agent_page'];
        $withCount = [];
        if ($request->has('page_id')) {
            array_push($clauses, WhereClause::query('page_id', $request->page_id));
        }
        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function attachAgentPage(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'agent_id' => 'required|max:255',
            'page_id' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $agent = $this->agentRepository->findById($request->input('agent_id'));
        if (empty($agent)) {
            return $this->errorClient('ID người dùng không tồn tại');
        }
        $page = $this->pageRepository->findById($request->input('page_id'));
        if (empty($page)) {
            return $this->errorClient('Fanpage không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model = $this->agentRepository->attach($agent, $page);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function detachAgentPage(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'agent_id' => 'required|max:255',
            'page_id' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $agent = $this->agentRepository->findById($request->input('agent_id'));
        if (empty($agent)) {
            return $this->errorClient('ID người dùng không tồn tại');
        }
        $page = $this->pageRepository->findById($request->input('page_id'));
        if (empty($page)) {
            return $this->errorClient('Fanpage không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model = $this->agentRepository->detach($agent, $page);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
