<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class CheckOldPass implements Rule
{

    public function passes($attribute, $value)
    {
        //
        return Hash::check($value, auth()->user()->password);
    }

    public function message()
    {
        return 'You entered wrong password ';
    }
}
