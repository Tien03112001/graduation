<?php

namespace App\Jobs;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Models\TrackingEvent;
use App\Utils\Caches\AppSettingUtil;
use App\Utils\KafkaUtil;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FacebookConversionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $event;

    /**
     * Create a new job instance.
     *
     * @param FacebookConversionEvent $event
     */
    public function __construct(FacebookConversionEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        Log::info(print_r($this->event->toArray(), true));
        TrackingEvent::query()->create($this->event->toArray());
    }
}
