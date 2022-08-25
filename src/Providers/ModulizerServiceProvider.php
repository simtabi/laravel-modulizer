<?php declare(strict_types=1);

namespace Simtabi\Modulizer\Providers;

use Illuminate\Support\ServiceProvider;
use Simtabi\Modulizer\Console\Commands\MakeGeneratorCommand;
use Simtabi\Modulizer\Helpers\ModuleHelpers;

class ModulizerServiceProvider extends ServiceProvider
{

    protected const BASE_PATH = __DIR__.'../../';

    public function register()
    {
        $this->mergeConfigFrom(ModuleHelpers::getModuleDirPath(self::BASE_PATH, 'config/modulizer.php'), 'modulizer');
    }

    public function boot()
    {
        $this->configureCommands();
        $this->configurePublishing();
    }

    public function configureCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeGeneratorCommand::class,
            ]);
        }
    }

    public function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                ModuleHelpers::getModuleDirPath(self::BASE_PATH, 'stubs') => base_path('stubs'),
            ], 'stubs');

            $this->publishes([
                ModuleHelpers::getModuleDirPath(self::BASE_PATH, 'config/modulizer.php') => config_path('modulizer.php'),
            ], 'config');
        }
    }
}
