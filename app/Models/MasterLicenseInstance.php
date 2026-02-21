<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLicenseInstance extends Model
{
    protected $fillable = [
        'master_license_id',
        'domain',
        'ip_address',
        'version',
        'last_heard_at',
    ];

    protected $casts = [
        'last_heard_at' => 'datetime',
    ];

    public function license()
    {
        return $this->belongsTo(MasterLicense::class, 'master_license_id');
    }
}
