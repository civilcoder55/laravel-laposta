<?php

namespace App\Http\Requests;

use App\Rules\CheckOldPass;
use Illuminate\Foundation\Http\FormRequest;

class ProfilePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'oldPassword' => ['required', new CheckOldPass],
            'password' => ['required', 'min:6', 'confirmed'],
        ];
    }
}
