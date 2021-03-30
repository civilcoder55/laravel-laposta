<?php

namespace App\Exceptions;

use Exception;

class BadAccountException extends Exception
{
    // Exception when try to hit wrong social account
    public function render()
    {
        return redirect()->back()->with('error', "Not Supported Account");
    }
}
