<?php

namespace App\Services;

class LocationService
{
    /**
     * Calculate distance between two coordinates using Haversine formula
     *
     * @param float $lat1 Latitude of first point
     * @param float $lon1 Longitude of first point
     * @param float $lat2 Latitude of second point
     * @param float $lon2 Longitude of second point
     * @param string $unit Unit of measurement (km, mi, nm)
     * @return float Distance in specified unit
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2, string $unit = 'km'): float
    {
        // Convert degrees to radians
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        // Calculate differences
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        // Haversine formula
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Earth's radius in kilometers
        $earthRadius = 6371;
        
        // Calculate distance in kilometers
        $distance = $earthRadius * $c;

        // Convert to requested unit
        switch (strtolower($unit)) {
            case 'mi':
            case 'miles':
                return $distance * 0.621371; // Convert km to miles
            case 'nm':
            case 'nautical':
                return $distance * 0.539957; // Convert km to nautical miles
            case 'km':
            case 'kilometers':
            default:
                return $distance;
        }
    }

    /**
     * Calculate distance between two users
     *
     * @param \App\Models\User $user1
     * @param \App\Models\User $user2
     * @param string $unit
     * @return float|null
     */
    public static function calculateDistanceBetweenUsers($user1, $user2, string $unit = 'km'): ?float
    {
        if (!$user1->hasCoordinates() || !$user2->hasCoordinates()) {
            return null;
        }

        return self::calculateDistance(
            $user1->latitude,
            $user1->longitude,
            $user2->latitude,
            $user2->longitude,
            $unit
        );
    }

    /**
     * Find nearest users within a radius
     *
     * @param \App\Models\User $centerUser
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $maxDistance Maximum distance in km
     * @param string $unit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findNearestUsers($centerUser, $query, float $maxDistance = 50, string $unit = 'km')
    {
        if (!$centerUser->hasCoordinates()) {
            return collect();
        }

        // Get users with coordinates
        $users = $query->whereNotNull('latitude')
                      ->whereNotNull('longitude')
                      ->where('id', '!=', $centerUser->id)
                      ->get();

        // Calculate distances and filter
        $nearbyUsers = $users->map(function ($user) use ($centerUser, $unit) {
            $distance = self::calculateDistanceBetweenUsers($centerUser, $user, $unit);
            $user->distance = $distance;
            return $user;
        })
        ->filter(function ($user) use ($maxDistance) {
            return $user->distance !== null && $user->distance <= $maxDistance;
        })
        ->sortBy('distance');

        return $nearbyUsers;
    }

    /**
     * Add distance calculation to a collection of users
     *
     * @param \App\Models\User $centerUser
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @param string $unit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function addDistanceToUsers($centerUser, $users, string $unit = 'km')
    {
        if (!$centerUser->hasCoordinates()) {
            return $users->map(function ($user) {
                $user->distance = null;
                return $user;
            });
        }

        return $users->map(function ($user) use ($centerUser, $unit) {
            $user->distance = self::calculateDistanceBetweenUsers($centerUser, $user, $unit);
            return $user;
        });
    }

    /**
     * Sort users by distance
     *
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function sortUsersByDistance($users, string $direction = 'asc')
    {
        return $users->sortBy('distance', SORT_REGULAR, $direction === 'desc');
    }

    /**
     * Validate coordinates
     *
     * @param float $latitude
     * @param float $longitude
     * @return bool
     */
    public static function validateCoordinates(float $latitude, float $longitude): bool
    {
        return $latitude >= -90 && $latitude <= 90 && $longitude >= -180 && $longitude <= 180;
    }

    /**
     * Get formatted distance string
     *
     * @param float|null $distance
     * @param string $unit
     * @return string
     */
    public static function formatDistance(?float $distance, string $unit = 'km'): string
    {
        if ($distance === null) {
            return 'Jarak tidak diketahui';
        }

        $unitLabel = match ($unit) {
            'mi', 'miles' => 'mil',
            'nm', 'nautical' => 'mil laut',
            default => 'km'
        };

        return number_format($distance, 2) . ' ' . $unitLabel;
    }
}