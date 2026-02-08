<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'type',
        'subject',
        'html_body',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
