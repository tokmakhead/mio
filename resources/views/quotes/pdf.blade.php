<!DOCTYPE html>
<html lang="tr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8">
    <title>{{ $quote->number }} - {{ $quote->customer->name }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #334155;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        /* Dynamic Brand Color */
        :root {
            --primary:
                {{ $brandSettings['primary_color'] ?? '#881337' }}
            ;
        }

        .header-bg {
            background-color: var(--primary);
            height: 120px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .container {
            padding: 40px;
        }

        /* Branding Section */
        .brand-section {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }

        .brand-logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 9px;
            margin-top: 2px;
        }

        /* Doc Title */
        .doc-title {
            text-align: right;
            color: white;
        }

        .doc-title h1 {
            font-size: 32px;
            font-weight: 300;
            margin: 0;
            letter-spacing: 2px;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .doc-number {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 5px;
        }

        /* Grid Layout */
        .row {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .col-6 {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        /* Info Cards */
        .info-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 15px;
            margin-right: 10px;
        }

        .label {
            color: #64748b;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
        }

        .value {
            font-size: 11px;
            font-weight: 500;
            color: #0f172a;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 10px;
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 12px 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Totals */
        .totals-section {
            display: table;
            width: 100%;
            margin-top: 20px;
        }

        .notes-area {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            padding-right: 20px;
        }

        .totals-area {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }

        .totals-table td {
            padding: 6px 0;
            text-align: right;
        }

        .total-row {
            font-size: 14px;
            font-weight: bold;
            color: var(--primary);
            border-top: 2px solid #e2e8f0;
            padding-top: 10px;
        }

        /* Footer */
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f8fafc;
            padding: 20px 40px;
            border-top: 1px solid #e2e8f0;
        }

        .footer-row {
            display: table;
            width: 100%;
        }

        .footer-col {
            display: table-cell;
            width: 33%;
            vertical-align: top;
            font-size: 8px;
            color: #64748b;
        }
    </style>
</head>

<body>
    <div class="header-bg" style="background-color: {{ $brandSettings['primary_color'] ?? '#881337' }}"></div>

    <div class="container">
        <!-- Header -->
        <div class="brand-section">
            <div style="display: table-cell; vertical-align: middle;">
                <div class="brand-logo">{{ $brandSettings['site_title'] ?? 'MIONEX' }}</div>
                <div class="brand-subtitle">Müşteri Odaklı Lojistik ve Yönetim</div>
            </div>
            <div style="display: table-cell; vertical-align: middle;" class="doc-title">
                <h1>TEKLİF FORMU</h1>
                <div class="doc-number">#{{ $quote->number }}</div>
            </div>
        </div>

        <!-- Meta Grid -->
        <div class="row">
            <div class="col-6">
                <div class="info-card">
                    <span class="label">Teklif Sahibi</span>
                    <div class="value font-bold">{{ $brandSettings['site_title'] ?? 'MIONEX' }}</div>
                    <div class="value">{{ $brandSettings['company_address'] ?? 'Adres Girilmedi' }}</div>
                    <div class="value">{{ $brandSettings['company_email'] ?? 'E-posta Girilmedi' }}</div>
                    @if(isset($brandSettings['company_mersis']) || isset($brandSettings['company_tax_id']))
                        <div class="value" style="margin-top: 5px; font-size: 9px; opacity: 0.8;">
                            {{ isset($brandSettings['company_mersis']) ? 'Mersis: ' . $brandSettings['company_mersis'] : '' }}
                            {{ isset($brandSettings['company_tax_office']) ? ' ' . $brandSettings['company_tax_office'] : '' }}
                            {{ isset($brandSettings['company_tax_id']) ? ' Vergi: ' . $brandSettings['company_tax_id'] : '' }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-6" style="padding-left: 10px;">
                <div class="info-card" style="background: white; border: 1px solid #e2e8f0;">
                    <span class="label">Sayın</span>
                    <div class="value font-bold" style="font-size: 13px;">{{ $quote->customer->name }}</div>
                    <div class="value">{{ $quote->customer->address }}</div>
                    <div class="value">{{ $quote->customer->phone }}</div>
                    <div class="value">{{ $quote->customer->email }}</div>
                </div>
            </div>
        </div>

        <!-- Dates -->
        <div class="row" style="margin-top: -15px;">
            <div class="col-6"></div>
            <div class="col-6" style="padding-left: 10px;">
                <table style="width: 100%">
                    <tr>
                        <td>
                            <span class="label">Teklif Tarihi</span>
                            <div class="value">{{ $quote->created_at->format('d.m.Y') }}</div>
                        </td>
                        <td>
                            <span class="label">Geçerlilik Tarihi</span>
                            <div class="value font-bold text-red-600">
                                {{ $quote->valid_until->format('d.m.Y') }}
                            </div>
                        </td>
                        <td class="text-right">
                            <span class="label">Para Birimi</span>
                            <div class="value">{{ $quote->currency }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Hizmet / Açıklama</th>
                    <th class="text-center" style="width: 10%;">Miktar</th>
                    <th class="text-right" style="width: 15%;">Birim Fiyat</th>
                    <th class="text-right" style="width: 10%;">KDV</th>
                    <th class="text-right" style="width: 20%;">Toplam</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->items as $item)
                    <tr>
                        <td>
                            <div class="font-bold">{{ $item->description }}</div>
                            @if($item->service)
                                <div style="font-size: 8px; color: #94a3b8;">{{ $item->service->identifier_code }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($item->qty, 0) }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ (int) $item->vat_rate }}%</td>
                        <td class="text-right font-bold">{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals & Notes -->
        <div class="totals-section">
            <div class="notes-area">
                <div
                    style="background: #ffffff; padding: 15px; border-left: 3px solid {{ $brandSettings['primary_color'] ?? '#881337' }};">
                    <span class="label">Notlar & Açıklamalar</span>
                    <p style="font-size: 10px; margin: 5px 0 0 0; color: #64748b;">
                        {{ $quote->notes ?: 'Tüm şartlar ve geçerlilik süreleri bu teklifte belirtildiği gibidir.' }}
                    </p>
                </div>

                <div style="margin-top: 30px;">
                    <span class="label">Şartlar</span>
                    <ul style="font-size: 9px; color: #64748b; padding-left: 15px; margin-top: 5px;">
                        <li>Bu teklif belirtilen süre boyunca geçerlidir.</li>
                        <li>Ödeme şartları sözleşme aşamasında netleştirilecektir.</li>
                        <li>Tüm fiyatlara belirtilen oranlarda KDV dahildir.</li>
                    </ul>
                </div>
            </div>

            <div class="totals-area">
                <table class="totals-table">
                    <tr>
                        <td>Ara Toplam</td>
                        <td class="font-bold">{{ number_format($quote->subtotal, 2) }} {{ $quote->currency }}</td>
                    </tr>
                    <tr>
                        <td>Toplam KDV</td>
                        <td>{{ number_format($quote->tax_total, 2) }} {{ $quote->currency }}</td>
                    </tr>
                    @if($quote->discount_total > 0)
                        <tr>
                            <td style="color: #ef4444;">İndirim</td>
                            <td style="color: #ef4444;">-{{ number_format($quote->discount_total, 2) }}
                                {{ $quote->currency }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="padding: 10px 0;">
                            <div class="total-row" style="color: {{ $brandSettings['primary_color'] ?? '#881337' }}">
                                <span style="float:left; font-size: 11px; margin-top: 3px;">GENEL TOPLAM</span>
                                <span style="float:right;">{{ number_format($quote->grand_total, 2) }}
                                    {{ $quote->currency }}</span>
                                <div style="clear:both;"></div>
                            </div>
                        </td>
                    </tr>
                </table>

                <div style="margin-top: 50px; text-align: center;">
                    <div style="border-bottom: 1px solid #e2e8f0; width: 60%; margin: 0 auto 5px auto;"></div>
                    <span class="label">Onay ve İmza</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-row">
            <div class="footer-col">
                <strong>{{ $brandSettings['site_title'] ?? 'MIONEX' }}</strong><br>
                @if(isset($brandSettings['company_mersis']))
                    Mersis: {{ $brandSettings['company_mersis'] }}<br>
                @endif
                @if(isset($brandSettings['company_tax_id']))
                    Vergi: {{ $brandSettings['company_tax_id'] }}
                    {{ isset($brandSettings['company_tax_office']) ? '/ ' . $brandSettings['company_tax_office'] : '' }}
                @endif
            </div>
            <div class="footer-col text-center">
                <!-- Optional QR Code for Quote -->
            </div>
            <div class="footer-col text-right">
                <!-- Footer link removed as requested -->
                Sayfa 1 / 1
            </div>
        </div>
    </div>
</body>

</html>