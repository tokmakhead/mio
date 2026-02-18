<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #374151; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .header { border-bottom: 2px solid #a82244; padding-bottom: 10px; margin-bottom: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #a82244; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #a82244; margin: 0;">Yeni Fatura: {{ $invoice->number }}</h2>
        </div>
        
        <p>Sayın <strong>{{ $invoice->customer->name }}</strong>,</p>
        
        <p>Hesabınız için <strong>{{ $invoice->number }}</strong> numaralı yeni bir fatura düzenlenmiştir.</p>
        
        <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Toplam Tutar:</strong> {{ number_format($invoice->grand_total, 2) }} {{ $invoice->currency }}</p>
            <p style="margin: 5px 0;"><strong>Son Ödeme Tarihi:</strong> {{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-' }}</p>
        </div>
        
        <p>Faturanıza ait detaylı döküm ekteki PDF dosyasında yer almaktadır. Ayrıca aşağıdaki butona tıklayarak sistem üzerinden faturayı görüntüleyebilirsiniz:</p>
        
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn">Faturayı Görüntüle</a>
        </p>
        
        <p>Sorularınız olması durumunda bu e-postayı yanıtlayarak bize ulaşabilirsiniz.</p>
        
        <div class="footer">
            <p>Bu e-posta <strong>MIONEX</strong> sistemi tarafından otomatik olarak gönderilmiştir.</p>
        </div>
    </div>
</body>
</html>