<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        return response()->json([
            'status' => 'error',
            'message' => $this->getMessage($exception),
        ], $this->getStatusCode($exception));
    }

    private function getStatusCode(Throwable $e)
    {
        return match (true) {
            $e instanceof AuthenticationException => Response::HTTP_UNAUTHORIZED, // 401
            $e instanceof ModelNotFoundException => Response::HTTP_NOT_FOUND, // 404
            $e instanceof ValidationException => Response::HTTP_UNPROCESSABLE_ENTITY, // 422
            $e instanceof HttpException => $e->getStatusCode(), // personalizados
            default => Response::HTTP_INTERNAL_SERVER_ERROR, // 500
        };
    }

    private function getMessage(Throwable $e)
    {
        return match (true) {
            $e instanceof ValidationException => $e->errors(),
            $e instanceof ModelNotFoundException => 'Resource not found',
            default => $e->getMessage(),
        };
    }
}
