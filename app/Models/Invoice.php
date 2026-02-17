<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Invoice extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'customer_id',
        'quote_id',
        'number',
        'status',
        'currency',
        'discount_total',
        'subtotal',
        'tax_total',
        'grand_total',
        'paid_amount',
        'issue_date',
        'due_date',
        'sent_at',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'discount_total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->grand_total - $this->paid_amount;
    }

    public function calculateTotals()
    {
        $this->subtotal = number_format($this->items->sum('line_subtotal'), 2, '.', '');
        $this->tax_total = number_format($this->items->sum('line_tax'), 2, '.', '');
        $this->grand_total = number_format((float) $this->subtotal + (float) $this->tax_total - (float) $this->discount_total, 2, '.', '');
        $this->save();
    }

    public function updateStatus()
    {
        if ($this->grand_total > 0 && (float) $this->paid_amount >= (float) $this->grand_total) {
            $this->status = 'paid';
            if (!$this->paid_at) {
                $this->paid_at = now();
            }
        } elseif ((float) $this->paid_amount > 0) {
            $this->status = 'partial';
            $this->paid_at = null; // Clear if it was fully paid before
        } else {
            // Zero or negative payment
            $this->paid_at = null;

            // If it's not draft or cancelled, determine if it's sent or overdue
            if (!in_array($this->status, ['draft', 'cancelled'])) {
                if ($this->due_date && $this->due_date < now()->startOfDay()) {
                    $this->status = 'overdue';
                } else {
                    $this->status = 'sent';
                }
            }
        }

        $this->save();
    }

    public static function getStatusLabel($status)
    {
        return match ($status) {
            'draft' => 'Taslak',
            'sent' => 'Gönderildi',
            'partial' => 'Kısmi Ödeme',
            'paid' => 'Ödendi',
            'overdue' => 'Vadesi Geçmiş',
            'cancelled' => 'İptal Edildi',
            default => $status,
        };
    }

    public static function getStatusColor($status)
    {
        return match ($status) {
            'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'sent' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'partial' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'cancelled' => 'bg-gray-500 text-white',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Generate a robust sequential invoice number
     */
    public static function generateNumber()
    {
        $year = now()->year;
        $prefix = 'FAT';

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
