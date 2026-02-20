<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EmailTemplate;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        EmailTemplate::where('type', 'service_expiry')->update([
            'html_body' => '<p>Sayın <strong>{{customer_name}}</strong>,</p>
<p>Aşağıda detayları belirtilen hizmetinizin süresi dolmak üzeredir. Kesinti yaşamamanız için süresi dolmadan yenilemenizi öneririz.</p>
<div style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin-bottom: 20px; border: 1px solid #f3f4f6;">
    <p style="margin:0"><strong>Hizmet:</strong> {{service_name}}</p>
    <p style="margin:0"><strong>Bitiş Tarihi:</strong> <span style="color: #ef4444;">{{end_date}}</span></p>
    <p style="margin:0"><strong>Kalan Gün:</strong> {{days_left}}</p>
</div>
<p>Yenileme yapmak veya detayları incelemek için aşağıdaki butonu kullanabilirsiniz.</p>
<p align="center">
    <a href="{{renew_url}}" style="display: inline-block; padding: 12px 24px; background-color: #dc2626; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600;">Hizmeti Yenile</a>
</p>'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
