<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Route;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
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
