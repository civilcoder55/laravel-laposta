<?php

namespace App\PostaBot\Exceptions;

use Exception;

class TokenizerException extends Exception
{
    public $error;
    public function __construct($error)
    {
        $this->error = $error;
    }
    public function render()
    {
        return redirect()->route('accounts.index')->with('error', $this->error);
    }
}
