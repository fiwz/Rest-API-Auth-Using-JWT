<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// use Spatie\Permission\Exceptions\UnauthorizedException; // Jika anda menggunakan Spatie Permissions
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use BadMethodCallException;
use Exception;

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

        // Custom Handler starts here

        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'error' => 'Method Not Allowed.',
                'success' => false,
                'message' => $e->getMessage(),
            ], SymfonyResponse::HTTP_METHOD_NOT_ALLOWED);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'error' => 'Not Found.',
                'success' => false,
                'message' => $e->getMessage(),
            ], SymfonyResponse::HTTP_NOT_FOUND);
        });

        $this->renderable(function (BadMethodCallException $e) {
            return response()->json([
                'error' => 'Bad Method Call.',
                'success' => false,
                'message' => $e->getMessage(),
            ], SymfonyResponse::HTTP_FORBIDDEN);
        });

        $this->renderable(function (UnauthorizedException $e) {
            return response()->json([
                'error' => 'You do not have the required authorization.',
                'success' => false,
                'message' => $e->getMessage(),
            ], SymfonyResponse::HTTP_UNAUTHORIZED);
        });

        // All Exception goes here, this below code is experimental
        // $this->renderable(function (Exception $e) {
        //     return response()->json([
        //         'error' => 'Error ' . $e->getMessage(),
        //         'success' => false,
        //         'message' => $e->getMessage(),
        //     ], SymfonyResponse::HTTP_BAD_REQUEST);
        // });
    }
}
