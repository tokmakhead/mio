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
        'status',
        'domain',
        'ip_address',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
