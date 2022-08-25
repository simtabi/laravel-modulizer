<?php declare(strict_types=1);

namespace Simtabi\Modules\{Module}\Providers;

use Config;
use Illuminate\Support\ServiceProvider;
use Simtabi\Modulizer\Helpers\ModuleHelpers;

class {Module}ServiceProvider extends ServiceProvider
{
    protected string $moduleName       = '{Module}';
    protected string $moduleNameLower  = '{module}';
    protected const  MODULE_DIR_PATH   = __DIR__.'../../';

    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(ModuleHelpers::getModuleDirPath(self::MODULE_DIR_PATH, 'database/Migrations'));
    }

    public function register()
    {
        ModuleHelpers::autoloadHelpers(ModuleHelpers::getModuleDirPath(self::MODULE_DIR_PATH, 'helpers'));

        $this->app->register(ModuleRouteServiceProvider::class);
    }

    protected function registerConfig()
    {
        $this->publishes([
            ModuleHelpers::getModuleDirPath(self::MODULE_DIR_PATH, 'config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');

        $this->mergeConfigFrom(
            ModuleHelpers::getModuleDirPath(self::MODULE_DIR_PATH, 'config/config.php'), $this->moduleNameLower
        );
    }

    public function registerViews()
    {
        $viewPath   = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = ModuleHelpers::getModuleDirPath(self::MODULE_DIR_PATH, 'resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadJsonTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadJsonTranslationsFrom(ModuleHelpers::getModuleDirPath(self::MODULE_DIR_PATH, 'resources/lang'), $this->moduleNameLower);
        }
    }

    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
    
    protected function getModuleDirPath(?string $dirPath): string
    {
        return MODULE_DIR_PATH . (!empty($dirPath) ? "$dirPath" : '');
    }
}
