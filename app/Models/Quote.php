<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Quote extends Model
{
    use HasFactory, LogsActivity;

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
        $this->subtotal = number_format($this->items->sum('line_subtotal'), 2, '.', '');
        $this->tax_total = number_format($this->items->sum('line_tax'), 2, '.', '');
        $this->grand_total = number_format((float) $this->subtotal + (float) $this->tax_total - (float) $this->discount_total, 2, '.', '');
        $this->save();
    }

    public static function getStatusLabel($status)
    {
        return match ($status) {
            'draft' => 'Taslak',
            'sent' => 'Gönderildi',
            'accepted' => 'Kabul Edildi',
            'rejected' => 'Reddedildi',
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
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'expired' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Generate a robust sequential quote number
     */
    public static function generateNumber()
    {
        $year = now()->year;
        $prefix = 'TK';

        $lastRecord = self::where('number', 'like', "{$prefix}-{$year}-%")
            ->orderByRaw('CAST(SUBSTRING_INDEX(number, "-", -1) AS UNSIGNED) DESC')
            ->first();

        $nextSequence = 1;
        if ($lastRecord) {
            $parts = explode('-', $lastRecord->number);
            $lastSequence = (int) end($parts);
            $nextSequence = $lastSequence + 1;
        }

        return "{$prefix}-{$year}-" . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);
    }
}
