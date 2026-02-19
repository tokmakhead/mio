<!DOCTYPE html>
<html lang="tr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FATURA - {{ $invoice->number }}</title>
    <style>
        @page {
            margin: 0cm;
            padding: 0cm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif !important;
            color: #333;
            font-size: 9pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            width: 100%;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Helpers */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-primary {
            color: #dc2626;
        }

        /* MIONEX Red */
        .text-white {
            color: #fff;
        }

        .text-muted {
            color: #6b7280;
        }

        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 0;
            vertical-align: top;
        }

        /* HEADER SECTION */
        .header-bg {
            background-color: #f8f8f8;
            border-bottom: 3px solid #dc2626;
            padding: 40px 50px;
        }

        .brand-logo {
            font-size: 24pt;
            font-weight: bold;
            color: #111;
            letter-spacing: -0.5px;
        }

        .brand-sub {
            font-size: 9pt;
            color: #dc2626;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .invoice-badge {
            background-color: #111 !important;
            color: #fff !important;
            padding: 8px 15px;
            font-size: 14pt;
            font-weight: bold;
            display: inline-block;
            border-radius: 4px;
        }

        .invoice-meta {
            margin-top: 10px;
            color: #555;
            font-size: 9pt;
        }

        /* CONTENT WRAPPER */
        .wrapper {
            padding: 40px 50px;
        }

        /* CARDS */
        .info-card {
            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        }

        .card-header {
            background-color: #f3f4f6;
            padding: 10px 15px;
            font-size: 8pt;
            font-weight: bold;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-body {
            padding: 15px;
            color: #1f2937;
            font-size: 10pt;
        }

        /* ITEMS TABLE */
        .items-table {
            width: 100%;
            margin-bottom: 40px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .items-table thead th {
            background-color: #111 !important;
            color: #fff !important;
            padding: 12px 15px;
            font-size: 8pt;
            font-weight: 600;
            text-align: left;
            border: none;
        }

        .items-table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        /* TOTALS AREA */
        .totals-area {
            width: 100%;
        }

        .total-row td {
            padding: 8px 0;
        }

        .grand-total-box {
            background-color: #dc2626 !important;
            color: #fff !important;
            padding: 12px 15px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .grand-total-box td {
            vertical-align: middle !important;
            font-family: 'DejaVu Sans', sans-serif !important;
        }

        /* FOOTER */
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f8f8f8;
            padding: 15px 50px;
            border-top: 1px solid #e5e7eb;
            font-size: 8pt;
            color: #6b7280;
        }

        /* Screen Only Styles */
        @media screen {
            body {
                background-color: #e5e7eb;
                padding: 40px;
            }

            .page-container {
                max-width: 210mm;
                min-height: 297mm;
                margin: 0 auto;
                background-color: #fff;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                position: relative;
            }
        }

        /* Print Reset */
        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: transparent;
            }

            .page-container {
                width: 100%;
                margin: 0;
                padding: 0;
                background-color: transparent;
                box-shadow: none;
            }

            @page {
                margin: 0;
                size: a4 portrait;
            }
        }
    </style>
</head>

