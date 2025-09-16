<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FishFarm extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'banyak_bibit',
        'lokasi_koordinat',
        'alamat',
        'jenis_ikan',
        'luas_tambak',
        'foto',
        'no_telepon',
        'status',
        'deskripsi'
    ];

    protected $casts = [
        'lokasi_koordinat' => 'array',
        'luas_tambak' => 'decimal:2',
        'banyak_bibit' => 'integer'
    ];

    /**
     * Get the user that owns the fish farm
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get appointments for this fish farm
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'fish_farm_id');
    }

    /**
     * Get available collectors based on distance
     */
    public function getAvailableCollectors($maxDistance = 50)
    {
        if (!$this->lokasi_koordinat) {
            return collect([]);
        }

        return Collector::where('status', 'aktif')
            ->get()
            ->filter(function ($collector) use ($maxDistance) {
                if (!$collector->lokasi_koordinat) {
                    return false;
                }

                $distance = $this->calculateDistance(
                    $this->lokasi_koordinat['lat'],
                    $this->lokasi_koordinat['lng'],
                    $collector->lokasi_koordinat['lat'],
                    $collector->lokasi_koordinat['lng']
                );

                return $distance <= $maxDistance;
            })
            ->map(function ($collector) {
                $distance = $this->calculateDistance(
                    $this->lokasi_koordinat['lat'],
                    $this->lokasi_koordinat['lng'],
                    $collector->lokasi_koordinat['lat'],
                    $collector->lokasi_koordinat['lng']
                );
                
                $collector->distance = round($distance, 2);
                return $collector;
            })
            ->sortBy('distance');
    }

    /**
     * Calculate distance between coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // kilometers
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    /**
     * Get estimated production in kg
     */
    public function getEstimatedProductionAttribute()
    {
        // Assuming 80% survival rate and 0.5kg average fish weight
        return $this->banyak_bibit * 0.8 * 0.5;
    }
}
