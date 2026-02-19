<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'branch_name',
        'branch_code',
        'account_number',
        'iban',
        'currency',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Scope for active accounts
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('bank_name');
    }

    // Helper to format IBAN
    public function getFormattedIbanAttribute()
    {
        return chunk_split($this->iban, 4, ' ');
    }
}
