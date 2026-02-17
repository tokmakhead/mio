<!DOCTYPE html>
<html lang="tr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FATURA - {{ $invoice->number }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            src: url('{{ storage_path("fonts/Inter-Regular.otf") }}') format('opentype');
        }

        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            src: url('{{ storage_path("fonts/Inter-Bold.otf") }}') format('opentype');
        }

        body {
            font-family: 'Inter', 'DejaVu Sans', sans-serif;
            color: #0B0B0B;
            font-size: 10pt;
            line-height: 1.4;
            padding: 18mm 22mm;
            /* A4 Margins matching Python script */
        }

        /* Updates for Premium Look */
        .text-primary {
            color: #0B0B0B;
        }

        .text-muted {
            color: #5A5A5A;
        }

        .text-soft {
            color: #9CA3AF;
        }

        .bg-block {
            background-color: #F2F2F2;
        }

        .bg-zebra {
            background-color: #FAFAFA;
        }

        .bg-white {
            background-color: #FFFFFF;
        }

        .border-soft {
            border-color: #E6E6E6;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-regular {
            font-weight: 400;
        }

        /* Typography Scale */
        .text-xs {
            font-size: 8pt;
        }

        .text-sm {
            font-size: 9pt;
        }

        .text-base {
            font-size: 10pt;
        }

        .text-lg {
            font-size: 12pt;
        }

        .text-xl {
            font-size: 14pt;
        }

        .text-2xl {
            font-size: 18pt;
        }

        .text-3xl {
            font-size: 24pt;
        }

        /* Helpers */
        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .table-fixed {
            table-layout: fixed;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .align-top {
            vertical-align: top;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .tracking-wide {
            letter-spacing: 0.05em;
        }

        /* Tables */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            padding: 0;
        }

        /* Header */
        .brand-title {
            font-size: 14pt;
            font-weight: 600;
            /* Medium/SemiBold map to Bold in generic if not specific */
            line-height: 1.2;
        }

        .brand-tagline {
            font-size: 9.5pt;
            color: #5A5A5A;
            margin-top: 2mm;
        }

        .doc-title {
            font-size: 18pt;
            font-weight: 700;
            text-align: right;
            letter-spacing: 0.5px;
        }

        .doc-number {
            font-size: 11pt;
            color: #5A5A5A;
            text-align: right;
            margin-top: 1mm;
        }

        /* Parties */
        .party-block {
            background-color: #F2F2F2;
            border-radius: 4px;
            padding: 15px;
            margin-top: 10mm;
            margin-bottom: 8mm;
        }

        .party-label {
            font-size: 9.5pt;
            font-weight: 700;
            color: #5A5A5A;
            text-transform: uppercase;
            margin-bottom: 4mm;
        }

        .party-info {
            font-size: 10.5pt;
            line-height: 1.5;
        }

        /* Dates */
        .dates-table td {
            padding-bottom: 3mm;
            border-bottom: 1px solid #E6E6E6;
        }

        /* Items */
        .items-table {
            margin-top: 8mm;
        }

        .items-table th {
            text-align: left;
            font-size: 9.5pt;
            font-weight: 700;
            color: #5A5A5A;
            padding: 3mm 4mm;
            background-color: #FAFAFA;
            border-bottom: 1px solid #E6E6E6;
        }

        .items-table td {
            padding: 3mm 4mm;
            font-size: 10.5pt;
            color: #0B0B0B;
            border-bottom: 1px solid #EFEFEF;
        }

        .items-table tr:nth-child(even) {
            background-color: #FFFFFF;
        }

        .items-table tr:nth-child(odd) {
            background-color: #FCFCFC;
        }

        /* Totals */
        .totals-block {
            background-color: #F2F2F2;
            border-radius: 4px;
            padding: 4mm;
        }

        .totals-table td {
            padding: 1.5mm 0;
            text-align: right;
        }

        .total-row {
            border-top: 1.5px solid #DDDDDD;
            margin-top: 3mm;
            padding-top: 3mm;
        }

        .grand-total {
            font-size: 16pt;
            font-weight: 700;
            color: #0B0B0B;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 18mm;
            left: 22mm;
            right: 22mm;
            font-size: 9pt;
            color: #5A5A5A;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <table class="w-full">
        <tr>
            <td class="align-top w-half">
                <div class="brand-title">{{ $brandSettings['site_title'] ?? 'MIONEX' }}</div>
                <div class="brand-tagline">Müşteri Odaklı Lojistik ve Yönetim</div>
            </td>
            <td class="align-top w-half">
                <div class="doc-title">SATIŞ FATURASI</div>
                <div class="doc-number">#{{ $invoice->number }}</div>
            </td>
        </tr>
    </table>

    <!-- Parties -->
    <div class="party-block">
        <table class="w-full table-fixed">
            <tr>
                <td class="align-top" style="width: 48%; padding-right: 2%;">
                    <div class="party-label">FATURA SAHİBİ</div>
                    <div class="party-info">
                        <strong>{{ $brandSettings['company_name'] ?? ($brandSettings['site_title'] ?? 'MIONEX') }}</strong><br>
                        {{ $brandSettings['company_address'] ?? '' }}<br>
                        {{ $brandSettings['company_phone'] ?? '' }}<br>
                        {{ $brandSettings['company_email'] ?? '' }}
                        @if(isset($brandSettings['company_tax_id']))
                            <br><span class="text-xs text-muted">V.D: {{ $brandSettings['company_tax_office'] ?? '-' }} /
                                No: {{ $brandSettings['company_tax_id'] }}</span>
                        @endif
                    </div>
                </td>
                <td class="align-top" style="width: 48%; padding-left: 2%;">
                    <div class="party-label">SAYIN</div>
                    <div class="party-info">
                        <strong>{{ $invoice->customer->name }}</strong><br>
                        {{ $invoice->customer->address }}<br>
                        {{ $invoice->customer->phone }}<br>
                        {{ $invoice->customer->email }}
                        @if($invoice->customer->tax_number)
                            <br><span class="text-xs text-muted">V.D: {{ $invoice->customer->tax_office }} / No:
                                {{ $invoice->customer->tax_number }}</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Dates -->
    <table class="w-full dates-table">
        <tr>
            <td style="width: 25%">
                <span class="party-label">DÜZENLEME TARİHİ</span>
            </td>
            <td style="width: 25%">
                <span class="text-base">{{ $invoice->issue_date->format('d.m.Y') }}</span>
            </td>
            <td style="width: 20%">
                <span class="party-label">VADE TARİHİ</span>
            </td>
            <td style="width: 30%">
                <span class="text-base">{{ $invoice->due_date->format('d.m.Y') }}</span>
            </td>
        </tr>
    </table>

    <!-- Items -->
    <table class="w-full items-table">
        <thead>
            <tr>
                <th style="width: 40%">HİZMET / AÇIKLAMA</th>
                <th class="text-center" style="width: 15%">MİKTAR</th>
                <th class="text-right" style="width: 15%">BİRİM FİYAT</th>
                <th class="text-right" style="width: 10%">KDV</th>
                <th class="text-right" style="width: 20%">TOPLAM</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <div class="font-bold">{{ $item->description }}</div>
                        @if($item->service && $item->service->identifier_code)
                            <div class="text-xs text-muted" style="margin-top: 1mm">{{ $item->service->identifier_code }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->qty, 0) }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }} {{ $invoice->currency }}</td>
                    <td class="text-right">%{{ (int) $item->vat_rate }}</td>
                    <td class="text-right font-bold">{{ number_format($item->line_total, 2) }} {{ $invoice->currency }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Bottom Section -->
    <table class="w-full" style="margin-top: 10mm">
        <tr>
            <!-- Left: Notes & Bank -->
            <td class="align-top" style="width: 55%; padding-right: 5mm">
                @if($invoice->notes)
                    <div class="party-label" style="margin-bottom: 2mm">NOTLAR</div>
                    <div class="text-sm text-muted" style="line-height: 1.4; margin-bottom: 6mm">
                        {{ $invoice->notes }}
                    </div>
                @endif

                <div class="party-label" style="margin-bottom: 2mm">BANKA BİLGİLERİ</div>
                <div class="text-sm text-muted" style="line-height: 1.4">
                    Banka: Garanti BBVA<br>
                    IBAN: TR00 0000 0000 0000 0000 0000 00<br>
                    Alıcı: {{ $brandSettings['company_name'] ?? ($brandSettings['site_title'] ?? 'MIONEX') }}
                </div>
            </td>

            <!-- Right: Totals -->
            <td class="align-top" style="width: 45%">
                <div class="totals-block">
                    <table class="w-full totals-table">
                        <tr>
                            <td class="text-muted font-bold text-sm">Ara Toplam</td>
                            <td class="text-base">{{ number_format($invoice->subtotal, 2) }} {{ $invoice->currency }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted font-bold text-sm">Toplam KDV</td>
                            <td class="text-base">{{ number_format($invoice->tax_total, 2) }} {{ $invoice->currency }}
                            </td>
                        </tr>
                        @if($invoice->discount_total > 0)
                            <tr>
                                <td class="text-red-600 font-bold text-sm">İndirim</td>
                                <td class="text-red-600 text-base">-{{ number_format($invoice->discount_total, 2) }}
                                    {{ $invoice->currency }}</td>
                            </tr>
                        @endif

                        <tr>
                            <td colspan="2">
                                <div class="total-row">
                                    <table class="w-full">
                                        <tr>
                                            <td class="text-left font-bold text-muted uppercase text-sm"
                                                style="vertical-align: middle;">Genel Toplam</td>
                                            <td class="text-right grand-total">
                                                {{ number_format($invoice->grand_total, 2) }} {{ $invoice->currency }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>

                        @if($invoice->remaining_amount > 0)
                            <tr>
                                <td colspan="2" style="padding-top: 2mm">
                                    <table class="w-full">
                                        <tr>
                                            <td class="text-left text-muted text-xs">Kalan Tutar</td>
                                            <td class="text-right font-bold text-sm" style="color: #ef4444">
                                                {{ number_format($invoice->remaining_amount, 2) }} {{ $invoice->currency }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

                <!-- QR Code (Optional) -->
                <div class="text-center" style="margin-top: 5mm">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ route('invoices.show', $invoice->id) }}"
                        alt="QR" style="width: 22mm; opacity: 0.9;">
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        {{ $brandSettings['company_name'] ?? 'MIONEX' }} • Sayfa 1 / 1
    </div>

</body>

</html>