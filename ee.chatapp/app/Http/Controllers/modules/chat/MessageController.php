<?php

namespace App\Http\Controllers\modules\chat;

use App\Common\Enum\MessageType;
use App\Common\Enum\StatusEnum;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Jobs\SendMessageJob;
use App\Models\Message;
use App\Repository\ConversationRepositoryInterface;
use App\Repository\MessageRepositoryInterface;
use App\Utils\AuthUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends RestController
{
    protected $conversationRepository;

    public function __construct(MessageRepositoryInterface $repository, ConversationRepositoryInterface $conversationRepository)
    {
        parent::__construct($repository);
        $this->conversationRepository = $conversationRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', '20');
        $clauses = [];
        $orderBy = $request->input('orderBy', 'created_at:desc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('sender_name', $request->search));
        }

        if ($request->has('conversation_id')) {
            array_push($clauses, WhereClause::query('conversation_id', $request->conversation_id));
        }

        if ($request->has('created_at')) {
            array_push($clauses, WhereClause::query('created_at', $request->created_at, '<'));
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

    public function store(Request $request)
    {
        $agent = AuthUtil::getInstance()->getModel();
        $validator = $this->validateRequest($request, [
            'conversation_id' => 'required|numeric',
            'content' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $conversation = $this->conversationRepository->findById($request->conversation_id, ['last_session']);

        if (empty($conversation)) {
            return $this->errorClient('Hội thoại không tồn tại');
        }

        $attributes = $request->only([
            'conversation_id',
            'content',
        ]);
        if (empty($conversation->last_session)) {
            $attributes['session_id'] = 0;
        } else {
            $attributes['session_id'] = $conversation->last_session->id;
        }
        $attributes['agent_id'] = $agent->id;
        $attributes['sender_name'] = $agent->name;
        $attributes['type'] = MessageType::SEND;
        $attributes['sent_status'] = StatusEnum::INACTIVE;
        $attributes['opened_status'] = StatusEnum::INACTIVE;

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes,['conversation.customer.rank','conversation.contact','conversation.source.page','conversation.messages']);
            $conversation = $this->conversationRepository->update($conversation->id, [
                'update_time' => now()->valueOf()
            ]);
            SendMessageJob::dispatch($model, $conversation);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Message)) {
            return $this->errorNotFound();
        }

        if ($model->sent_status == StatusEnum::ACTIVE) {
            return $this->errorClient('Tin nhắn đã được gửi đi');
        }

        $validator = $this->validateRequest($request, [
            'content' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $columns = [
            'content',
        ];
        $attributes = [];

        foreach ($columns as $column) {
            $attributes[$column] = $request->{$column};
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if ($model->sent_status == StatusEnum::ACTIVE) {
            return $this->errorClient('Tin nhắn đã được gửi đi');
        }
        try {
            DB::beginTransaction();
            $this->repository->delete($model);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
