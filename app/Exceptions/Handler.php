<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Route;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->renderable(function (Exception $e, $request) {
            if (($p = $e->getPrevious()) instanceof AuthorizationException) {
                $route = Route::current()->getName();
                if ($route == "posts.edit") {
                    return redirect()->route('posts.review', Route::current()->parameters()['post']);
                } elseif ($route == "posts.review") {
                    return redirect()->route('posts.edit', Route::current()->parameters()['post']);
                }
            }

        });
    }

}
