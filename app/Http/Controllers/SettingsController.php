<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BrandSetting;
use App\Models\EmailSetting;
use App\Models\EmailTemplate;
use App\Models\SystemSetting;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Services\UpdateService;

class SettingsController extends Controller
{
    // SMTP Settings
    public function smtp()
    {
        $settings = EmailSetting::first() ?? new EmailSetting();
        return view('settings.smtp', compact('settings'));
    }

    public function updateSmtp(Request $request)
    {
        $data = $request->validate([
            'driver' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|string',
            'username' => 'nullable|string',
            'encryption' => 'nullable|string',
            'from_email' => 'required|email',
            'from_name' => 'required|string',
        ]);

        if ($request->filled('password')) {
            $data['password_encrypted'] = Crypt::encryptString($request->password);
        }

        EmailSetting::updateOrCreate(['id' => 1], $data);

        return back()->with('success', 'SMTP ayarları güncellendi.');
    }

    public function testSmtp(Request $request)
    {
        $request->validate(['to' => 'required|email']);

        $settings = EmailSetting::first();
        if (!$settings) {
            return back()->with('error', 'Önce SMTP ayarlarını kaydedin.');
        }

        try {
            config([
                'mail.mailers.smtp.host' => $settings->host,
                'mail.mailers.smtp.port' => $settings->port,
                'mail.mailers.smtp.username' => $settings->username,
                'mail.mailers.smtp.password' => $settings->password_encrypted ? Crypt::decryptString($settings->password_encrypted) : null,
                'mail.mailers.smtp.encryption' => $settings->encryption,
                'mail.from.address' => $settings->from_email,
                'mail.from.name' => $settings->from_name,
            ]);

            Mail::raw('Bu bir test e-postasıdır. SMTP ayarlarınız doğru çalışıyor.', function ($message) use ($request) {
                $message->to($request->to)
                    ->subject('SMTP Test E-postası');
            });

            return back()->with('success', 'Test e-postası başarıyla gönderildi.');
        } catch (\Exception $e) {
            return back()->with('error', 'E-posta gönderilemedi: ' . $e->getMessage());
        }
    }

