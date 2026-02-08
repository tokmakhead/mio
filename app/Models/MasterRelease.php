<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterRelease extends Model
{
    protected $fillable = [
        'version',
        'release_notes',
        'file_path',
        'is_critical',
        'published_at',
    ];

    protected $casts = [
        'is_critical' => 'boolean',
        'published_at' => 'datetime',
    ];
}
