<?php

namespace App\Rules;

use App\Models\Media;
use Illuminate\Contracts\Validation\Rule;

class PostMediaCheck implements Rule
{

    public function passes($attribute, $value)
    {
        // validate if media ids belongs to the current user
        $media = Media::whereIn('id', $value)->get();
        foreach ($media as $m) {
            if ($m->user_id != auth()->user()->id) {
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
