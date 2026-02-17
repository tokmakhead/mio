<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    echo "Metin: E-posta gönderimi başlatılıyor...\n";

    Mail::raw('MIONEX SMTP Test Mesajı: Bu e-postayı alıyorsanız sunucu ayarlarınız başarıyla tamamlanmıştır.', function ($message) {
        $message->to('ercandelican@gmail.com')
            ->subject('MIONEX | SMTP Bağlantı Testi');
    });

    echo "Sonuç: E-posta başarıyla gönderildi! Lütfen ercandelican@gmail.com adresini kontrol edin.\n";
} catch (\Exception $e) {
    echo "HATA Oluştu:\n";
    echo $e->getMessage();
}
