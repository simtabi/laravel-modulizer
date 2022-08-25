<?php declare(strict_types=1);

namespace Simtabi\Modulizer\Providers;

use Illuminate\Support\ServiceProvider;
use Simtabi\Modulizer\Console\CheckModuleSecurityCommand;
use Simtabi\Modulizer\Console\DisablePackageCommand;
use Simtabi\Modulizer\Console\EnableModuleCommand;
use Simtabi\Modulizer\Console\GenerateModuleCommand;
use Simtabi\Modulizer\Console\GenerateNewModuleCommand;
use Simtabi\Modulizer\Console\GetModuleCommand;
use Simtabi\Modulizer\Console\GitPackageCommand;
use Simtabi\Modulizer\Console\ListLocallyInstalledPackagesCommand;
use Simtabi\Modulizer\Console\MoveTestsCommand;
use Simtabi\Modulizer\Console\PublishModuleCommand;
use Simtabi\Modulizer\Console\RemoveModuleCommand;
use Simtabi\Modulizer\Helpers\ModuleHelpers;

class ModulizerServiceProvider extends ServiceProvider
{

    protected const BASE_PATH = __DIR__.'/../../';

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
                CheckModuleSecurityCommand::class,
                DisablePackageCommand::class,
                EnableModuleCommand::class,
                // GenerateModuleCommand::class,
                GenerateNewModuleCommand::class,
                GetModuleCommand::class,
                GitPackageCommand::class,
                ListLocallyInstalledPackagesCommand::class,
                MoveTestsCommand::class,
                PublishModuleCommand::class,
                RemoveModuleCommand::class,
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