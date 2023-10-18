<?php

namespace App\Jobs;

use App\Models\Message;
use App\Utils\RedisUtil;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishNewMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $channel;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param string $token
     * @param Message $message
     */
    public function __construct(string $token, Message $message)
    {
        $this->channel = $token . "_conversations";
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->message->load('conversation.contact', 'conversation.source.page');
        $payload = [
            'type' => 'update_message',
            'data' => [
                'message' => $this->message->toArray(),
                'conversation' => $this->message->conversation->toArray()
            ],
        ];
        $count = RedisUtil::publish('broadcast', json_encode([
            'emitChannel' => $this->channel,
            'payload' => $payload
        ], JSON_UNESCAPED_SLASHES));
        if ($count == 0) {
            $this->release(30);
        }
    }
}
