<?php

return [
    'ignore_files' => ['module.json'],
    'modules_path' => env('MODULIZER_MODULES_PATH', 'platform/modules'),
    'stubs_path'   => env('MODULIZER_STUBS_PATH', 'stubs/module'),

    /**
     * You can set defaults for the following placeholders.
     */
    'author_name'     => 'Simtabi',
    'author_email'    => 'info@simtabi.com',
    'author_homepage' => 'https://simtabi.com',
    'author_role'     => 'Developer',
    'license'         => 'MIT',
];