<?php

namespace App\Console\Commands;

use App\Common\WhereClause;
use App\Repository\AgentRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Utils\RedisUtil;
use Illuminate\Console\Command;

class ListenAgentStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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

    /**
     * Execute the console command.
     *
     * @param UserRepositoryInterface $userRepository
     * @param AgentRepositoryInterface $agentRepository
     * @return int
     */
    public function handle(UserRepositoryInterface $userRepository, AgentRepositoryInterface $agentRepository)
    {
        RedisUtil::subscribe('agent_status', function ($message) use ($userRepository, $agentRepository) {
            $data = json_decode($message, true);
            $token = $data['token'];
            $status = $data['status'];
            $user = $userRepository->find([
                WhereClause::query('remember_token', $token)
            ], null, ['agent']);
            if (isset($user) && isset($user->agent)) {
                $agentRepository->update($user->agent, [
                    'is_online' => $status,
                    'online_at' => $status == 1 ? now()->toDateTimeString() : null
                ]);
            }
        });
        return 0;
    }
}
