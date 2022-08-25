<?php

namespace Simtabi\Modulizer;

use Illuminate\Support\ServiceProvider;
use Simtabi\Modulizer\Console\Commands\MakeGeneratorCommand;

class ModulizerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/modulizer.php', 'modulizer');
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
                __DIR__.'/../stubs' => base_path('stubs'),
            ], 'stubs');

            $this->publishes([
                __DIR__.'/../config/modulizer.php' => config_path('modulizer.php'),
            ], 'config');
        }
    }
}
