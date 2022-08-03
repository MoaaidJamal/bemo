<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCardRequest extends FormRequest
{
    public function rules()
    {
        return [
            'column_id' => ['required', Rule::exists('columns', 'id')],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
    }
}
