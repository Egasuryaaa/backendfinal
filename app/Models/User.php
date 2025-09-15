<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Default guard name untuk role system
     */
    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Mendapatkan alamat-alamat user.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Mendapatkan keranjang user.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Mendapatkan produk yang dijual oleh user (penjual).
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'penjual_id');
    }

    /**
     * Mendapatkan pesanan user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Mendapatkan lokasi penjual (plural).
     */
    public function sellerLocations()
    {
        return $this->hasMany(SellerLocation::class);
    }
    
    /**
     * Mendapatkan lokasi penjual utama (singular).
     */
    public function sellerLocation()
    {
        return $this->hasOne(SellerLocation::class)->latest();
    }

    /**
     * Mendapatkan ulasan yang diberikan oleh user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Mendapatkan notifikasi yang diterima oleh user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Role Helper Methods
     */
    
    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is pembeli/customer
     */
    public function isPembeli(): bool
    {
        return $this->hasRole('pembeli');
    }

    /**
     * Check if user is penjual biasa
     */
    public function isPenjualBiasa(): bool
    {
        return $this->hasRole('penjual_biasa');
    }

    /**
     * Check if user is pengepul
     */
    public function isPengepul(): bool
    {
        return $this->hasRole('pengepul');
    }

    /**
     * Check if user is pemilik tambak
     */
    public function isPemilikTambak(): bool
    {
        return $this->hasRole('pemilik_tambak');
    }

    /**
     * Check if user is any type of seller (penjual_biasa or pemilik_tambak)
     */
    public function isSeller(): bool
    {
        return $this->hasAnyRole(['penjual_biasa', 'pemilik_tambak']);
    }

    /**
     * Check if user can manage products (seller, admin)
     */
    public function canManageProducts(): bool
    {
        return $this->hasAnyRole(['admin', 'penjual_biasa', 'pemilik_tambak']);
    }

    /**
     * Check if user can make bulk purchases (pengepul, admin)
     */
    public function canBulkPurchase(): bool
    {
        return $this->hasAnyRole(['admin', 'pengepul']);
    }

    /**
     * Check if user can manage tambak (pemilik_tambak, admin)
     */
    public function canManageTambak(): bool
    {
        return $this->hasAnyRole(['admin', 'pemilik_tambak']);
    }

    /**
     * Get user's primary role name
     */
    public function getPrimaryRole(): ?string
    {
        $role = $this->roles()->first();
        return $role ? $role->name : null;
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayName(): string
    {
        $roleNames = [
            'admin' => 'Administrator',
            'pembeli' => 'Pembeli',
            'penjual_biasa' => 'Penjual Biasa',
            'pengepul' => 'Pengepul',
            'pemilik_tambak' => 'Pemilik Tambak'
        ];

        $primaryRole = $this->getPrimaryRole();
        return $roleNames[$primaryRole] ?? 'Unknown';
    }

    /**
     * Assign default role to user (pembeli)
     */
    public function assignDefaultRole(): void
    {
        if (!$this->hasAnyRole(['admin', 'pembeli', 'penjual_biasa', 'pengepul', 'pemilik_tambak'])) {
            $this->assignRole('pembeli');
        }
    }
}