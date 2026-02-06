<h1>Yeni Teklif</h1>
<p>Sayın {{ $quote->customer->name }},</p>
<p>İsteğiniz üzerine hazırladığımız <strong>{{ $quote->number }}</strong> numaralı teklifimiz ekteki dökümanda yer
    almaktadır.</p>
<p><strong>Toplam Tutar:</strong> {{ number_format($quote->grand_total, 2) }} {{ $quote->currency }}</p>
<p><strong>Geçerlilik:</strong> {{ $quote->valid_until->format('d.m.Y') }}</p>
<p>Teklifimizi kabul etmek veya sorularınız için bu e-postayı yanıtlayabilirsiniz.</p>
<br>
<p>İyi çalışmalar,<br>MIOLY Ekibi</p>