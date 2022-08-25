<?php declare(strict_types=1);

namespace Simtabi\Modules\{Module}\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Simtabi\Modulizer\Helpers\ModuleHelpers;

class ModuleRouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace  = 'Simtabi\Modules\{Module}\Http\Controllers';
    protected const  MODULE_DIR_PATH   = __DIR__.'../../';

    public function register()
    {
        ModuleHelpers::autoloadHelpers(ModuleHelpers::getModuleDirPath(self::MODULE_DIR_PATH, 'helpers'));
    }

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(ModuleHelpers::getModuleDirPath(self::MODULE_DIR_PATH, 'routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(ModuleHelpers::getModuleDirPath(self::MODULE_DIR_PATH, 'routes/api.php'));
    }

}
