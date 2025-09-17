<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Appointment
 *
 * @property int $id
 * @property int $pembeli_id
 * @property int $penjual_id
 * @property int $lokasi_penjual_id
 * @property \Illuminate\Support\Carbon $tanggal_temu
 * @property \Illuminate\Support\Carbon $waktu_mulai
 * @property \Illuminate\Support\Carbon $waktu_selesai
 * @property string $status
 * @property string|null $catatan_pembeli
 * @property string|null $catatan_penjual
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $buyer
 * @property-read \App\Models\User $seller
 * @property-read \App\Models\SellerLocation $sellerLocation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read string $status_label
 * @property-read string $formatted_date
 * @property-read string $formatted_time_range
 */


class Appointment extends Model
{
    use HasFactory;

    /**
     * Tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'appointments';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'penjual_id',
        'pembeli_id',
        'lokasi_penjual_id',
        'fish_farm_id',
        'collector_id',
        'tanggal_janji',
        'tanggal',
        'jenis',
        'status',
        'tujuan',
        'catatan',
        'catatan_collector',
        'catatan_selesai',
        'meeting_location',
        'perkiraan_berat',
        'berat_aktual',
        'harga_per_kg',
        'harga_final',
        'total_estimasi',
        'total_final',
        'total_aktual',
        'kualitas_ikan',
        'whatsapp_sent',
        'tanggal_selesai'
    ];

    /**
     * Atribut yang dikonversi ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_janji' => 'datetime',
        'tanggal' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'meeting_location' => 'array',
        'perkiraan_berat' => 'decimal:2',
        'berat_aktual' => 'decimal:2',
        'harga_per_kg' => 'decimal:2',
        'harga_final' => 'decimal:2',
        'total_estimasi' => 'decimal:2',
        'total_final' => 'decimal:2',
        'total_aktual' => 'decimal:2',
        'whatsapp_sent' => 'boolean'
    ];

    /**
     * Status janji temu yang tersedia.
     *
     * @var array
     */
    public static $statuses = [
        'menunggu' => 'Menunggu Konfirmasi',
        'dikonfirmasi' => 'Dikonfirmasi',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    /**
     * Mendapatkan user (penjual) dari janji temu ini.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penjual_id');
    }

    /**
     * Mendapatkan user (pembeli) dari janji temu ini.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembeli_id');
    }

    /**
     * Mendapatkan lokasi penjual untuk janji temu ini.
     */
    public function sellerLocation(): BelongsTo
    {
        return $this->belongsTo(SellerLocation::class, 'lokasi_penjual_id');
    }

    /**
     * Mendapatkan fish farm yang terkait dengan appointment ini.
     */
    public function fishFarm(): BelongsTo
    {
        return $this->belongsTo(FishFarm::class, 'fish_farm_id');
    }

    /**
     * Mendapatkan collector yang terkait dengan appointment ini.
     */
    public function collector(): BelongsTo
    {
        return $this->belongsTo(Collector::class, 'collector_id');
    }

    /**
     * Mendapatkan user yang membuat appointment ini (penjual/pemilik tambak).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penjual_id');
    }

    /**
     * Mendapatkan penjual/pemilik tambak.
     */
    public function penjual(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penjual_id');
    }

    /**
     * Mendapatkan pembeli/pengepul.
     */
    public function pembeli(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembeli_id');
    }

    /**
     * Mendapatkan pesan-pesan yang terkait dengan janji temu ini.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'janji_temu_id');
    }

    /**
     * Mendapatkan notifikasi yang terkait dengan janji temu ini.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'janji_temu_id');
    }

    /**
     * Scope untuk filter berdasarkan status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter janji temu yang akan datang.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_janji', '>=', now())
            ->whereIn('status', ['menunggu', 'dikonfirmasi']);
    }

    /**
     * Scope untuk filter janji temu yang sudah lewat.
     */
    public function scopePast($query)
    {
        return $query->where('tanggal_janji', '<', now())
            ->orWhereIn('status', ['selesai', 'dibatalkan']);
    }

    /**
     * Mendapatkan deskripsi status janji temu.
     */
    public function getStatusTextAttribute()
    {
        return self::$statuses[$this->status] ?? $this->status;
    }

    /**
     * Mendapatkan format tanggal untuk janji temu.
     */
    public function getFormattedDateAttribute()
    {
        return $this->tanggal_janji->translatedFormat('l, d F Y');
    }

    /**
     * Mendapatkan format waktu untuk janji temu.
     */
    public function getFormattedTimeAttribute()
    {
        return $this->tanggal_janji->format('H:i');
    }

    /**
     * Memeriksa apakah janji temu sudah lewat.
     */
    public function isPast()
    {
        return $this->tanggal_janji < now();
    }

    /**
     * Memperbarui status janji temu dan membuat notifikasi.
     */
    public function updateStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->save();

        // Buat notifikasi untuk penjual
        $this->seller->notifications()->create([
            'judul' => 'Status Janji Temu Diperbarui',
            'isi' => "Janji temu dengan {$this->buyer->name} telah diperbarui statusnya menjadi {$this->status_text}.",
            'jenis' => 'janji_temu',
            'janji_temu_id' => $this->id,
            'tautan' => '/janji-temu/' . $this->id,
        ]);

        // Buat notifikasi untuk pembeli
        $this->buyer->notifications()->create([
            'judul' => 'Status Janji Temu Diperbarui',
            'isi' => "Janji temu dengan {$this->seller->name} telah diperbarui statusnya menjadi {$this->status_text}.",
            'jenis' => 'janji_temu',
            'janji_temu_id' => $this->id,
            'tautan' => '/janji-temu/' . $this->id,
        ]);

        return $this;
    }
}
