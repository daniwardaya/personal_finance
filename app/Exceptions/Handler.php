<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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
            // Tangani pengecualian TokenExpiredException
        if ($exception instanceof TokenExpiredException) {
            return response()->json([
                'message' => 'Token has expired, please refresh your token',
            ], 401); // Kode status 401 Unauthorized
        }

        // Tangani pengecualian lainnya (misalnya JWTException)
        if ($exception instanceof JWTException) {
            return response()->json([
                'message' => 'Token is invalid or malformed',
            ], 401);
        }


        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'message' => 'Too many requests, please try again later.',
                'error' => 'Rate Limit Exceeded',
            ], 429);
        }

        if ($exception instanceof UnauthorizedHttpException) {
            // Cek jika token expired dan tangani dengan custom message
            if (str_contains($exception->getMessage(), 'Token has expired')) {
                return response()->json([
                    'message' => 'Token has expired, please refresh your token',
                ], 401);
            }
    
            return response()->json([
                'message' => 'Unauthorized, please provide a valid token',
            ], 401);
        }

        return parent::render($request, $exception);
    }
}