<body>
    <div class="page-container">
        <!-- Header -->
        <div class="header-bg">
            <table class="w-full">
                <tr>
                    <td style="width: 60%">
                        <div class="brand-logo">{{ $brandSettings['site_title'] ?? 'MIONEX' }}</div>
                        <div class="brand-sub">M&#252;&#351;teri Odakl&#305; Lojistik ve Y&#246;netim</div>
                    </td>
                    <td style="width: 40%; text-align: right;">
                        <div class="invoice-badge">FATURA</div>
                        <div class="invoice-meta">
                            <strong>NO:</strong> #{{ $invoice->number }}<br>
                            <strong>TAR&#304;H:</strong> {{ $dates['issue_date'] }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="wrapper">
            <!-- Info Cards -->
            <table class="w-full" style="border-spacing: 20px 0; border-collapse: separate; margin: 0 -20px;">
                <tr>
                    <td class="w-half" style="vertical-align: top;">
                        <div class="info-card">
                            <div class="card-header">G&#214;NDEREN F&#304;RMA</div>
                            <div class="card-body">
                                <strong
                                    style="font-size: 11pt; color: #111;">{{ $brandSettings['company_name'] ?? 'MIONEX' }}</strong><br>
                                <div style="margin-top: 5px; color: #555;">
                                    {{ $brandSettings['company_address'] ?? '' }}<br>
                                    {{ $brandSettings['company_email'] ?? '' }}<br>
                                    {{ $brandSettings['company_phone'] ?? '' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="w-half" style="vertical-align: top;">
                        <div class="info-card">
                            <div class="card-header">SAYIN M&#220;&#351;TER&#304;</div>
                            <div class="card-body">
                                <strong
                                    style="font-size: 11pt; color: #111;">{{ $invoice->customer->name }}</strong><br>
                                <div style="margin-top: 5px; color: #555;">
                                    {{ $invoice->customer->address }}<br>
                                    {{ $invoice->customer->email }}<br>
                                    {{ $invoice->customer->phone }}
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Items -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="45%">H&#304;ZMET / A&#199;IKLAMA</th>
                        <th width="12%" class="text-center">M&#304;KTAR</th>
                        <th width="16%" class="text-right">B&#304;R&#304;M</th>
                        <th width="10%" class="text-right">KDV</th>
                        <th width="17%" class="text-right">T. TUTAR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>
                                <strong style="color: #111;">{{ $item->description }}</strong>
                                @if($item->service && $item->service->identifier_code)
                                    <div style="font-size: 8pt; color: #888; margin-top: 2px;">
                                        {{ $item->service->identifier_code }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">{{ number_format($item->qty, 0) }}</td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }} {{ $totals['currency'] }}</td>
                            <td class="text-right">%{{ (int) $item->vat_rate }}</td>
                            <td class="text-right font-bold" style="color: #111;">{{ number_format($item->line_total, 2) }}
                                {{ $totals['currency'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Footer Section -->
            <table class="w-full">
                <tr>
                    <td style="width: 60%; padding-right: 40px;">
                        @if($invoice->notes)
                            <div style="margin-bottom: 20px;">
                                <div
                                    style="font-size: 8pt; font-weight: bold; text-transform: uppercase; color: #dc2626; margin-bottom: 5px;">
                                    NOTLAR</div>
                                <div
                                    style="background: #fffbe6; border: 1px solid #fde68a; padding: 10px; border-radius: 4px; font-size: 9pt; color: #92400e;">
                                    {{ $invoice->notes }}
                                </div>
                            </div>
                        @endif

                        <div style="font-size: 8pt; color: #6b7280;">
                            <strong>BANKA BİLGİLERİ:</strong><br>
                            @foreach($bankAccounts as $account)
                                <div style="margin-top: 4px;">
                                    <span style="font-weight: bold; color: #374151;">{{ $account->bank_name }}</span>
                                    - <span style="font-family: monospace;">{{ $account->formatted_iban }}</span><br>
                                    @if($account->account_number)
                                        <span style="font-size: 7pt;">Şube: {{ $account->branch_name }}
                                            ({{ $account->branch_code }}) - Hesap: {{ $account->account_number }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </td>

                    <td style="width: 40%;">
                        <table class="totals-area">
                            <tr class="total-row">
                                <td class="text-muted text-right">Ara Toplam:</td>
                                <td class="text-right font-bold">{{ $totals['subtotal'] }} {{ $totals['currency'] }}
                                </td>
                            </tr>
                            <tr class="total-row">
                                <td class="text-muted text-right">KDV Toplam:</td>
                                <td class="text-right font-bold">{{ $totals['tax_total'] }} {{ $totals['currency'] }}
                                </td>
                            </tr>
                            @if($invoice->discount_total > 0)
                                <tr class="total-row">
                                    <td class="text-right" style="color: #dc2626;">&#304;ndirim:</td>
                                    <td class="text-right" style="color: #dc2626;">-{{ $totals['discount_total'] }}
                                        {{ $totals['currency'] }}
                                    </td>
                                </tr>
                            @endif
                        </table>

                        <div class="grand-total-box">
                            <table class="w-full">
                                <tr>
                                    <td style="font-size: 8pt; opacity: 0.9; text-align: left; vertical-align: middle;">
                                        GENEL TOPLAM</td>
                                    <td class="text-right"
                                        style="font-size: 14pt; font-weight: bold; vertical-align: middle; white-space: nowrap;">
                                        {{ $totals['grand_total'] }} <span
                                            style="font-size: 10pt;">{{ $totals['currency'] }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        @if($qrBase64)
                            <div style="margin-top: 20px; text-align: right;">
                                <img src="{{ $qrBase64 }}"
                                    style="width: 80px; height: 80px; border: 4px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <table class="w-full">
                <tr>
                    <td style="text-transform: none;">{{ $brandSettings['company_name'] ?? 'MIONEX' }} &copy;
                        {{ date('Y') }}
                    </td>
                    <td class="text-right">Sayfa 1 / 1</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>