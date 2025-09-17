<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collector extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'lokasi_koordinat',
        'alamat',
        'no_telepon',
        'jenis_ikan_diterima',
        'rate_harga_per_kg',
        'kapasitas_maksimal',
        'jam_operasional_mulai',
        'jam_operasional_selesai',
        'foto',
        'status',
        'deskripsi'
    ];

    protected $casts = [
        'lokasi_koordinat' => 'array',
        'jenis_ikan_diterima' => 'array',
        'rate_harga_per_kg' => 'decimal:2',
        'kapasitas_maksimal' => 'decimal:2'
    ];

    /**
     * Get the user that owns the collector business
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get appointments for this collector
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'collector_id');
    }

    /**
     * Get nearby fish farms within specified distance
     */
    public function getNearbyFishFarms($maxDistance = 50)
    {
        if (!$this->lokasi_koordinat) {
            return collect([]);
        }

        return FishFarm::where('status', 'aktif')
            ->get()
            ->filter(function ($fishFarm) use ($maxDistance) {
                if (!$fishFarm->lokasi_koordinat) {
                    return false;
                }

                $distance = $this->calculateDistance(
                    $this->lokasi_koordinat['lat'],
                    $this->lokasi_koordinat['lng'],
                    $fishFarm->lokasi_koordinat['lat'],
                    $fishFarm->lokasi_koordinat['lng']
                );

                return $distance <= $maxDistance;
            })
            ->map(function ($fishFarm) {
                $distance = $this->calculateDistance(
                    $this->lokasi_koordinat['lat'],
                    $this->lokasi_koordinat['lng'],
                    $fishFarm->lokasi_koordinat['lat'],
                    $fishFarm->lokasi_koordinat['lng']
                );
                
                $fishFarm->distance = round($distance, 2);
                return $fishFarm;
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
     * Check if collector accepts specific fish type
     */
    public function acceptsFishType($fishType)
    {
        if (!$this->jenis_ikan_diterima) {
            return false;
        }

        return in_array(strtolower($fishType), array_map('strtolower', $this->jenis_ikan_diterima));
    }

    /**
     * Get potential earnings from a fish farm
     */
    public function calculateEarnings($fishFarm)
    {
        if (!$this->acceptsFishType($fishFarm->jenis_ikan)) {
            return 0;
        }

        $estimatedProduction = $fishFarm->estimated_production;
        return $estimatedProduction * $this->rate_per_kg;
    }
}
