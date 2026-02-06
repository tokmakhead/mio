<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'number',
        'status',
        'currency',
        'discount_total',
        'subtotal',
        'tax_total',
        'grand_total',
        'valid_until',
        'sent_at',
        'accepted_at',
        'notes',
    ];

    protected $casts = [
        'discount_total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'valid_until' => 'date',
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function calculateTotals()
    {
        $this->subtotal = (string) $this->items->sum('line_subtotal');
        $this->tax_total = (string) $this->items->sum('line_tax');
        $this->grand_total = (string) ((float) $this->subtotal + (float) $this->tax_total - (float) $this->discount_total);
        $this->save();
    }

    public static function getStatusLabel($status)
    {
        return match ($status) {
            'draft' => 'Taslak',
            'sent' => 'Gönderildi',
            'accepted' => 'Kabul Edildi',
            'expired' => 'Süresi Dolmuş',
            default => $status,
        };
    }

    public static function getStatusColor($status)
    {
        return match ($status) {
            'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'sent' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'accepted' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'expired' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
