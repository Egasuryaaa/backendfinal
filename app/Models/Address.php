<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property int $user_id
 * @property string $nama_penerima
 * @property string $nomor_telepon
 * @property string $alamat_lengkap
 * @property string $kecamatan
 * @property string $kota
 * @property string $provinsi
 * @property string $kode_pos
 * @property bool $utama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read string $full_address
 * @property-read string $short_address
 */


class Address extends Model
{
    use HasFactory;

    /**
     * Tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'addresses';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'label',
        'nama_penerima',
        'telepon',
        'alamat_lengkap',
        'provinsi',
        'kota',
        'kecamatan',
        'kode_pos',
        'utama',
        'catatan',
    ];

    /**
     * Atribut yang dikonversi ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'utama' => 'boolean',
    ];

    /**
     * Mendapatkan user yang memiliki alamat ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan pesanan yang menggunakan alamat ini.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'alamat_id');
    }

    /**
     * Scope untuk filter alamat utama.
     */
    public function scopeMain($query)
    {
        return $query->where('utama', true);
    }

    /**
     * Mendapatkan alamat lengkap dengan format.
     */
    public function getFullAddressAttribute()
    {
        return "{$this->alamat_lengkap}, {$this->kecamatan}, {$this->kota}, {$this->provinsi} {$this->kode_pos}";
    }

    /**
     * Mendapatkan alamat singkat.
     */
    public function getShortAddressAttribute()
    {
        return "{$this->kecamatan}, {$this->kota}, {$this->provinsi}";
    }

    /**
     * Mendapatkan label yang ditampilkan.
     */
    public function getDisplayLabelAttribute()
    {
        return $this->label ?: 'Alamat';
    }

    /**
     * Menjadikan alamat sebagai utama dan mengupdate alamat lain.
     */
    public function setAsMain()
    {
        // Ubah semua alamat user menjadi non-utama
        Address::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['utama' => false]);

        // Set alamat ini sebagai utama
        $this->utama = true;
        $this->save();

        return $this;
    }
}