<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileInfoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['unique:users,email,' . auth()->user()->id, 'required', 'email'],
            'name' => ['required', 'string'],
            '_avatar' => ['image'],
        ];
    }
}
