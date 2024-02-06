<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    /**
     * unauthenticated
     * Custom error handler for unauthorized user
     *
     * @param  mixed $request
     * @param  mixed $guards
     * @return void
     */
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json([
            'error' => 'You do not have the required authorization.',
            'success' => false,
            'message' => 'Unauthorized.',
        ], Response::HTTP_UNAUTHORIZED));
    }
}
