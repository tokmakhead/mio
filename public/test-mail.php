<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    echo "Metin: E-posta gönderimi başlatılıyor (SSL Doğrulaması Devre Dışı)...\n";

    // Bypass SSL verification for testing
    config([
        'mail.mailers.smtp.transport' => 'smtp',
        'mail.mailers.smtp.host' => env('MAIL_HOST', 'mail.mioly.app'),
        'mail.mailers.smtp.port' => env('MAIL_PORT', 465),
        'mail.mailers.smtp.encryption' => env('MAIL_ENCRYPTION', 'ssl'),
        'mail.mailers.smtp.username' => env('MAIL_USERNAME'),
        'mail.mailers.smtp.password' => env('MAIL_PASSWORD'),
        'mail.mailers.smtp.timeout' => null,
        'mail.mailers.smtp.local_domain' => env('MAIL_EHLO_DOMAIN'),
        'mail.mailers.smtp.verify_peer' => false,
    ]);

    Mail::raw('MIONEX SMTP Test Mesajı: Bu e-postayı alıyorsanız sunucu ayarlarınız (SSL doğrulaması atlanarak) başarıyla tamamlanmıştır.', function ($message) {
        $message->to('ercandelican@gmail.com')
            ->subject('MIONEX | SMTP Bağlantı Testi (SSL Bypass)');
    });

    echo "Sonuç: E-posta başarıyla gönderildi! Lütfen ercandelican@gmail.com adresini kontrol edin.\n";
} catch (\Exception $e) {
    echo "HATA Oluştu:\n";
    echo $e->getMessage();
}
