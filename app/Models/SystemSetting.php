<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'site_name',
        'logo_path',
        'favicon_path',
        'default_currency',
        'default_vat_rate',
        'withholding_rate',
        'bank_name',
        'iban',
        'bank_account_info',
        'invoice_prefix',
        'invoice_start_number',
        'quote_prefix',
        'quote_start_number',
        'timezone',
        'locale',
    ];
}
