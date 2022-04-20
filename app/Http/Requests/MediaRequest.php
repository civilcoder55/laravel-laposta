<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'media' => ['required', 'array', 'min:1'],
            'media.*' => ['required', 'image', 'mimes:jpeg,png,jpg'],
        ];
    }
}
