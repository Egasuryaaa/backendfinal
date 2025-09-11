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
        // If the request expects JSON (API request), don't redirect
        if ($request->expectsJson()) {
            return null;
        }
        
        // For web requests, redirect to login
        return route('login');
    }
}
