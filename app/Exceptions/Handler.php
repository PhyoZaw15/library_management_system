<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\InvalidTokenException;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;


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

        $this->renderable(function (InvalidTokenException $e, Request $request) {
            if ($exception instanceof AuthenticationException && $request->expectsJson()) {
                return response()->json(['message' => 'Invalid token'], 401);
            }
    
            return parent::render($request, $exception);
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Invalid token or unauthorized.'], 401);
        }

        return response()->json(['status' => false, 'error' => 'Invalid token or Unauthorized.'], 401);
        // return redirect()->guest(route('login'));
    }
}
