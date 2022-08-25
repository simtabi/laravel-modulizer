<?php

namespace Simtabi\Modules\{Module}\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Update{Model}Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required'
        ];
    }
}