    // Email Templates
    public function emailTemplates()
    {
        $templates = EmailTemplate::all();
        // Seed default templates if empty
        if ($templates->isEmpty()) {
            $defaults = [
                ['type' => 'welcome', 'subject' => 'Hoş Geldiniz', 'html_body' => '<p>Merhaba {{customer_name}}, aramıza hoş geldiniz.</p>'],
                ['type' => 'quote', 'subject' => 'Yeni Teklifiniz: {{quote_number}}', 'html_body' => '<p>Sayın {{customer_name}}, teklifiniz ektedir.</p>'],
                ['type' => 'invoice', 'subject' => 'Yeni Faturanız: {{invoice_number}}', 'html_body' => '<p>Sayın {{customer_name}}, {{invoice_number}} numaralı faturanız ektedir.</p>'],
                [
                    'type' => 'service_expiry',
                    'subject' => 'Hizmet Süresi Doluyor: {{service_name}}',
                    'html_body' => '<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; }
            .content { padding: 20px !important; }
            .cta-button { width: 100% !important; display: block !important; margin-bottom: 10px !important; }
        }
        @media (prefers-color-scheme: dark) {
            body { background-color: #111827 !important; color: #f9fafb !important; }
            .container { background-color: #1f2937 !important; border-color: #374151 !important; }
            .info-box { background-color: #374151 !important; }
            .text-gray-600 { color: #9ca3af !important; }
            .text-gray-900 { color: #f9fafb !important; }
            .bank-info { background-color: #111827 !important; border-color: #374151 !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; color: #1f2937;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table class="container" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                    <tr>
                        <td align="center" style="padding: 40px 40px 20px 40px; background-color: #a82244;">
                            <img src="{{logo_url}}" alt="MIONEX" width="120" style="display: block; margin-bottom: 20px; filter: brightness(0) invert(1);">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700;">Hizmet Süresi Dolma Uyarısı</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content" style="padding: 40px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6;">Sayın <strong>{{customer_name}}</strong>,</p>
                            <p style="margin: 0 0 30px 0; font-size: 16px; line-height: 1.6;">Aşağıda detayları belirtilen hizmetinizin süresi dolmak üzeredir. Kesinti yaşamamanız için süresi dolmadan yenilemenizi öneririz.</p>
                            <div class="info-box" style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin-bottom: 30px; border: 1px solid #f3f4f6;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td style="padding-bottom: 10px;" width="120"><span style="color: #6b7280; font-size: 14px;">Hizmet Türü:</span></td>
                                        <td style="padding-bottom: 10px;"><span style="color: #111827; font-size: 14px; font-weight: 600;">{{service_type}}</span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 10px;"><span style="color: #6b7280; font-size: 14px;">Hizmet Adı:</span></td>
                                        <td style="padding-bottom: 10px;"><span style="color: #111827; font-size: 14px; font-weight: 600;">{{service_name}}</span></td>
                                    </tr>
                                    <tr>
                                        <td><span style="color: #6b7280; font-size: 14px;">Bitiş Tarihi:</span></td>
                                        <td><span style="color: #ef4444; font-size: 14px; font-weight: 600;">{{end_date}}</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div style="background-color: {{days_left_color}}; border-radius: 6px; padding: 12px; text-align: center; margin-bottom: 30px;">
                                <span style="color: #ffffff; font-weight: 700; font-size: 16px;">Hizmetin dolmasına {{days_left}} gün kaldı!</span>
                            </div>
                            <h3 style="font-size: 18px; margin: 0 0 15px 0;">Yenileme Seçenekleri</h3>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px 0 0 8px;">1 Yıllık Yenileme</td>
                                    <td align="right" style="padding: 12px; border: 1px solid #e5e7eb; border-radius: 0 8px 8px 0; font-weight: 700;">{{renewal_price_1y}}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px; border: 1px solid #e5e7eb; border-top: none;">2 Yıllık Yenileme</td>
                                    <td align="right" style="padding: 12px; border: 1px solid #e5e7eb; border-top: none; font-weight: 700;">{{renewal_price_2y}}</td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{service_url}}" class="cta-button" style="display: inline-block; padding: 14px 24px; background-color: #ffffff; color: #a82244; text-decoration: none; border: 2px solid #a82244; border-radius: 8px; font-weight: 600; font-size: 14px; margin-right: 10px;">Hizmeti Görüntüle</a>
                                        <a href="{{renew_url}}" class="cta-button" style="display: inline-block; padding: 14px 24px; background-color: #a82244; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; box-shadow: 0 4px 6px rgba(168, 34, 68, 0.2);">Hizmeti Yenile</a>
                                    </td>
                                </tr>
                            </table>
                            <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
                                <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Banka Bilgileri</h4>
                                <div class="bank-info" style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 15px;">
                                    <p style="margin: 0; font-size: 14px; line-height: 1.6;">
                                        <strong>Banka:</strong> {{bank_name}}<br>
                                        <strong>IBAN:</strong> <span style="font-family: monospace;">{{iban}}</span><br>
                                        <strong>Not:</strong> {{bank_info}}
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px 40px; background-color: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #9ca3af; line-height: 1.5;">Bu otomatik bir bilgilendirme mesajıdır. Lütfen bu e-postayı yanıtlamayınız.</p>
                            <p style="margin: 10px 0 0 0; font-size: 12px; color: #9ca3af;">E-posta düzgün görüntülenmiyorsa <a href="{{fallback_url}}" style="color: #a82244; text-decoration: underline;">buraya tıklayarak</a> tarayıcıda görüntüleyebilirsiniz.</p>
                            <p style="margin: 20px 0 0 0; font-size: 12px; color: #d1d5db;">&copy; {{year}} MIONEX. Tüm hakları saklıdır.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>'
                ],
            ];
            foreach ($defaults as $default) {
                EmailTemplate::create($default);
            }
            $templates = EmailTemplate::all();
        }

        return view('settings.email_templates', compact('templates'));
    }

    public function updateEmailTemplate(Request $request, EmailTemplate $template)
    {
        $data = $request->validate([
            'subject' => 'required|string',
            'html_body' => 'required|string',
            'enabled' => 'boolean',
        ]);

        $data['enabled'] = $request->has('enabled');

        $template->update($data);

        return back()->with('success', 'Şablon güncellendi.');
    }
    public function testEmailTemplate(Request $request, EmailTemplate $template)
    {
        $request->validate(['to' => 'required|email']);

        $settings = EmailSetting::first();
        if (!$settings) {
            return back()->with('error', 'Önce SMTP ayarlarını kaydedin.');
        }

        try {
            config([
                'mail.mailers.smtp.host' => $settings->host,
                'mail.mailers.smtp.port' => $settings->port,
                'mail.mailers.smtp.username' => $settings->username,
                'mail.mailers.smtp.password' => $settings->password_encrypted ? Crypt::decryptString($settings->password_encrypted) : null,
                'mail.mailers.smtp.encryption' => $settings->encryption,
                'mail.from.address' => $settings->from_email,
                'mail.from.name' => $settings->from_name,
            ]);

            // Simple replacement for test
            $body = $template->html_body;
            $body = str_replace('{{customer_name}}', 'Test Müşteri', $body);
            $body = str_replace('{{service_name}}', 'Test Hizmeti', $body);

            Mail::html($body, function ($message) use ($request, $template) {
                $message->to($request->to)
                    ->subject('[TEST] ' . $template->subject);
            });

            return back()->with('success', 'Test e-postası başarıyla gönderildi.');
        } catch (\Exception $e) {
            return back()->with('error', 'E-posta gönderilemedi: ' . $e->getMessage());
        }
    }

    // Site Settings
    public function site()
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);
        return view('settings.site', compact('settings'));
    }

    public function updateSite(Request $request)
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);

        $data = $request->validate([
            'site_name' => 'required|string',
            'default_currency' => 'required|string',
            'default_vat_rate' => 'required|numeric',
            'withholding_rate' => 'required|numeric',
        ]);

        $settings->update($data);

        return back()->with('success', 'Site ayarları güncellendi.');
    }

    // Financial Settings
    public function financial()
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);
        return view('settings.financial', compact('settings'));
    }

    public function updateFinancial(Request $request)
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);

        $data = $request->validate([
            'bank_name' => 'nullable|string',
            'iban' => 'nullable|string',
            'bank_account_info' => 'nullable|string',
            'invoice_prefix' => 'required|string',
            'invoice_start_number' => 'required|integer',
            'quote_prefix' => 'required|string',
            'quote_start_number' => 'required|integer',
        ]);

        $settings->update($data);

        return back()->with('success', 'Finansal ayarlar güncellendi.');
    }

    // System Settings
    public function system()
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);

        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        if (File::exists($logFile)) {
            $logs = array_slice(file($logFile), -100);
        }

        return view('settings.system', compact('settings', 'logs'));
    }

    public function updateSystem(Request $request)
    {
        $settings = SystemSetting::firstOrCreate(['id' => 1]);

        $data = $request->validate([
            'timezone' => 'required|string',
            'locale' => 'required|string',
        ]);

        $settings->update($data);

        // Update App Config (Runtime only, for persistent config change .env or config files would be needed)
        // For now storing in DB is enough for application logic that reads from DB

        return back()->with('success', 'Sistem ayarları güncellendi.');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');

        return back()->with('success', 'Önbellek temizlendi.');
    }

    public function clearLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        if (File::exists($logFile)) {
            File::put($logFile, '');
        }

        return back()->with('success', 'Log dosyası temizlendi.');
    }

    // Brand Settings
    public function brand()
    {
        $settings = BrandSetting::all()->pluck('value', 'key');
        return view('settings.brand', compact('settings'));
    }

    public function updateBrand(Request $request)
    {
        $data = $request->validate([
            'site_title' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'logo' => 'nullable|image|max:2048', // 2MB Max
            'favicon' => 'nullable|image|max:1024', // 1MB Max
            'login_image' => 'nullable|image|max:4096', // 4MB Max
        ]);

        // Handle Text Fields
        $textFields = ['site_title', 'primary_color'];
        foreach ($textFields as $field) {
            if ($request->has($field)) {
                BrandSetting::updateOrCreate(
                    ['key' => $field],
                    ['value' => $request->input($field)]
                );

                // Sync site_title with SystemSetting
                if ($field === 'site_title') {
                    $systemSettings = SystemSetting::firstOrCreate(['id' => 1]);
                    $systemSettings->update(['site_name' => $request->input($field)]);
                }
            }
        }

        // Handle File Uploads
        $fileFields = [
            'logo' => 'logo_path',
            'favicon' => 'favicon_path',
            'login_image' => 'login_image_path'
        ];

        foreach ($fileFields as $inputName => $dbKey) {
            if ($request->hasFile($inputName)) {
                // Store file publically
                $path = $request->file($inputName)->store('public/brand');
                $url = Storage::url($path);

                BrandSetting::updateOrCreate(
                    ['key' => $dbKey],
                    ['value' => $url]
                );
            }
        }

        // Clear cache and view cache to reflect changes immediately
        \Illuminate\Support\Facades\Cache::forget('brand_settings');
        Artisan::call('view:clear');

        return back()->with('success', 'Marka ayarları güncellendi.');
    }

    // Payment Gateways
    public function gateways()
    {
        $gateways = PaymentGateway::all()->keyBy('provider');
        return view('settings.gateways', compact('gateways'));
    }

    public function updateGateways(Request $request)
    {
        $data = $request->validate([
            'stripe.api_key' => 'nullable|string',
            'stripe.secret_key' => 'nullable|string',
            'stripe.webhook_secret' => 'nullable|string',
            'iyzico.api_key' => 'nullable|string',
            'iyzico.secret_key' => 'nullable|string',
            'iyzico.base_url' => 'nullable|string',
            'paytr.merchant_id' => 'nullable|string',
            'paytr.merchant_key' => 'nullable|string',
            'paytr.merchant_salt' => 'nullable|string',
            'param.client_code' => 'nullable|string',
            'param.client_username' => 'nullable|string',
            'param.client_password' => 'nullable|string',
            'param.guid' => 'nullable|string',
            'paypal.client_id' => 'nullable|string',
            'paypal.secret' => 'nullable|string',
        ]);

        // Process Stripe
        $stripeConfig = [
            'api_key' => $request->input('stripe.api_key'),
            'secret_key' => $request->input('stripe.secret_key'),
            'webhook_secret' => $request->input('stripe.webhook_secret'),
            'mode' => $request->has('stripe.sandbox') ? 'sandbox' : 'live',
        ];

        PaymentGateway::updateOrCreate(
            ['provider' => 'stripe'],
            [
                'is_active' => $request->has('stripe.active'),
                'config' => $stripeConfig
            ]
        );

        // Process Iyzico
        $iyzicoConfig = [
            'api_key' => $request->input('iyzico.api_key'),
            'secret_key' => $request->input('iyzico.secret_key'),
            'base_url' => $request->input('iyzico.base_url'),
            'mode' => $request->has('iyzico.sandbox') ? 'sandbox' : 'live',
        ];

        PaymentGateway::updateOrCreate(
            ['provider' => 'iyzico'],
            [
                'is_active' => $request->has('iyzico.active'),
                'config' => $iyzicoConfig
            ]
        );

        // Process PayTR
        $paytrConfig = [
            'merchant_id' => $request->input('paytr.merchant_id'),
            'merchant_key' => $request->input('paytr.merchant_key'),
            'merchant_salt' => $request->input('paytr.merchant_salt'),
            'mode' => $request->has('paytr.sandbox') ? 'sandbox' : 'live',
        ];

        PaymentGateway::updateOrCreate(
            ['provider' => 'paytr'],
            [
                'is_active' => $request->has('paytr.active'),
                'config' => $paytrConfig
            ]
        );

        // Process Param
        $paramConfig = [
            'client_code' => $request->input('param.client_code'),
            'client_username' => $request->input('param.client_username'),
            'client_password' => $request->input('param.client_password'),
            'guid' => $request->input('param.guid'),
            'mode' => $request->has('param.sandbox') ? 'sandbox' : 'live',
        ];

        PaymentGateway::updateOrCreate(
            ['provider' => 'param'],
            [
                'is_active' => $request->has('param.active'),
                'config' => $paramConfig
            ]
        );

        // Process PayPal
        $paypalConfig = [
            'client_id' => $request->input('paypal.client_id'),
            'secret' => $request->input('paypal.secret'),
            'mode' => $request->has('paypal.sandbox') ? 'sandbox' : 'live',
        ];

        PaymentGateway::updateOrCreate(
            ['provider' => 'paypal'],
            [
                'is_active' => $request->has('paypal.active'),
                'config' => $paypalConfig
            ]
        );

        return back()->with('success', 'Ödeme altyapı ayarları güncellendi.');
    }

    public function checkUpdate(\App\Services\UpdateService $updater)
    {
        $result = $updater->check();

        if ($result['update_available']) {
            return back()->with('update_info', $result);
        }

        return back()->with('success', 'Sisteminiz güncel! Mevcut Sürüm: ' . ($result['current_version'] ?? 'Unknown'));
    }
}
