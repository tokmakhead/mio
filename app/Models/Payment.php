<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\LogsActivity;

class Payment extends Model
{
    use LogsActivity;
    protected $fillable = [
        'invoice_id',
        'customer_id',
        'amount',
        'currency',
        'method',
        'paid_at',
        'note',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
