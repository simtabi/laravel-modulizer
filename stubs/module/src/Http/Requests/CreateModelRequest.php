<?php declare(strict_types=1);

namespace Simtabi\Modules\{Module}\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;;

class Create{Model}Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required'
        ];
    }
}
