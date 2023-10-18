<?php

namespace App\Jobs;

use App\Common\Enum\MessageType;
use App\Common\WhereClause;
use App\Models\Agent;
use App\Models\Conversation;
use App\Models\ConversationSession;
use App\Models\FacebookConversation;
use App\Models\FacebookMessage;
use App\Models\MessageAttachment;
use App\Repository\AgentRepositoryInterface;
use App\Repository\ConversationRepositoryInterface;
use App\Repository\ConversationSessionRepositoryInterface;
use App\Repository\MessageRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RouteFacebookMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $facebookMessage;
    protected $facebookConversation;
    protected $retryCount;

    /**
     * Create a new job instance.
     *
     * @param FacebookConversation $facebookConversation
     * @param FacebookMessage $facebookMessage
     * @param int $retryCount
     */
    public function __construct(FacebookConversation $facebookConversation, FacebookMessage $facebookMessage, int $retryCount)
    {
        $this->facebookMessage = $facebookMessage;
        $this->facebookConversation = $facebookConversation;
        $this->retryCount = $retryCount;
    }

    /**
     * Execute the job.
     *
     * @param ConversationRepositoryInterface $conversationRepository
     * @param ConversationSessionRepositoryInterface $conversationSessionRepository
     * @param MessageRepositoryInterface $messageRepository
     * @param AgentRepositoryInterface $agentRepository
     * @return void
     */
    public function handle(ConversationRepositoryInterface $conversationRepository,
                           ConversationSessionRepositoryInterface $conversationSessionRepository,
                           MessageRepositoryInterface $messageRepository,
                           AgentRepositoryInterface $agentRepository)
    {

        $conversation = $this->facebookConversation->conversation;

        $lastSession = $conversation->last_session;
        if (isset($lastSession)) {
            Log::info('Conversation có lastSession');
            $ssExpiredAt = Carbon::parse($lastSession->expired_at);
            if (now()->isBefore($ssExpiredAt)) {
                Log::info('lastSession chưa hết hạn');
                // nếu chưa hết thời hạn session thì bắn tin nhắn đến agent của last session
                //B1. kiểm tra agent đang online
                $agent = $agentRepository->find([
                    WhereClause::query('user_id', $lastSession->agent_id),
                    WhereClause::query('is_online', true)
                ], null, ['user']);
                if (isset($agent) && $agent instanceof Agent) {
                    //B2. Nếu online thì bắn
                    Log::info('Agent đang online');
                    $this->publishMessage($conversation, $lastSession, $agent, $messageRepository);
                    return;
                } else {
                    //B3. Nếu không online thì tiếp tục route lại
                    if ($this->retryCount > 6) {
                        //B4. Nếu quá 60s lần kiểm tra vẫn không online thì tìm agent khác
                        Log::info('Check agent online: Quá giờ chờ. Tìm agent khác');
                        $conversationSessionRepository->update($lastSession, [
                            'open_status' => false
                        ]);
                        $lastSession = null;
                    } else {
                        //B5. Nếu chưa quá 60s thì vẫn tiếp tục chờ Agent online
                        Log::info('Check agent đang online: ' . $this->retryCount);
                        RouteFacebookMessageJob::dispatch($this->facebookConversation, $this->facebookMessage, $this->retryCount + 1)
                            ->delay(10);
                        return;
                    }
                }
            } else {
                //Nếu session đã hết hạn thì tạo mới session
                Log::info('lastSession hết hạn');
                $conversationSessionRepository->update($lastSession, [
                    'open_status' => false
                ]);
                $lastSession = null;
            }
        }

        //tạo mới session
        if (empty($lastSession)) {
            //B1. Tìm agent online mới
            Log::info('Tìm agent mới');
            $agent = $agentRepository->find([
                WhereClause::query('is_online', true),
                WhereClause::queryWhereHas('pages', [
                    WhereClause::query('page_id', $this->facebookConversation->page_id)
                ])
            ], 'priority:asc', ['user']);

            if (empty($agent)) {
                //Không có agent nào online thì cứ 30s check 1 lần (Sau này sẽ cho gặp BOT)
                Log::info('Không có agent nào online');
                RouteFacebookMessageJob::dispatch($this->facebookConversation, $this->facebookMessage, $this->retryCount + 1)
                    ->delay(30);
                return;
            } else {
                Log::info('Có agent online');
                $lastSession = $conversationSessionRepository->create([
                    'conversation_id' => $conversation->id,
                    'agent_id' => $agent->id,
                    'open_status' => true,
                    'expired_at' => now()->addDay()
                ]);
                $conversationRepository->update($conversation, [
                    'agent_id' => $agent->id,
                    'update_time' => now()->valueOf()
                ]);
                $this->publishMessage($conversation, $lastSession, $agent, $messageRepository);
                return;
            }
        }

    }

    private function publishMessage(Conversation $conversation, ConversationSession $lastSession, Agent $agent, MessageRepositoryInterface $messageRepository)
    {
        $messageAttachments = [];
        foreach ($this->facebookMessage->attachments as $att) {
            array_push($messageAttachments, new MessageAttachment([
                'type' => $att->type,
                'url' => $att->url,
                'title' => $att->title
            ]));
        }
        $message = $messageRepository->create([
            'conversation_id' => $conversation->id,
            'session_id' => $lastSession->id,
            'type' => MessageType::RECEIVE,
            'agent_id' => $lastSession->agent_id,
            'sender_name' => $this->facebookMessage->sender_name,
            'content' => $this->facebookMessage->content,
            'source_type' => $this->facebookMessage->getTable(),
            'source_id' => $this->facebookMessage->id,
            'sent_status' => true,
            'opened_status' => true,
            'relations' => [
                'attachments' => $messageAttachments
            ]
        ]);
        Log::info('Bắn tin nhắn đến agent online');
        PublishNewMessageJob::dispatch($agent->user->remember_token, $message);
    }
}
