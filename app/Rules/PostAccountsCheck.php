<?php

namespace App\Rules;

use App\Models\Account;
use Illuminate\Contracts\Validation\Rule;


class PostAccountsCheck implements Rule
{

    public function passes($attribute, $value)
    {
        // validate if accounts id belongs to the current user
        $accounts = Account::whereIn('id', $value)->get();
        if (!request()->draft && $accounts->count() == 0) {
            return false;
        }
        foreach ($accounts as $a) {
            if ($a->user_id != auth()->user()->id) {
                return false;
            }
        }
        return true;
    }


    public function message()
    {
        return 'Malformed Request , Please try again .. ';
    }
}
