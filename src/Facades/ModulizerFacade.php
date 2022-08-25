<?php declare(strict_types=1);

namespace Simtabi\Modulizer\Facades;

use Illuminate\Support\Facades\Facade;
use Simtabi\Modulizer\Modulizer;

class ModulizerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Modulizer::class;
    }
}
