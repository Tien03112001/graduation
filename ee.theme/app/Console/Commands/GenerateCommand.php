<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;

class GenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:gen {type} {model} {module?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinh file tu dong: {type=all,route,controller|repo} {model} {module?}';

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
        $type = $this->argument('type');
        $model = $this->argument('model');
        $module = $this->argument('module');
        if ($type == 'all' || $type == 'a') {
            $this->generateRepository($model);
            $this->generateController($model, $module);
            $this->generateRoute($model, $module);
        }

        if ($type == 'route') {
            $this->generateRoute($model, $module);
        }

        if ($type == 'controller') {
            $this->generateController($model, $module);
        }

        if ($type == 'repo') {
            $this->generateRepository($model);
        }

        return 0;
    }

    private function generateRepository($model)
    {
        $interfaceFile = app_path('Repository\UserRepositoryInterface.php');
        $destInterfaceFile = Str::replace('User', $model, $interfaceFile);

        $modelFile = app_path("Models\\$model.php");
        if (!file_exists($modelFile)) {
            $this->error("ERROR: $modelFile khong ton tai!!");
            return;
        }

        if (!file_exists($destInterfaceFile)) {
            $contentInterfaceFile = file_get_contents($interfaceFile);
            $contentInterfaceFile = Str::replace('User', $model, $contentInterfaceFile);
            file_put_contents($destInterfaceFile, $contentInterfaceFile);
        } else {
            $this->error("ERROR: $destInterfaceFile da ton tai!!");
        }

        $repoFile = app_path('Repository\Eloquent\UserRepository.php');
        $destRepoFile = Str::replace('User', $model, $repoFile);
        if (!file_exists($destRepoFile)) {
            $contentRepoFile = file_get_contents($repoFile);
            $contentRepoFile = Str::replace('User', $model, $contentRepoFile);
            file_put_contents($destRepoFile, $contentRepoFile);
        } else {
            $this->error("ERROR: $destRepoFile da ton tai!!");
        }
    }

    private function generateController($model, $module)
    {
        $controllerFile = app_path('Common\Template\UserController.php');
        $destControllerFile = app_path("Http\Controllers\modules\\$module\\$model" . 'Controller.php');
        if (!file_exists($destControllerFile)) {
            $contentControllerFile = file_get_contents($controllerFile);
            $contentControllerFile = Str::replace('User', $model, $contentControllerFile);
            $contentControllerFile = Str::replace('\modules\template', "\modules\\$module", $contentControllerFile);
            file_put_contents($destControllerFile, $contentControllerFile);
        } else {
            $this->error("ERROR: $destControllerFile da ton tai!!");
        }
    }

    private function generateRoute($model, $module)
    {
        $routeFile = base_path('routes\api.php');
        if (isset($module)) {
            $routeFile = base_path("routes\modules\\$module.php");
        }
        $contentRouteFile = file_get_contents($routeFile);
        $route = Pluralizer::plural(implode("_", array_map(function ($val) {
            return Str::lower($val);
        }, Str::ucsplit($model))));
        $routeResource = "Route::resource('" . $route . "', '" . $model . "Controller');";
        $contentRouteFile = Str::replaceLast('});', "\n$routeResource\n});", $contentRouteFile);
        file_put_contents($routeFile, $contentRouteFile);
    }
}
