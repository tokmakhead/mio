<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterRelease extends Model
{
    protected $fillable = [
        'version',
        'release_notes',
        'is_critical',
        'requirements',
        'download_count',
        'published_at',
    ];

    protected $casts = [
        'is_critical' => 'boolean',
        'requirements' => 'array',
        'download_count' => 'integer',
        'published_at' => 'datetime',
    ];
}
