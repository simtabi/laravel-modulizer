<?php

namespace Simtabi\Modulizer\Helpers;

use File;

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
}