<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LedgerEntry extends Model
{
    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'currency',
        'ref_type',
        'ref_id',
        'occurred_at',
        'description',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function ref(): MorphTo
    {
        return $this->morphTo();
    }
}
