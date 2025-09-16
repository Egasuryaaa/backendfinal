<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  Multiple roles can be passed
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Silahkan login terlebih dahulu.'
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu.');
        }

        $user = $request->user();

        // If no roles specified, just check if authenticated
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has any of the specified roles using enum column
        $hasRole = false;
        foreach ($roles as $role) {
            if ($user->role === $role) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            $rolesList = implode(', ', $roles);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Akses ditolak. Role yang diperlukan: {$rolesList}",
                    'required_roles' => $roles,
                    'user_role' => $user->role
                ], 403);
            }

            return redirect()->back()->with('error', "Akses ditolak. Anda memerlukan role: {$rolesList}");
        }

        return $next($request);
    }

        /**
     * Check if user has admin role
     */
    public static function isAdmin($user): bool
    {
        return $user && $user->role === 'admin';
    }

    /**
     * Check if user is pembeli/customer
     */
    public static function isPembeli($user): bool
    {
        return $user && $user->role === 'pembeli';
    }

    /**
     * Check if user is penjual biasa (seller)
     */
    public static function isPenjualBiasa($user): bool
    {
        return $user && $user->role === 'penjual_biasa';
    }

    /**
     * Check if user is pengepul
     */
    public static function isPengepul($user): bool
    {
        return $user && $user->role === 'pengepul';
    }

    /**
     * Check if user is pemilik tambak
     */
    public static function isPemilikTambak($user): bool
    {
        return $user && $user->role === 'pemilik_tambak';
    }

    /**
     * Check if user is any type of seller (penjual_biasa or pemilik_tambak)
     */
    public static function isSeller($user): bool
    {
        return $user && in_array($user->role, ['penjual_biasa', 'pemilik_tambak']);
    }

    /**
     * Check if user can manage products
     */
    public static function canManageProducts($user): bool
    {
        return $user && in_array($user->role, ['admin', 'penjual_biasa', 'pemilik_tambak']);
    }

    /**
     * Check if user can make bulk purchases
     */
    public static function canBulkPurchase($user): bool
    {
        return $user && in_array($user->role, ['admin', 'pengepul']);
    }

    /**
     * Check if user can manage tambak
     */
    public static function canManageTambak($user): bool
    {
        return $user && in_array($user->role, ['admin', 'pemilik_tambak']);
    }

    /**
     * Get user role hierarchy level (higher number = more privileges)
     */
    public static function getRoleLevel($user): int
    {
        if (!$user) return 0;

        return match($user->role) {
            'admin' => 100,
            'pemilik_tambak' => 80,
            'pengepul' => 70,
            'penjual_biasa' => 60,
            'pembeli' => 40,
            default => 0
        };
    }
}
