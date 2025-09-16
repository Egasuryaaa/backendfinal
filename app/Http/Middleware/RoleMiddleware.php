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

        // Check if user has any of the specified roles
        $hasRole = false;
        foreach ($roles as $role) {
            // Use direct relationship check instead of hasRole() method
            if ($user->roles()->where('name', $role)->exists()) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            $rolesList = implode(', ', $roles);
            $userRoles = $user->roles()->pluck('name')->toArray();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Akses ditolak. Role yang diperlukan: {$rolesList}",
                    'required_roles' => $roles,
                    'user_roles' => $userRoles
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
        return $user && $user->roles()->where('name', 'admin')->exists();
    }

    /**
     * Check if user is pembeli/customer
     */
    public static function isPembeli($user): bool
    {
        return $user && ($user->roles()->where('name', 'pembeli')->exists() ||
                        $user->roles()->where('name', 'customer')->exists());
    }

    /**
     * Check if user is penjual biasa (seller)
     */
    public static function isPenjualBiasa($user): bool
    {
        return $user && ($user->roles()->where('name', 'penjual_biasa')->exists() ||
                        $user->roles()->where('name', 'seller')->exists());
    }

    /**
     * Check if user is pengepul
     */
    public static function isPengepul($user): bool
    {
        return $user && $user->roles()->where('name', 'pengepul')->exists();
    }

    /**
     * Check if user is pemilik tambak
     */
    public static function isPemilikTambak($user): bool
    {
        return $user && $user->roles()->where('name', 'pemilik_tambak')->exists();
    }

    /**
     * Check if user is any type of seller (penjual_biasa = seller)
     */
    public static function isSeller($user): bool
    {
        return $user && ($user->roles()->where('name', 'penjual_biasa')->exists() ||
                        $user->roles()->where('name', 'seller')->exists() ||
                        $user->roles()->where('name', 'pemilik_tambak')->exists());
    }

    /**
     * Check if user can manage products
     */
    public static function canManageProducts($user): bool
    {
        return $user && ($user->roles()->where('name', 'admin')->exists() ||
                        $user->roles()->where('name', 'penjual_biasa')->exists() ||
                        $user->roles()->where('name', 'seller')->exists() ||
                        $user->roles()->where('name', 'pemilik_tambak')->exists());
    }

    /**
     * Check if user can make bulk purchases
     */
    public static function canBulkPurchase($user): bool
    {
        return $user && ($user->roles()->where('name', 'admin')->exists() ||
                        $user->roles()->where('name', 'pengepul')->exists());
    }

    /**
     * Check if user can manage tambak
     */
    public static function canManageTambak($user): bool
    {
        return $user && ($user->roles()->where('name', 'admin')->exists() ||
                        $user->roles()->where('name', 'pemilik_tambak')->exists());
    }

    /**
     * Get user role hierarchy level (higher number = more privileges)
     */
    public static function getRoleLevel($user): int
    {
        if (!$user) return 0;

        if ($user->roles()->where('name', 'admin')->exists()) return 100;
        if ($user->roles()->where('name', 'pemilik_tambak')->exists()) return 80;
        if ($user->roles()->where('name', 'pengepul')->exists()) return 70;
        if ($user->roles()->where('name', 'penjual_biasa')->exists() ||
            $user->roles()->where('name', 'seller')->exists()) return 60;
        if ($user->roles()->where('name', 'pembeli')->exists() ||
            $user->roles()->where('name', 'customer')->exists()) return 40;

        return 0;
    }
}
