<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Service extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'customer_id',
        'provider_id',
        'type',
        'name',
        'identifier_code',
        'cycle',
        'payment_type',
        'status',
        'currency',
        'price',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relations
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('end_date', '<=', now()->addDays($days))
            ->where('end_date', '>=', now())
            ->where('status', 'active');
    }

    /**
     * Accessors
     */

    // MRR (Monthly Recurring Revenue)
    public function getMrrAttribute(): float
    {
        if ($this->status !== 'active') {
            return 0;
        }

        return match ($this->cycle) {
            'monthly' => (float) $this->price,
            'quarterly' => (float) $this->price / 3,
            'yearly' => (float) $this->price / 12,
            'biennial' => (float) $this->price / 24,
            'custom' => 0,
            default => 0,
        };
    }

    // ARR (Annual Recurring Revenue)
    public function getArrAttribute(): float
    {
        return $this->mrr * 12;
    }

    // Days Until Expiry
    public function getDaysUntilExpiryAttribute(): int
    {
        return now()->diffInDays($this->end_date, false);
    }

    // Expiry Status Color
    public function getExpiryColorAttribute(): string
    {
        $days = $this->days_until_expiry;

        if ($days < 0)
            return 'text-gray-500 dark:text-gray-400'; // Expired
        if ($days < 30)
            return 'text-danger-600 dark:text-danger-400';
        if ($days < 60)
            return 'text-warning-600 dark:text-warning-400';
        if ($days < 90)
            return 'text-success-600 dark:text-success-400';
        return 'text-gray-700 dark:text-gray-300';
    }

    /**
     * Helper Methods
     */
    public static function getTypeBadgeColor(string $type): string
    {
        return Provider::getTypeBadgeColor($type);
    }

    public static function getTypeLabel(string $type): string
    {
        return Provider::getTypeLabel($type);
    }

    public static function getCycleLabel(string $cycle): string
    {
        return match ($cycle) {
            'monthly' => 'Aylık',
            'quarterly' => '3 Aylık',
            'yearly' => 'Yıllık',
            'biennial' => '2 Yıllık',
            'custom' => 'Özel',
            default => $cycle,
        };
    }

    public static function getStatusDotColor(string $status): string
    {
        return match ($status) {
            'active' => 'bg-success-500',
            'suspended' => 'bg-warning-500',
            'cancelled' => 'bg-danger-500',
            'expired' => 'bg-gray-500',
            default => 'bg-gray-500',
        };
    }

    public static function getStatusLabel(string $status): string
    {
        return match ($status) {
            'active' => 'Aktif',
            'suspended' => 'Askıda',
            'cancelled' => 'İptal',
            'expired' => 'Süresi Dolmuş',
            default => $status,
        };
    }

    public static function getPaymentTypeLabel(string $paymentType): string
    {
        return match ($paymentType) {
            'installment' => 'Taksitli',
            'upfront' => 'Peşin',
            default => $paymentType,
        };
    }
}
