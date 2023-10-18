<?php

namespace App\Jobs;

use App\Common\Entities\FacebookMessageEventEntity;
use App\Common\WhereClause;
use App\Models\FacebookMessage;
use App\Models\FacebookMessageAttachment;
use App\Repository\ConversationRepositoryInterface;
use App\Repository\CustomerContactRepositoryInterface;
use App\Repository\FacebookConversationRepositoryInterface;
use App\Repository\FacebookFanpageRepositoryInterface;
use App\Repository\FacebookMessageAttachmentRepositoryInterface;
use App\Repository\FacebookMessageRepositoryInterface;
use App\Repository\FacebookWebhookCommentChangedRequestRepositoryInterface;
use App\Repository\FacebookWebhookPostChangedRequestRepositoryInterface;
use App\Repository\FacebookWebhookRequestRepositoryInterface;
use App\Repository\MessageRepositoryInterface;
use App\Utils\FacebookApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SaveFacebookPageEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $entry;

    /**
     * Create a new job instance.
     *
     * @param $entry
     */
    public function __construct($entry)
    {
        $this->entry = $entry;
    }

    public function handle(FacebookConversationRepositoryInterface $facebookConversationRepository,
                           FacebookMessageRepositoryInterface $facebookMessageRepository,
                           FacebookMessageAttachmentRepositoryInterface $facebookMessageAttachmentRepository,
                           CustomerContactRepositoryInterface $contactRepository,
                           FacebookWebhookRequestRepositoryInterface $webhookRequestRepository,
                           FacebookWebhookCommentChangedRequestRepositoryInterface $webhookCommentChangedRequestRepository,
                           FacebookWebhookPostChangedRequestRepositoryInterface $webhookPostChangedRequestRepository,
                           FacebookFanpageRepositoryInterface $fanpageRepository,
                           ConversationRepositoryInterface $conversationRepository,
                           MessageRepositoryInterface $messageRepository
    )
    {
        foreach ($this->entry as $message) {
            Log::info("------------Start Log Facebook Subscribe ----------");
            Log::info(json_encode($message, JSON_UNESCAPED_SLASHES));
            if (isset($message['changes'])) {
                // Handle Page Changes event
                $change = $message['changes'][0];
                if ($change['item'] == 'post') {
                    //bai viet moi
                    $webhookPostChangedRequestRepository->create($change);
                    $this->saveFacebookPost($change);
                } elseif ($change['item'] == 'comment') {
                    //comment moi
                    $webhookCommentChangedRequestRepository->create($change);
                    $this->saveFacebookComment($change);
                } else {
                    $webhookRequestRepository->create($message);
                }
            } else {
                $messaging = $message['messaging'];
                foreach ($messaging as $event) {
                    $fbMessageEvent = new FacebookMessageEventEntity($event);
                    if (isset($event['read'])) {
                        //da doc tin nhan
                        $this->readMessage($fbMessageEvent, $facebookMessageRepository, $messageRepository);
                    } elseif (isset($event['delivery'])) {
                        //da gui tin nhan
                        $this->sentMessage($fbMessageEvent);
                    } elseif (isset($event['message']) && isset($event['message']['is_echo'])) {
                        //tu gui tin nhan
                    } else {
                        //nhan tin nhan
                        $this->saveMessage(
                            $fbMessageEvent,
                            $facebookConversationRepository,
                            $facebookMessageRepository,
                            $facebookMessageAttachmentRepository,
                            $contactRepository,
                            $fanpageRepository,
                            $conversationRepository
                        );
                    }
                }
            }
            Log::info("------------End Log Facebook Subscribe ----------");
        }
    }

    private function saveFacebookPost($change)
    {

    }

    private function saveFacebookComment($change)
    {
    }

    /**
     * @param FacebookMessageEventEntity $event
     * @param FacebookConversationRepositoryInterface $facebookConversationRepository
     * @param FacebookMessageRepositoryInterface $facebookMessageRepository
     * @param FacebookMessageAttachmentRepositoryInterface $facebookMessageAttachmentRepository
     * @param CustomerContactRepositoryInterface $contactRepository
     * @param FacebookFanpageRepositoryInterface $fanpageRepository
     * @param ConversationRepositoryInterface $conversationRepository
     */
    private function saveMessage(FacebookMessageEventEntity $event,
                                 FacebookConversationRepositoryInterface $facebookConversationRepository,
                                 FacebookMessageRepositoryInterface $facebookMessageRepository,
                                 FacebookMessageAttachmentRepositoryInterface $facebookMessageAttachmentRepository,
                                 CustomerContactRepositoryInterface $contactRepository,
                                 FacebookFanpageRepositoryInterface $fanpageRepository,
                                 ConversationRepositoryInterface $conversationRepository)
    {
        $sender = $event->getSender();
        $recipient = $event->getRecipient();
        $message = $event->getMessage();

        $fbMessageExists = $facebookMessageRepository->find([
            WhereClause::query('mid', $message->getMid())
        ]);
        if (isset($fbMessageExists)) {
            return;
        }

        Log::info(print_r((array)$event, true));

        if (isset($sender) && isset($recipient) && isset($message)) {
            $pageId = $recipient->getId();
            $userId = $sender->getId();

            $page = $fanpageRepository->findById($pageId);
            if (empty($page)) {
                Log::error('Empty page');
                return;
            }

            $fbConversation = $facebookConversationRepository->find([
                WhereClause::query('page_id', $pageId),
                WhereClause::query('user_id', $userId),
            ], null, ['conversation']);

            $contact = $contactRepository->find([
                WhereClause::query('facebook_uuid', $userId)
            ]);

            if (empty($contact)) {
                try {
                    $senderInfo = FacebookApi::getInstance()->getUserProfile($page, $userId);
                    if ($senderInfo != false) {
                        $contact = $contactRepository->create([
                            'facebook_uuid' => $sender->getId(),
                            'last_name' => $senderInfo['last_name'],
                            'first_name' => $senderInfo['first_name'],
                            'full_name' => $senderInfo['last_name'] . ' ' . $senderInfo['first_name'],
                            'locale' => $senderInfo['locale'],
                            'timezone' => $senderInfo['timezone'],
                            'gender' => $senderInfo['gender']
                        ]);
                    } else {
                        throw new \Exception("getUserProfile of $userId is null");
                    }
                } catch (\Exception $e) {
                    Log::error($e);
                    return;
                }
            }

            if (empty($fbConversation)) {
                $fbConversation = $facebookConversationRepository->create([
                    'page_id' => $pageId,
                    'user_id' => $userId,
                ], ['page']);

                $conversationRepository->create([
                    'contact_id' => $contact->id,
                    'update_time' => now()->timestamp,
                    'source_type' => $fbConversation->getTable(),
                    'source_id' => $fbConversation->id
                ]);

            }


            $fbAttachments = [];

            foreach ($event->getMessage()->getAttachments() as $attachment) {
                array_push($fbAttachments, new FacebookMessageAttachment([
                    'type' => $attachment->getType(),
                    'url' => $attachment->getPayload()->getUrl(),
                    'title' => $attachment->getPayload()->getTitle(),
                    'sticker_id' => $attachment->getPayload()->getStickerId()
                ]));
            }


            $fbMessage = $facebookMessageRepository->create([
                'conversation_id' => $fbConversation->id,
                'mid' => $message->getMid(),
                'reply_to_mid' => $message->getReplyTo(),
                'quick_reply' => $message->getQuickReply(),
                'sender_id' => $userId,
                'sender_name' => $contact ? $contact->full_name : 'Người dùng facebook',
                'receiver_id' => $pageId,
                'content' => $message->getText(),
                'referral_product_id' => $message->getReferral() ? $message->getReferral()->getId() : null,
                'sent_timestamp' => $event->getTimestamp(),
                'sent_status' => 0,
                'read_timestamp' => null,
                'read_status' => 1,
                'relations' => [
                    'attachments' => $fbAttachments
                ]
            ]);
            Log::info('Route message');
            RouteFacebookMessageJob::dispatch($fbConversation, $fbMessage, 0);
        }
    }

    /**
     * @param FacebookMessageEventEntity $event
     * @param FacebookMessageRepositoryInterface $facebookMessageRepository
     * @param MessageRepositoryInterface $messageRepository
     */
    private function readMessage(FacebookMessageEventEntity $event,
                                 FacebookMessageRepositoryInterface $facebookMessageRepository,
                                 MessageRepositoryInterface $messageRepository)
    {
        $fbMessages = $facebookMessageRepository->get([
            WhereClause::query('receiver_id', $event->getSender()->getId()),
            WhereClause::query('sender_id', $event->getRecipient()->getId()),
            WhereClause::query('sent_status', 1),
        ]);

        $fbMessageIds = [];
        foreach ($fbMessages as $fbMessage) {
            array_push($fbMessageIds, $fbMessage->id);
        }


        if (empty($fbMessageIds)) {
            return;
        }

        $facebookMessageRepository->bulkUpdate([
            WhereClause::queryIn('id', $fbMessageIds)
        ], [
            'read_timestamp' => $event->getRead()->getWatermark(),
            'read_status' => 1
        ]);

        $messageRepository->bulkUpdate([
            WhereClause::queryIn('source_id', $fbMessageIds),
            WhereClause::query('source_type', (new FacebookMessage())->getTable())
        ], [
            'read_timestamp' => $event->getRead()->getWatermark(),
            'read_status' => 1
        ]);

    }

    /**
     * @param FacebookMessageEventEntity $event
     */
    private function sentMessage(FacebookMessageEventEntity $event)
    {
        $delivery = $event->getDelivery();
    }
}
