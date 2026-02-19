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
        'discount_type',
        'discount_rate',
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
        $this->subtotal = (float) number_format($this->items->sum('line_subtotal'), 2, '.', '');
        $this->tax_total = (float) number_format($this->items->sum('line_tax'), 2, '.', '');

        if ($this->discount_type === 'percentage') {
            $this->discount_total = (float) number_format((float) $this->subtotal * ($this->discount_rate / 100), 2, '.', '');
        } else {
            $this->discount_total = (float) number_format((float) $this->discount_rate, 2, '.', '');
        }

        $this->grand_total = (float) number_format((float) $this->subtotal + (float) $this->tax_total - (float) $this->discount_total, 2, '.', '');
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
        $settings = \App\Models\SystemSetting::first();
        $prefix = $settings->invoice_prefix ?? 'FAT';
        $startNumber = $settings->invoice_start_number ?? 1;

        $year = now()->year;

        // Handle dynamic placeholders
        $prefix = str_replace(['{YEAR}', '{Y}'], [$year, substr($year, -2)], $prefix);

        // If prefix doesn't contain YEAR tag but user wants year-based prefix, they should set it in settings.
        // Default behavior: just use the prefix as is.
        // Format: PREFIX-SEQUENCE (e.g. FAT2024-00001) or just PREFIX-SEQUENCE (FAT-00001)

        // To find the last record, we need to match the prefix pattern.
        // If the prefix changes annually (contains year), the sequence will naturally reset because matching records won't be found.

        $lastRecord = self::where('number', 'like', "{$prefix}%")
            ->orderByRaw('LENGTH(number) DESC') // Order by length first to handle 1 vs 10 correctly if not padded
            ->orderBy('number', 'desc')
            ->first();

        // Extract sequence
        if ($lastRecord) {
            // Assuming format is PREFIX-SEQUENCE or PREFIXSEQUENCE
            // Remove prefix from the start
            $sequenceStr = substr($lastRecord->number, strlen($prefix));
            // Remove any separator if present (e.g. -)
            // But wait, the separator might be part of the prefix or added automatically?
            // Let's assume the user puts the separator in the prefix if they want it, OR we enforce a standard.
            // Current standard was PREFIX-YEAR-SEQUENCE.
            // New standard: Just use the defined prefix. 
            // BUT: If the user didn't put a separator in the prefix, `FAT202400001` is hard to parse if no separator.
            // Let's assume we ALWAYS append a hyphen between prefix and sequence IF the prefix doesn't end with one.

            // Actually, let's keep it simple. We filter by `like prefix%`.
            // We strip the prefix. The rest is the number.

            // Sanitize separator handling:
            // If prefix ends with -, leave it. If not, maybe check if the number has -, ...
            // Best approach: trusting the user set `FAT-{YEAR}-` as prefix.
            // If they just set `FAT`, we might want `FAT-00001`.

            // Let's check if the last record implies a separator. 
            // Actually, let's look at `SystemSetting` defaults. 
            // If I change the logic, I might break existing numbering if they rely on hardcoded `FAT-`.
            // Existing logic enforced: `FAT-{YEAR}-`.

            // Proposed Logic:
            // 1. Get Prefix. Replace Tags.
            // 2. Search `number LIKE 'Prefix%'`.
            // 3. Last Number - Prefix = Sequence (Int).
            // 4. Increment.

            $sequencePart = substr($lastRecord->number, strlen($prefix));
            // Remove leading hyphen if we added one automatically? 
            // If we assume the stored number is exactly what we generated, 
            // then we should generate it consistently.

            // Let's assume we simply concatenate Prefix + PaddedSequence.

            $currentSequence = (int) $sequencePart;
            $nextSequence = $currentSequence + 1;
        } else {
            $nextSequence = $startNumber;
        }

        return $prefix . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);
    }
}
