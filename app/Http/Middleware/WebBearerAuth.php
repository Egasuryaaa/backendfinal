<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class WebBearerAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // First, check if there's already a session-based auth
        if (Auth::check()) {
            return $next($request);
        }

        // Check for token in various places
        $token = $this->getTokenFromRequest($request);
        
        if (!$token) {
            // If no token and this is an AJAX request, return 401
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            
            // For web requests to protected routes, redirect to login
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Try to authenticate with the token
        $user = $this->authenticateWithToken($token);
        
        if (!$user) {
            // Clear invalid token from cookie
            if ($request->hasCookie('auth_token')) {
                return redirect()->route('login')
                    ->withCookie(cookie()->forget('auth_token'))
                    ->with('error', 'Sesi telah berakhir, silakan login kembali');
            }
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid token'], 401);
            }
            
            return redirect()->route('login')->with('error', 'Token tidak valid, silakan login kembali');
        }

        // Log the user in for this request
        Auth::login($user);
        
        return $next($request);
    }

    /**
     * Get token from various sources in order of preference
     */
    private function getTokenFromRequest(Request $request)
    {
        // 1. From Authorization header
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            return substr($authHeader, 7);
        }

        // 2. From URL parameter (for dashboard redirects)
        if ($request->has('token')) {
            return $request->get('token');
        }

        // 3. From cookie
        if ($request->hasCookie('auth_token')) {
            return $request->cookie('auth_token');
        }

        return null;
    }

    /**
     * Authenticate user with token
     */
    private function authenticateWithToken($token)
    {
        try {
            $accessToken = PersonalAccessToken::findToken($token);
            
            if (!$accessToken || !$accessToken->tokenable) {
                return null;
            }

            // Check if token is expired
            if ($accessToken->expires_at && $accessToken->expires_at->isPast()) {
                $accessToken->delete();
                return null;
            }

            return $accessToken->tokenable;
        } catch (\Exception $e) {
            \Log::error('Token authentication error: ' . $e->getMessage());
            return null;
        }
    }
}
