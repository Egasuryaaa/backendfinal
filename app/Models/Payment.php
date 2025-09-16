<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'payment_id',
        'order_id',
        'invoice_id', // Changed from xendit_invoice_id to match migration
        'external_id', // Changed from xendit_external_id to match migration
        'payment_method',
        'payment_channel',
        'amount',
        'status',
        'invoice_url',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Get the order that owns the payment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->getAttribute('status') === self::STATUS_PENDING;
    }

    /**
     * Check if payment is paid.
     */
    public function isPaid(): bool
    {
        return $this->getAttribute('status') === self::STATUS_PAID;
    }

    /**
     * Check if payment is failed.
     */
    public function isFailed(): bool
    {
        return $this->getAttribute('status') === self::STATUS_FAILED;
    }

    /**
     * Check if payment is expired.
     */
    public function isExpired(): bool
    {
        return $this->getAttribute('status') === self::STATUS_EXPIRED;
    }

    /**
     * Check if payment is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->getAttribute('status') === self::STATUS_CANCELLED;
    }

    /**
     * Mark payment as paid.
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed(): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
        ]);
    }

    /**
     * Mark payment as expired.
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    /**
     * Mark payment as cancelled.
     */
    public function markAsCancelled(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);
    }

    /**
     * Get formatted amount in Rupiah.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->getAttribute('amount'), 0, ',', '.');
    }

    /**
     * Get status text in Indonesian.
     */
    public function getStatusTextAttribute(): string
    {
        $statusTexts = [
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PAID => 'Pembayaran Berhasil',
            self::STATUS_FAILED => 'Pembayaran Gagal',
            self::STATUS_EXPIRED => 'Pembayaran Kedaluwarsa',
            self::STATUS_CANCELLED => 'Pembayaran Dibatalkan',
        ];

        return $statusTexts[$this->getAttribute('status')] ?? 'Status Tidak Diketahui';
    }
}
