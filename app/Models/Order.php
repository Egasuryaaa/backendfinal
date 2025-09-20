<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * Tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nomor_pesanan',
        'status',
        'metode_pembayaran',
        'status_pembayaran',
        'id_pembayaran',
        'alamat_id',
        'metode_pengiriman',
        'biaya_kirim',
        'subtotal',
        'pajak',
        'total',
        'catatan',
        'payment_deadline',
        'payment_proof',
        'payment_proof_uploaded_at',
        'verified_at',
        'verified_by',
    ];

    /**
     * Atribut yang dikonversi ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'biaya_kirim' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'pajak' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_deadline' => 'datetime',
        'payment_proof_uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Status pesanan yang tersedia.
     *
     * @var array
     */
    public static $statuses = [
        'menunggu' => 'Menunggu Pembayaran',
        'dibayar' => 'Pembayaran Diterima',
        'diproses' => 'Pesanan Diproses',
        'dikirim' => 'Dalam Pengiriman',
        'selesai' => 'Pesanan Selesai',
        'dibatalkan' => 'Pesanan Dibatalkan'
    ];

    /**
     * Status pembayaran yang tersedia.
     *
     * @var array
     */
    public static $paymentStatuses = [
        'menunggu' => 'Menunggu Pembayaran',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'dibayar' => 'Pembayaran Diterima',
        'gagal' => 'Pembayaran Gagal'
    ];

    /**
     * Mendapatkan user (pembeli) dari pesanan ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan alamat pengiriman untuk pesanan ini.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'alamat_id');
    }

    /**
     * Mendapatkan item-item dalam pesanan ini.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'pesanan_id');
    }

    /**
     * Mendapatkan data pembayaran untuk pesanan ini.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'id', 'order_id');
    }

    /**
     * Mendapatkan semua pembayaran untuk pesanan ini.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    /**
     * Mendapatkan notifikasi terkait pesanan ini.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'pesanan_id');
    }

    /**
     * Scope untuk filter pesanan berdasarkan status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter pesanan berdasarkan status pembayaran.
     */
    public function scopePaymentStatus($query, $status)
    {
        return $query->where('status_pembayaran', $status);
    }

    /**
     * Scope untuk pesanan yang siap untuk dinilai (selesai dan belum direview).
     */
    public function scopeReadyToReview($query)
    {
        return $query->where('status', 'selesai')
            ->whereHas('orderItems', function ($q) {
                $q->whereDoesntHave('review');
            });
    }

    /**
     * Mendapatkan deskripsi status pesanan.
     */
    public function getStatusTextAttribute()
    {
        return self::$statuses[$this->status] ?? $this->status;
    }

    /**
     * Mendapatkan deskripsi status pembayaran.
     */
    public function getPaymentStatusTextAttribute()
    {
        return self::$paymentStatuses[$this->status_pembayaran] ?? $this->status_pembayaran;
    }

    /**
     * Mendapatkan total harga dengan format Rupiah.
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format((float) $this->total, 0, ',', '.');
    }

    /**
     * Mendapatkan subtotal dengan format Rupiah.
     */
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format((float) $this->subtotal, 0, ',', '.');
    }

    /**
     * Mendapatkan biaya pengiriman dengan format Rupiah.
     */
    public function getFormattedShippingAttribute()
    {
        return 'Rp ' . number_format((float) $this->biaya_kirim, 0, ',', '.');
    }

    /**
     * Menghasilkan nomor pesanan unik.
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = mt_rand(1000, 9999);

        $orderNumber = $prefix . $date . $random;

        // Pastikan nomor pesanan unik
        while (self::where('nomor_pesanan', $orderNumber)->exists()) {
            $random = mt_rand(1000, 9999);
            $orderNumber = $prefix . $date . $random;
        }

        return $orderNumber;
    }

    /**
     * Memperbarui status pesanan dan membuat notifikasi.
     */
    public function updateStatus($newStatus)
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;
        $this->save();

        // Buat notifikasi untuk perubahan status
        $this->user->notifications()->create([
            'judul' => 'Status Pesanan Diperbarui',
            'isi' => "Pesanan #{$this->nomor_pesanan} telah diperbarui statusnya menjadi {$this->status_text}.",
            'jenis' => 'pesanan',
            'pesanan_id' => $this->id,
            'tautan' => '/pesanan/' . $this->id,
        ]);

        // Jika status menjadi selesai, perbarui stok produk
        if ($newStatus === 'selesai' && $oldStatus !== 'selesai') {
            // Logic for handling completion (optional)
        }

        return $this;
    }

    /**
     * Memperbarui status pembayaran dan membuat notifikasi.
     */
    public function updatePaymentStatus($newStatus)
    {
        $this->status_pembayaran = $newStatus;

        // Jika pembayaran diterima, update status pesanan menjadi dibayar
        if ($newStatus === 'dibayar') {
            $this->status = 'dibayar';
        }

        $this->save();

        // Buat notifikasi untuk perubahan status pembayaran
        $this->user->notifications()->create([
            'judul' => 'Status Pembayaran Diperbarui',
            'isi' => "Pembayaran untuk pesanan #{$this->nomor_pesanan} telah {$this->payment_status_text}.",
            'jenis' => 'pembayaran',
            'pesanan_id' => $this->id,
            'tautan' => '/pesanan/' . $this->id,
        ]);

        return $this;
    }

    /**
     * Set payment deadline (2 hours from now)
     */
    public function setPaymentDeadline()
    {
        $this->payment_deadline = now()->addHours(2);
        $this->save();

        return $this;
    }

    /**
     * Check if payment deadline has passed
     */
    public function isPaymentExpired(): bool
    {
        if (!$this->payment_deadline) {
            return false;
        }

        return now()->isAfter($this->payment_deadline);
    }

    /**
     * Check if order can be cancelled due to expired payment
     */
    public function canBeCancelledDueToExpiration(): bool
    {
        return $this->status === 'menunggu' &&
               $this->metode_pembayaran === 'manual' &&
               $this->isPaymentExpired();
    }

    /**
     * Cancel order due to payment timeout
     */
    public function cancelDueToTimeout()
    {
        if ($this->canBeCancelledDueToExpiration()) {
            $this->status = 'dibatalkan';
            $this->status_pembayaran = 'gagal';
            $this->save();

            // Create notification
            $this->user->notifications()->create([
                'judul' => 'Pesanan Dibatalkan - Timeout Pembayaran',
                'isi' => "Pesanan #{$this->nomor_pesanan} telah dibatalkan karena tidak ada pembayaran dalam batas waktu 2 jam.",
                'jenis' => 'pesanan',
                'pesanan_id' => $this->id,
                'tautan' => '/orders/' . $this->id,
            ]);
        }

        return $this;
    }

    /**
     * Upload payment proof
     */
    public function uploadPaymentProof($proofPath)
    {
        $this->payment_proof = $proofPath;
        $this->payment_proof_uploaded_at = now();
        $this->status_pembayaran = 'menunggu_verifikasi';
        $this->save();

        // Create notification for user
        $this->user->notifications()->create([
            'judul' => 'Bukti Pembayaran Berhasil Diupload',
            'isi' => "Bukti pembayaran untuk pesanan #{$this->nomor_pesanan} telah berhasil diupload dan sedang menunggu verifikasi.",
            'jenis' => 'pembayaran',
            'pesanan_id' => $this->id,
            'tautan' => '/orders/' . $this->id,
        ]);

        return $this;
    }

    /**
     * Get bank account information from seller
     */
    public function getSellerBankAccount()
    {
        // Get seller from first order item (assuming all items from same seller)
        $firstItem = $this->orderItems()->with('product.seller')->first();

        if ($firstItem && $firstItem->product && $firstItem->product->seller) {
            $seller = $firstItem->product->seller;

            return [
                'bank_name' => $seller->bank_name,
                'account_number' => $seller->account_number,
                'account_holder_name' => $seller->account_holder_name,
                'seller_name' => $seller->name,
            ];
        }

        return null;
    }

    /**
     * Setup for table
     */
    protected static function booted()
    {
        // Generate nomor pesanan sebelum membuat pesanan baru
        static::creating(function ($order) {
            if (!$order->nomor_pesanan) {
                $order->nomor_pesanan = self::generateOrderNumber();
            }
        });
    }
}
