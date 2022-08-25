<?php

namespace Simtabi\Modulizer\Console;

use Illuminate\Console\Command;
use Simtabi\Modulizer\Traits\HasProgressBar;

abstract class BaseCommand extends Command
{
    use HasProgressBar;

    public function __construct()
    {
        parent::__construct();
    }
}