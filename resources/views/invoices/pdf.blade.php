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
                        <div class="company-name">MIOLY</div>
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
                            {{ $invoice->currency }}</td>
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