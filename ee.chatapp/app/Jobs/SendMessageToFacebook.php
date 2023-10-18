<?php

namespace App\Jobs;

use App\Models\Conversation;
use App\Models\FacebookFanpage;
use App\Models\FacebookMessageAttachment;
use App\Models\Message;
use App\Repository\FacebookMessageRepositoryInterface;
use App\Repository\MessageRepositoryInterface;
use App\Utils\FacebookApi;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMessageToFacebook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $conversation;
    protected $page;

    /**
     * Create a new job instance.
     *
     * @param Message $message
     * @param Conversation $conversation
     * @param FacebookFanpage $page
     */
    public function __construct(Message $message, Conversation $conversation, FacebookFanpage $page)
    {
        $this->message = $message;
        $this->conversation = $conversation;
        $this->page = $page;
    }

    public function handle(MessageRepositoryInterface $messageRepository, FacebookMessageRepositoryInterface $facebookMessageRepository)
    {
        $fbConversation = $this->conversation->source;
        try {
            $response = FacebookApi::getInstance()
                ->setAccessToken($this->page->access_token)
                ->sendMessage($this->page, $fbConversation->user_id, $this->message);
            if ($response == false) {
                throw new \Exception('Gửi tin nhắn qua facebook không thành công');
            } else {

                $fbAttachments = [];

                if ($this->message->attachments) {
                    foreach ($this->message->attachments as $attachment) {
                        array_push($fbAttachments, new FacebookMessageAttachment([
                            'type' => $attachment->type,
                            'url' => $attachment->url,
                            'title' => $attachment->title,
                            'sticker_id' => null
                        ]));
                    }
                }

                $fbMessage = $facebookMessageRepository->create([
                    'conversation_id' => $fbConversation->id,
                    'mid' => $response->getMessageId(),
                    'reply_to_mid' => null,
                    'quick_reply' => null,
                    'sender_id' => $this->page->id,
                    'sender_name' => $this->message->sender_name,
                    'receiver_id' => $response->getRecipientId(),
                    'content' => $this->message->content,
                    'referral_product_id' => null,
                    'sent_timestamp' => now()->valueOf(),
                    'sent_status' => 1,
                    'read_timestamp' => null,
                    'read_status' => 0,
                    'relations' => [
                        'attachments' => $fbAttachments
                    ]
                ]);

                $messageRepository->update($this->message, [
                    'source_type' => $fbMessage->getTable(),
                    'source_id' => $fbMessage->id,
                    'sent_status' => 1
                ]);
            }
        } catch (FacebookSDKException $e) {
            Log::error($e);
            $this->release(5);
        } catch (\Exception $e) {
            Log::error($e);
            $this->release(5);
        }
    }
}
