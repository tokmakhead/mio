<!DOCTYPE html>
<html lang="tr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FATURA - {{ $invoice->number }}</title>
    <style>
        @page {
            margin: 20mm;
            padding: 0;
        }

        /* Typography */
        @if(file_exists(public_path('fonts/Inter-Regular.ttf')))
            @font-face {
                font-family: 'Inter';
                font-weight: 400;
                font-style: normal;
                src: url('{{ public_path("fonts/Inter-Regular.ttf") }}') format('truetype');
            }

            @font-face {
                font-family: 'Inter';
                font-weight: 500;
                font-style: normal;
                src: url('{{ public_path("fonts/Inter-Medium.ttf") }}') format('truetype');
            }

            @font-face {
                font-family: 'Inter';
                font-weight: 600;
                font-style: normal;
                src: url('{{ public_path("fonts/Inter-SemiBold.ttf") }}') format('truetype');
            }

            @font-face {
                font-family: 'Inter';
                font-weight: 700;
                font-style: normal;
                src: url('{{ public_path("fonts/Inter-Bold.ttf") }}') format('truetype');
            }

            body {
                font-family: 'Inter', sans-serif;
            }

        @else body {
                font-family: 'DejaVu Sans', sans-serif;
            }

        @endif body {
            color: #0B0B0B;
            font-size: 10pt;
            line-height: 1.4;
        }

        /* Base Settings */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* Explicit Fixed Layout */
        }

        td,
        th {
            vertical-align: top;
            padding: 0;
            word-wrap: break-word;
            /* Wrap long text */
        }

        tr,
        td {
            page-break-inside: avoid;
            /* Prevent row breaking */
        }

        /* Colors & Utils */
        .text-muted {
            color: #5A5A5A;
        }

        .bg-block {
            background-color: #F2F2F2;
        }

        .bg-head {
            background-color: #FAFAFA;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-semi {
            font-weight: 600;
        }

        .font-med {
            font-weight: 500;
        }

        .uppercase {
            text-transform: uppercase;
        }

        /* HEADER */
        .header-table {
            margin-bottom: 20px;
        }

        .brand-name {
            font-size: 14pt;
            font-weight: 600;
        }

        .brand-tag {
            font-size: 9pt;
            color: #5A5A5A;
            margin-top: 2px;
        }

        .doc-title {
            font-size: 18pt;
            font-weight: 700;
        }

        .doc-number {
            font-size: 11pt;
            color: #5A5A5A;
            margin-top: 4px;
        }

        /* PARTY BLOCK */
        .party-block {
            background-color: #F2F2F2;
            padding: 12px;
            margin-bottom: 20px;
        }

        .party-label {
            font-size: 8pt;
            font-weight: 700;
            color: #5A5A5A;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .party-content {
            font-size: 10pt;
            line-height: 1.4;
        }

        /* DATES */
        .dates-table {
            margin-bottom: 20px;
            border-bottom: 1px solid #E6E6E6;
        }

        .dates-table td {
            padding-bottom: 10px;
        }

        /* ITEMS TABLE */
        .items-table {
            margin-bottom: 20px;
            width: 100%;
        }

        .items-table thead th {
            background-color: #FAFAFA;
            padding: 8px 10px;
            font-size: 9pt;
            font-weight: 600;
            color: #5A5A5A;
            text-align: left;
            border-bottom: 1px solid #E6E6E6;
        }

        .items-table tbody td {
            padding: 10px;
            font-size: 10pt;
            border-bottom: 1px solid #EFEFEF;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #FCFCFC;
            /* Zebra */
        }

        /* BOTTOM SECTION */
        .bottom-table {
            width: 100%;
            margin-top: 10px;
        }

        .notes-content {
            font-size: 9pt;
            color: #5A5A5A;
            line-height: 1.5;
        }

        .totals-block {
            background-color: #F2F2F2;
            padding: 15px;
        }

        .totals-table td {
            padding: 3px 0;
            text-align: right;
        }

        .grand-total-label {
            font-size: 10pt;
            font-weight: 700;
            color: #5A5A5A;
            text-transform: uppercase;
        }

        .grand-total-value {
            font-size: 18pt;
            font-weight: 700;
            color: #0B0B0B;
            margin-top: 5px;
        }

        .remaining-value {
            color: #DC2626;
            font-weight: 700;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            text-align: center;
            font-size: 8pt;
            color: #5A5A5A;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td width="50%">
                <div class="brand-name">{{ $brandSettings['site_title'] ?? 'MIONEX' }}</div>
                <div class="brand-tag">Müşteri Odaklı Lojistik ve Yönetim</div>
            </td>
            <td width="50%" class="text-right">
                <div class="doc-title">SATIŞ FATURASI</div>
                <div class="doc-number">#{{ $invoice->number }}</div>
            </td>
        </tr>
    </table>

    <!-- PARTY BLOCK -->
    <div class="party-block">
        <table>
            <tr>
                <td width="50%" style="padding-right: 15px;">
                    <div class="party-label">FATURA SAHİBİ</div>
                    <div class="party-content">
                        <strong>{{ $brandSettings['company_name'] ?? ($brandSettings['site_title'] ?? 'MIONEX') }}</strong><br>
                        {{ $brandSettings['company_address'] ?? '' }}<br>
                        {{ $brandSettings['company_phone'] ?? '' }}<br>
                        {{ $brandSettings['company_email'] ?? '' }}
                    </div>
                </td>
                <td width="50%" style="padding-left: 15px;">
                    <div class="party-label">SAYIN</div>
                    <div class="party-content">
                        <strong>{{ $invoice->customer->name }}</strong><br>
                        {{ $invoice->customer->address }}<br>
                        {{ $invoice->customer->phone }}<br>
                        {{ $invoice->customer->email }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- DATES -->
    <table class="dates-table">
        <tr>
            <td width="25%">
                <div class="party-label">DÜZENLEME TARİHİ</div>
            </td>
            <td width="25%">
                <div class="party-content">{{ $dates['issue_date'] }}</div>
            </td>
            <td width="25%">
                <div class="party-label">VADE TARİHİ</div>
            </td>
            <td width="25%">
                <div class="party-content">{{ $dates['due_date'] }}</div>
            </td>
        </tr>
    </table>

    <!-- ITEMS -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="40%">HİZMET / AÇIKLAMA</th>
                <th width="15%" class="text-center">MİKTAR</th>
                <th width="15%" class="text-right">BİRİM FİYAT</th>
                <th width="10%" class="text-right">KDV</th>
                <th width="20%" class="text-right">TOPLAM</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <div class="font-semi">{{ $item->description }}</div>
                        @if($item->service && $item->service->identifier_code)
                            <div style="font-size: 8pt; color: #5A5A5A; margin-top: 2px;">{{ $item->service->identifier_code }}
                            </div>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->qty, 0) }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }} {{ $totals['currency'] }}</td>
                    <td class="text-right">%{{ (int) $item->vat_rate }}</td>
                    <td class="text-right font-bold">{{ number_format($item->line_total, 2) }} {{ $totals['currency'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- BOTTOM SECTION -->
    <table class="bottom-table">
        <tr>
            <!-- LEFT COLUMN (NOTES + BANK) -->
            <td width="55%" style="padding-right: 30px;">
                @if($invoice->notes)
                    <div class="party-label" style="margin-bottom: 5px;">NOTLAR</div>
                    <div class="notes-content" style="margin-bottom: 20px;">
                        {{ $invoice->notes }}
                    </div>
                @endif

                <div class="party-label" style="margin-bottom: 5px;">BANKA BİLGİLERİ</div>
                <div class="notes-content">
                    Banka: Garanti BBVA<br>
                    IBAN: TR00 0000 0000 0000 0000 0000 00<br>
                    Alıcı: {{ $brandSettings['company_name'] ?? ($brandSettings['site_title'] ?? 'MIONEX') }}
                </div>
            </td>

            <!-- RIGHT COLUMN (TOTALS) -->
            <td width="45%">
                <div class="totals-block">
                    <table class="totals-table">
                        <tr>
                            <td class="text-muted">Ara Toplam</td>
                            <td class="font-semi">{{ $totals['subtotal'] }} {{ $totals['currency'] }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Toplam KDV</td>
                            <td class="font-semi">{{ $totals['tax_total'] }} {{ $totals['currency'] }}</td>
                        </tr>
                        @if($invoice->discount_total > 0)
                            <tr>
                                <td style="color: #DC2626;">İndirim</td>
                                <td style="color: #DC2626;">-{{ $totals['discount_total'] }} {{ $totals['currency'] }}</td>
                            </tr>
                        @endif

                        <!-- SPACER -->
                        <tr>
                            <td colspan="2" style="height: 10px;"></td>
                        </tr>

                        <!-- GRAND TOTAL -->
                        <tr>
                            <td colspan="2">
                                <div class="grand-total-label text-left">GENEL TOPLAM</div>
                                <div class="grand-total-value text-right">{{ $totals['grand_total'] }}
                                    {{ $totals['currency'] }}
                                </div>
                            </td>
                        </tr>

                        @if($invoice->remaining_amount > 0)
                            <tr>
                                <td colspan="2" style="height: 10px; border-bottom: 1px solid #E6E6E6;"></td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding-top: 10px;">Kalan Tutar</td>
                                <td class="remaining-value" style="padding-top: 10px;">{{ $totals['remaining_amount'] }}
                                    {{ $totals['currency'] }}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

                <!-- QR CODE -->
                @if($qrBase64)
                    <div class="text-center" style="margin-top: 20px;">
                        <img src="{{ $qrBase64 }}" style="width: 80px; height: 80px;">
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <div class="footer">
        {{ $brandSettings['company_name'] ?? 'MIONEX' }} — Sayfa 1 / 1
    </div>

</body>

</html>