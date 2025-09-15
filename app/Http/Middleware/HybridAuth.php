<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpFoundation\Response;

class HybridAuth
{
    /**
     * Handle an incoming request.
     * Checks both session authentication and Sanctum token authentication
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via session
        if (Auth::check()) {
            return $next($request);
        }

        // Check if user is authenticated via Sanctum token
        if (Auth::guard('sanctum')->check()) {
            return $next($request);
        }

        // Check for token in cookie (from auth.js)
        $token = $request->cookie('auth_token');
        if ($token) {
            // Try to authenticate with the token
            $request->headers->set('Authorization', 'Bearer ' . $token);
            if (Auth::guard('sanctum')->check()) {
                return $next($request);
            }
        }

        // If not authenticated, redirect to login for web requests
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return redirect()->route('login');
    }
}
