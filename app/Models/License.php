<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'license_key',
        'client_name',
        'domain',
        'status',
        'expires_at',
        'last_check_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_check_at' => 'datetime',
    ];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
