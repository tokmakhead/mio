<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    protected $fillable = [
        'driver',
        'host',
        'port',
        'username',
        'password_encrypted',
        'encryption',
        'from_email',
        'from_name',
    ];

    protected $hidden = [
        'password_encrypted',
    ];
}
