<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreColumnRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => ['required', 'string'],
        ];
    }
}
