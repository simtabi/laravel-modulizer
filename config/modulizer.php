<?php

return [
    'ignore_files' => ['module.json'],
    'modules_path' => env('MODULIZER_MODULES_PATH', 'platform/modules'),
    'stubs_path'   => env('MODULIZER_STUBS_PATH', 'stubs/module'),
];