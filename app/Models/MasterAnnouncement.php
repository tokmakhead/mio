<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAnnouncement extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'target_type',
        'master_license_id',
        'display_mode',
        'is_active',
        'is_dismissible',
        'is_priority',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_dismissible' => 'boolean',
        'is_priority' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function license()
    {
        return $this->belongsTo(MasterLicense::class, 'master_license_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }
}
