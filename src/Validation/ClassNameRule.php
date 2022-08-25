<?php declare(strict_types=1);

namespace Simtabi\Modulizer\Validation;

use Illuminate\Contracts\Validation\Rule;

class ClassNameRule implements Rule
{
    public $pattern = '/^[a-zA-Z_-\x80-\xff][a-zA-Z0-9_-\x80-\xff]*$/';

    public function passes($attribute, $value)
    {
        return preg_match($this->pattern, $value);
    }

    public function message()
    {
        return 'The package :attribute must conform to a valid PHP classname.';
    }
}