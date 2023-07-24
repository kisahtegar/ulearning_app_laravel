<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // All we need to do is changing the body of the register method. Instead of returning reportable() 
        // we will return renderable(). Inside this we catch the error and return in a json format.
        $this->renderable(function (Throwable $e) {
            //
            return response(['error' => $e->getMessage()], $e->getCode() ?: 400);
        });
    }
}
