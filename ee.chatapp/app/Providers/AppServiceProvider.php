<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        Relation::morphMap($this->getMorphMap());
		$this->bootObservers();
    }

    private function getMorphMap()
    {
        $maps = [];
        $files = scandir(app_path('Models'));
        foreach ($files as $file) {
            if (Str::endsWith($file, '.php')) {
                $model = 'App\Models\\' . Str::replaceFirst('.php', '', $file);
                $table = call_user_func(array(new $model(), 'getTable'));
                $maps[$table] = $model;
            }
        }
        return $maps;
    }
	
	private function bootObservers()
    {
        if (file_exists(app_path('Observers'))) {
            $files = scandir(app_path('Observers'));
            foreach ($files as $file) {
                if (Str::endsWith($file, '.php')) {
                    $observer = 'App\Observers\\' . Str::replaceFirst('.php', '', $file);
                    $model = 'App\Models\\' . Str::replaceFirst('Observer.php', '', $file);
                    call_user_func($model . '::observe', $observer);
                }
            }
        }
    }
}
