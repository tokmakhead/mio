<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLicense extends Model
{
    protected $fillable = [
        'code',
        'client_name',
        'client_email',
        'type',
        'price',
        'currency',
        'billing_cycle',
        'features',
        'status',
        'is_strict',
        'activation_limit',
        'expires_at',
        'trial_ends_at',
        'last_sync_at',
    ];

    protected $casts = [
        'is_strict' => 'boolean',
        'features' => 'array',
        'price' => 'decimal:2',
        'expires_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'last_sync_at' => 'datetime',
    ];

    /**
     * Check if a specific feature is enabled
     */
    public function hasFeature($feature): bool
    {
        return isset($this->features[$feature]) && $this->features[$feature] === true;
    }

    /**
     * Check if the license is expired or trial ended
     */
    public function isExpired(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return true;
        }

        if ($this->trial_ends_at && $this->trial_ends_at->isPast()) {
            return true;
        }

        return false;
    }

    /**
     * Check if it's currently in trial mode
     */
    public function isTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function instances()
    {
        return $this->hasMany(MasterLicenseInstance::class);
    }
}
