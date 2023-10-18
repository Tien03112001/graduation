<?php

namespace App\Console\Commands;

use App\Common\Enum\StatusEnum;
use App\Common\WhereClause;
use App\Models\Comment;
use App\Models\Post;
use App\Repository\CommentRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class AutoEnableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tu dong active cac post, comment';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->enablePosts();
        $this->enableComments();
        return 0;
    }

    private function enablePosts()
    {
        $repository = App::make(PostRepositoryInterface::class);
        if ($repository instanceof PostRepositoryInterface) {
            $models = $repository->get(
                [
                    WhereClause::queryNotNull('reserve_at'),
                    WhereClause::query('reserve_at', now()->toDateTimeString(), '<='),
                ],
                'id:asc'
            );
            foreach ($models as $model) {
                if ($model instanceof Post) {
                    $repository->update($model->id, [
                        'published' => StatusEnum::ACTIVE,
                        'reserve_at' => null
                    ]);
                }
            }
        }

    }

    private function enableComments()
    {
        $repository = App::make(CommentRepositoryInterface::class);
        if ($repository instanceof CommentRepositoryInterface) {
            $models = $repository->get(
                [
                    WhereClause::queryNotNull('reserve_at'),
                    WhereClause::query('reserve_at', now()->toDateTimeString(), '<='),
                ],
                'id:asc'
            );
            foreach ($models as $model) {
                if ($model instanceof Comment) {
                    $repository->update($model->id, [
                        'published' => StatusEnum::ACTIVE,
                        'reserve_at' => null
                    ]);
                }
            }
        }

    }

}
