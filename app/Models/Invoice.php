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
        $this->subtotal = (string) $this->items->sum('line_subtotal');
        $this->tax_total = (string) $this->items->sum('line_tax');
        $this->grand_total = (string) ((float) $this->subtotal + (float) $this->tax_total - (float) $this->discount_total);
        $this->save();
    }

    public function updateStatus()
    {
        if ($this->paid_amount >= $this->grand_total) {
            $this->status = 'paid';
            if (!$this->paid_at)
                $this->paid_at = now();
        } elseif ($this->due_date < now()->startOfDay() && $this->status !== 'cancelled') {
            $this->status = 'overdue';
        }

        $this->save();
    }

    public static function getStatusLabel($status)
    {
        return match ($status) {
            'draft' => 'Taslak',
            'sent' => 'Gönderildi',
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
            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'cancelled' => 'bg-gray-500 text-white',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
