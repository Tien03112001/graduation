<?php

namespace App\Http\Controllers\modules\chat;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ConversationRepositoryInterface;
use App\Utils\AuthUtil;
use Illuminate\Http\Request;

class ConversationController extends RestController
{
    protected $agentRepository;
    public function __construct(ConversationRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $agent = AuthUtil::getInstance()->getModel();

        $limit = $request->input('limit', '20');
        $clauses = [];
        $orderBy = $request->input('orderBy', 'update_time:desc');

        array_push($clauses, WhereClause::query('agent_id', $agent->id));

        $with = ['customer.rank','contact','source.page','messages'];
        $withCount = [];

        if ($request->has('search')) {
            $search = $request->search;
            array_push($clauses, WhereClause::queryRelationHas('contact', function ($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%');
            }));
        }

        if ($request->has('update_time')) {
            array_push($clauses, WhereClause::query('update_time', $request->update_time, '<'));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

}
