<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <title>FATURA - {{ $invoice->number }}</title>
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
                {{ $brandSettings['primary_color'] ?? '#1e293b' }}
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

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            background: #e2e8f0;
            color: #475569;
        }

        .paid {
            background: #dcfce7;
            color: #166534;
        }

        .overdue {
            background: #fee2e2;
            color: #991b1b;
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
    <div class="header-bg" style="background-color: {{ $brandSettings['primary_color'] ?? '#1e293b' }}"></div>

    <div class="container">
        <!-- Header -->
        <div class="brand-section">
            <div style="display: table-cell; vertical-align: middle;">
                <div class="brand-logo">{{ $brandSettings['site_title'] ?? 'MIONEX' }}</div>
                <div class="brand-subtitle">Müşteri Odaklı Lojistik ve Yönetim</div>
            </div>
            <div style="display: table-cell; vertical-align: middle;" class="doc-title">
                <h1>SATIŞ FATURASI</h1>
                <div class="doc-number">#{{ $invoice->number }}</div>
            </div>
        </div>

        <!-- Meta Grid -->
        <div class="row">
            <div class="col-6">
                <div class="info-card">
                    <span class="label">Fatura Sahibi</span>
                    <div class="value font-bold">{{ $brandSettings['site_title'] ?? 'MIONEX' }}</div>
                    <div class="value">Güzelyurt Mah. 2135. Sok. No: 12</div>
                    <div class="value">Esenyurt / İSTANBUL</div>
                    <div class="value">destek@mioly.com</div>
                </div>
            </div>
            <div class="col-6" style="padding-left: 10px;">
                <div class="info-card" style="background: white; border: 1px solid #e2e8f0;">
                    <span class="label">Sayın</span>
                    <div class="value font-bold" style="font-size: 13px;">{{ $invoice->customer->name }}</div>
                    <div class="value">{{ $invoice->customer->address }}</div>
                    <div class="value">{{ $invoice->customer->phone }}</div>
                    <div class="value">{{ $invoice->customer->email }}</div>
                </div>
            </div>
        </div>

        <!-- Dates & Status -->
        <div class="row" style="margin-top: -15px;">
            <div class="col-6"></div>
            <div class="col-6" style="padding-left: 10px;">
                <table style="width: 100%">
                    <tr>
                        <td>
                            <span class="label">Düzenleme Tarihi</span>
                            <div class="value">{{ $invoice->issue_date->format('d.m.Y') }}</div>
                        </td>
                        <td>
                            <span class="label">Vade Tarihi</span>
                            <div
                                class="value font-bold {{ $invoice->due_date < now() && $invoice->remaining_amount > 0 ? 'text-red-600' : '' }}">
                                {{ $invoice->due_date->format('d.m.Y') }}
                            </div>
                        </td>
                        <td class="text-right">
                            <span
                                class="status-badge {{ $invoice->status == 'paid' ? 'paid' : '' }} {{ $invoice->status == 'overdue' ? 'overdue' : '' }}">
                                {{ \App\Models\Invoice::getStatusLabel($invoice->status) }}
                            </span>
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
                @foreach($invoice->items as $item)
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
                @if($invoice->notes)
                    <div style="background: #ffffff; padding: 15px; border-left: 3px solid #e2e8f0;">
                        <span class="label">Notlar</span>
                        <p style="font-size: 10px; margin: 5px 0 0 0; color: #64748b;">{{ $invoice->notes }}</p>
                    </div>
                @endif

                <div style="margin-top: 30px;">
                    <span class="label">Banka Bilgileri</span>
                    <div style="font-size: 9px; color: #64748b; margin-top: 5px;">
                        <strong>Banka:</strong> Garanti BBVA<br>
                        <strong>IBAN:</strong> TR00 0000 0000 0000 0000 0000 00<br>
                        <strong>Alıcı:</strong> {{ $brandSettings['site_title'] ?? 'MIONEX' }}
                    </div>
                </div>
            </div>

            <div class="totals-area">
                <table class="totals-table">
                    <tr>
                        <td>Ara Toplam</td>
                        <td class="font-bold">{{ number_format($invoice->subtotal, 2) }} {{ $invoice->currency }}</td>
                    </tr>
                    <tr>
                        <td>Toplam KDV</td>
                        <td>{{ number_format($invoice->tax_total, 2) }} {{ $invoice->currency }}</td>
                    </tr>
                    @if($invoice->discount_total > 0)
                        <tr>
                            <td style="color: #ef4444;">İndirim</td>
                            <td style="color: #ef4444;">-{{ number_format($invoice->discount_total, 2) }}
                                {{ $invoice->currency }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="padding: 10px 0;">
                            <div class="total-row" style="color: {{ $brandSettings['primary_color'] ?? '#1e293b' }}">
                                <span style="float:left; font-size: 11px; margin-top: 3px;">GENEL TOPLAM</span>
                                <span style="float:right;">{{ number_format($invoice->grand_total, 2) }}
                                    {{ $invoice->currency }}</span>
                                <div style="clear:both;"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 9px; padding-top: 5px;">Kalan Tutar</td>
                        <td style="font-size: 11px; font-weight: bold; color: #ef4444; padding-top: 5px;">
                            {{ number_format($invoice->remaining_amount, 2) }} {{ $invoice->currency }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-row">
            <div class="footer-col">
                <strong>{{ $brandSettings['site_title'] ?? 'MIONEX' }}</strong><br>
                Mersis: 0622055500000018<br>
                Vergi: 6220555000 / Esenyurt
            </div>
            <div class="footer-col text-center">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ route('invoices.show', $invoice->id) }}"
                    alt="QR" style="width: 40px; opacity: 0.8;">
            </div>
            <div class="footer-col text-right">
                {{ config('app.url') }}<br>
                Sayfa 1 / 1
            </div>
        </div>
    </div>
</body>

</html>

<head>
    <meta charset="utf-8">
    <title>FATURA - {{ $invoice->number }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #334155;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        .header-bg {
            background-color: #1e293b;
            /* Dark Slate for Invoice */
            height: 180px;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: -1;
        }

        .container {
            padding: 40px 50px;
        }

        .header {
            margin-bottom: 50px;
            color: white;
        }

        .header table {
            width: 100%;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .invoice-title {
            text-align: right;
            font-size: 36px;
            font-weight: 200;
            margin: 0;
        }

        .info-section {
            width: 100%;
            margin-bottom: 40px;
        }

        .info-section td {
            vertical-align: top;
            width: 50%;
        }

        .label {
            color: #64748b;
            text-transform: uppercase;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .value {
            font-size: 12px;
            color: #1e293b;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #f8fafc;
            color: #475569;
            text-align: left;
            padding: 12px 10px;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #1e293b;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .row-alt {
            background-color: #fafafa;
        }

        .totals-table {
            float: right;
            width: 280px;
        }

        .totals-table td {
            padding: 8px 10px;
            text-align: right;
        }

        .grand-total-row {
            background-color: #f8fafc;
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .paid {
            background-color: #dcfce7;
            color: #166534;
        }

        .overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .footer {
            position: absolute;
            bottom: 40px;
            left: 50px;
            right: 50px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            color: #94a3b8;
            font-size: 9px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header-bg"></div>
    <div class="container">
        <div class="header">
            <table>
                <tr>
                    <td>
                        <div class="company-name">{{ $brandSettings['site_title'] ?? 'MIOLY' }}</div>
                        <div style="font-size: 10px; opacity: 0.8; margin-top: 5px;">Müşteri Odaklı Lojistik ve Yönetim
                        </div>
                    </td>
                    <td>
                        <h1 class="invoice-title">SATIŞ FATURASI</h1>
                        <div style="text-align: right; margin-top: 10px; font-size: 12px;">#{{ $invoice->number }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="info-section">
            <tr>
                <td>
                    <span class="label">Fatura Sahibi</span>
                    <div class="value font-bold">MIOLY Teknoloji A.Ş.</div>
                    <div class="value">Güzelyurt Mah. 2135. Sok. No: 12</div>
                    <div class="value">Esenyurt / İSTANBUL</div>
                    <div class="value">destek@mioly.com | +90 (212) 555 00 00</div>
                </td>
                <td style="text-align: right;">
                    <span class="label">Alıcı Müşteri</span>
                    <div class="value font-bold">{{ $invoice->customer->name }}</div>
                    <div class="value">{{ $invoice->customer->address }}</div>
                    <div class="value">{{ $invoice->customer->phone }}</div>
                    <div class="value">{{ $invoice->customer->email }}</div>
                </td>
            </tr>
        </table>

        <div style="margin-bottom: 20px;">
            <table style="width: 100%; padding: 15px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
                <tr>
                    <td>
                        <span class="label">Düzenleme Tarihi</span>
                        <div class="value">{{ $invoice->issue_date->format('d.m.Y') }}</div>
                    </td>
                    <td class="text-center">
                        <span class="label">Vade Tarihi</span>
                        <div
                            class="value font-bold {{ $invoice->due_date < now() && $invoice->remaining_amount > 0 ? 'text-danger' : '' }}">
                            {{ $invoice->due_date->format('d.m.Y') }}
                        </div>
                    </td>
                    <td class="text-right">
                        <span class="label">Durum</span>
                        <div class="value font-bold uppercase tracking-wider">
                            {{ \App\Models\Invoice::getStatusLabel($invoice->status) }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Açıklama</th>
                    <th class="text-center" style="width: 10%;">Miktar</th>
                    <th class="text-right" style="width: 15%;">Birim Fiyat</th>
                    <th class="text-right" style="width: 10%;">KDV %</th>
                    <th class="text-right" style="width: 20%;">Toplam</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                    <tr class="{{ $index % 2 == 1 ? 'row-alt' : '' }}">
                        <td>
                            <div class="font-bold" style="color: #1e293b;">{{ $item->description }}</div>
                        </td>
                        <td class="text-center">{{ number_format($item->qty, 0) }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ (int) $item->vat_rate }}%</td>
                        <td class="text-right font-bold">{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="width: 100%; min-height: 150px;">
            <table class="totals-table">
                <tr>
                    <td style="color: #64748b;">Ara Toplam</td>
                    <td class="font-bold">{{ number_format($invoice->subtotal, 2) }} {{ $invoice->currency }}</td>
                </tr>
                <tr>
                    <td style="color: #64748b;">Toplam KDV</td>
                    <td class="font-bold">{{ number_format($invoice->tax_total, 2) }} {{ $invoice->currency }}</td>
                </tr>
                @if($invoice->discount_total > 0)
                    <tr>
                        <td style="color: #b91c1c;">İndirim</td>
                        <td class="font-bold text-danger">-{{ number_format($invoice->discount_total, 2) }}
                            {{ $invoice->currency }}
                        </td>
                    </tr>
                @endif
                <tr class="grand-total-row">
                    <td style="padding: 15px 10px; color: #1e293b; font-weight: bold;">GENEL TOPLAM</td>
                    <td class="grand-total" style="padding: 15px 10px;">{{ number_format($invoice->grand_total, 2) }}
                        {{ $invoice->currency }}
                    </td>
                </tr>
                <tr>
                    <td style="color: #16a34a; font-size: 10px;">Ödenen Tutar</td>
                    <td style="color: #16a34a; font-weight: bold;">{{ number_format($invoice->paid_amount, 2) }}
                        {{ $invoice->currency }}
                    </td>
                </tr>
                <tr>
                    <td style="color: #b91c1c; font-size: 10px; font-weight: bold;">KALAN TUTAR</td>
                    <td style="color: #b91c1c; font-weight: bold;">{{ number_format($invoice->remaining_amount, 2) }}
                        {{ $invoice->currency }}
                    </td>
                </tr>
            </table>
        </div>

        @if($invoice->notes)
            <div
                style="clear: both; margin-top: 40px; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #1e293b;">
                <span class="label" style="color: #1e293b;">Fatura Notları</span>
                <div style="font-size: 10px; color: #475569;">{{ $invoice->notes }}</div>
            </div>
        @endif

        <div class="footer">
            <div>{{ config('app.url') }} | +90 212 555 0000 | MIOLY Lojistik ve Teknoloji A.Ş.</div>
            <div style="margin-top: 5px;">Mersis No: 0622055500000018 | Vergi Dairesi: Esenyurt | Vergi No: 6220555000
            </div>
        </div>
    </div>
</body>

</html>