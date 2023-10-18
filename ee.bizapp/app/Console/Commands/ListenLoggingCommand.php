<?php

namespace App\Console\Commands;

use App\Common\Core\KafkaClosure;
use App\Common\Core\KafkaPartitionConfig;
use App\Common\Core\KafkaTopicConfig;
use App\Repository\LoggingTrafficRepositoryInterface;
use App\Utils\KafkaUtil;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use RdKafka\Message;

class ListenLoggingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logging:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(LoggingTrafficRepositoryInterface $trafficRepository)
    {
        try {
            $topics = [new KafkaTopicConfig('logging_traffic', [KafkaPartitionConfig::create()])];
            $closure = KafkaClosure::getInstance()
                ->setSuccess(function (Message $message) use ($trafficRepository) {
                    $this->info('Message catching ...');
                    try {
                        $traffic = json_decode($message->payload, true, 512, JSON_THROW_ON_ERROR);
                        $trafficRepository->create($traffic);
                    } catch (\Exception $e) {
                        Log::error($e);
                    }
                });
            KafkaUtil::getInstance()
                ->setConsumeConfig('logging')
                ->consume('ezi.theme', $topics, $closure);
        } catch (\Exception $e) {
            Log::error($e);
            $this->error($e->getMessage());
        }
        return 0;
    }
}
