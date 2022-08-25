<?php declare(strict_types=1);

namespace Simtabi\Modulizer\Helpers;

use Illuminate\Support\Facades\File;

class ModuleHelpers
{

    /**
     * Load resource directory from a given base path
     *
     * @param string $basePath
     * @param string|null $resource
     * @return string
     * @since 2.0
     */

    public static function getModuleDirPath(string $basePath, ?string $resource): string
    {
        return $basePath . (!empty($resource) ? "$resource" : '');
    }

    /**
     * Load helpers from a directory
     *
     * @param string $directory
     * @since 2.0
     */
    public static function autoloadHelpers(string $directory): void
    {
        $helpers = File::glob($directory . '/*.php');
        foreach ($helpers as $helper) {
            File::requireOnce($helper);
        }
    }

    /**
     * Load modules stubs path
     *
     * @param string|null $directory
     * @return string
     * @since 2.0
     */
    public static function getStubsPath(?string $directory = null): string
    {
        $directory = !empty($directory) ? $directory : config('modulizer.stubs_path');

        return self::getModuleDirPath(__DIR__ . '/../../', $directory);
    }

    /**
     * Load modules path
     *
     * @param string|null $directory
     * @return string
     * @since 2.0
     */
    public static function getModulesPath(?string $directory = null): string
    {
        $directory = !empty($directory) ? $directory : "";

        return base_path(config('modulizer.modules_path') . "/{$directory}");
    }

    /**
     * Creates given directory if it doesn't exist
     *
     * @param string|null $directory
     * @return string
     * @since 2.0
     */
    public static function ensureFolderExists(string $path): void
    {
        File::ensureDirectoryExists($path);
    }

}
