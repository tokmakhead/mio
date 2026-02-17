<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\LogsActivity;

class Customer extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'mobile_phone',
        'website',
        'address',
        'city',
        'district',
        'postal_code',
        'country',
        'tax_or_identity_number',
        'invoice_address',
        'status',
        'notes',
    ];

    protected $casts = [
        'invoice_address' => 'array',
        'status' => 'string',
        'type' => 'string',
    ];

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeIndividual($query)
    {
        return $query->where('type', 'individual');
    }

    public function scopeCorporate($query)
    {
        return $query->where('type', 'corporate');
    }

    /**
     * Accessors
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->district,
            $this->city,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Relations
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class);
    }

    /**
     * Get balances grouped by currency
     */
    public function getBalancesAttribute()
    {
        return $this->ledgerEntries()
            ->select('currency')
            ->selectRaw('SUM(CASE WHEN type = \'debit\' THEN amount ELSE 0 END) as debits')
            ->selectRaw('SUM(CASE WHEN type = \'credit\' THEN amount ELSE 0 END) as credits')
            ->groupBy('currency')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->currency => (float) $item->debits - (float) $item->credits];
            });
    }

    /**
     * Legacy balance attribute (Defaults to TRY or first currency found)
     */
    public function getBalanceAttribute()
    {
        $balances = $this->balances;
        return $balances['TRY'] ?? ($balances->first() ?? 0);
    }
}
