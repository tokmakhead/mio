<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = ['provider', 'is_active', 'config'];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'encrypted:array',
    ];
}
