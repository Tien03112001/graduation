<?php

namespace App\Jobs;

use App\Models\Conversation;
use App\Models\FacebookConversation;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $conversation;

    /**
     * Create a new job instance.
     *
     * @param Message $message
     * @param Conversation $conversation
     */
    public function __construct(Message $message, Conversation $conversation)
    {
        $this->message = $message;
        $this->conversation = $conversation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $source = $this->conversation->source;
        if (empty($this->conversation->source_type)) {
            // là tin nhắn web
        } else if ($source instanceof FacebookConversation) {
            //là facebook
            SendMessageToFacebook::dispatch($this->message, $this->conversation, $source->page);
        }
    }
}
