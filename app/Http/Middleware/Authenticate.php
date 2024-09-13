<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */

    protected function redirectTo(Request $request): ?string
    {
        // For API requests, don't redirect, just return null (so it doesn't try to redirect)
        if ($request->expectsJson()) {
            return null;
        }
        // If you want to handle non-API requests (for example, for a web app), you can customize this
        return null;
    }
}
