<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cari Hesap Ekstresi - {{ $customer->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
        }

        .logo {
            width: 150px;
            float: left;
        }

        .company-info {
            float: right;
            text-align: right;
            font-size: 11px;
            color: #555;
        }

        .title {
            clear: both;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .customer-info {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f9f9f9;
            border-top: 2px solid #ddd;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .debit {
            color: #d32f2f;
        }

        .credit {
            color: #388e3c;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: #fff;
        }

        .badge-invoice {
            background-color: #eba434;
        }

        .badge-payment {
            background-color: #388e3c;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo">
            <!-- Logo area (Optional: <img src="..." />) -->
            <h1 style="margin: 0; color: #333;">{{ \App\Models\SystemSetting::first()->site_name ?? 'MIONEX' }}</h1>
        </div>
        <div class="company-info">
            <strong>{{ \App\Models\SystemSetting::first()->site_name ?? 'Şirket Adı' }}</strong><br>
            Tarih: {{ now()->format('d.m.Y H:i') }}<br>
        </div>
    </div>

    <div class="title">Cari Hesap Ekstresi</div>

    <div class="customer-info">
        <table style="width: 100%; background: transparent;">
            <tr>
                <td style="border: none; padding: 0;">
                    <strong>Sayın {{ $customer->company_name ?? $customer->name }}</strong><br>
                    {{ $customer->full_address ?? 'Adres Bilgisi Yok' }}<br>
                    {{ $customer->phone }} | {{ $customer->email }}
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    <strong>Vergi Dairesi:</strong> {{ $customer->tax_office ?? '-' }}<br>
                    <strong>Vergi/TC No:</strong> {{ $customer->tax_or_identity_number ?? '-' }}<br>
                    <strong>Müşteri No:</strong> #{{ $customer->id }}
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%">Tarih</th>
                <th style="width: 10%">Tip</th>
                <th style="width: 35%">Açıklama</th>
                <th style="width: 13%" class="text-right">Borç</th>
                <th style="width: 13%" class="text-right">Alacak</th>
                <th style="width: 14%" class="text-right">Bakiye</th>
            </tr>
        </thead>
        <tbody>
            @php $balance = 0; @endphp
            @foreach($entries as $entry)
                @php
                    if ($entry->type === 'debit') {
                        $balance += $entry->amount;
                    } else {
                        $balance -= $entry->amount;
                    }
                @endphp
                <tr>
                    <td>{{ $entry->occurred_at->format('d.m.Y') }}</td>
                    <td>
                        <span class="badge {{ $entry->type == 'debit' ? 'badge-invoice' : 'badge-payment' }}">
                            {{ $entry->type_label }}
                        </span>
                    </td>
                    <td>
                        {{ $entry->description }}
                        @if($entry->ref_id)
                            (#{{ $entry->ref_id }})
                        @endif
                    </td>
                    <td class="text-right">
                        @if($entry->type == 'debit')
                            {{ number_format($entry->amount, 2, ',', '.') }} {{ $entry->currency }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        @if($entry->type == 'credit')
                            {{ number_format($entry->amount, 2, ',', '.') }} {{ $entry->currency }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        <strong class="{{ $balance >= 0 ? 'debit' : 'credit' }}">
                            {{ number_format(abs($balance), 2, ',', '.') }} {{ $entry->currency }}
                            {{ $balance >= 0 ? '(B)' : '(A)' }}
                        </strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">Genel Toplam</td>
                <td class="text-right">{{ number_format($entries->where('type', 'debit')->sum('amount'), 2, ',', '.') }}
                </td>
                <td class="text-right">
                    {{ number_format($entries->where('type', 'credit')->sum('amount'), 2, ',', '.') }}</td>
                <td class="text-right">
                    <span class="{{ $balance >= 0 ? 'debit' : 'credit' }}">
                        {{ number_format(abs($balance), 2, ',', '.') }}
                        {{ $balance >= 0 ? '(B)' : '(A)' }}
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Bu belge sistem tarafından otomatik olarak oluşturulmuştur. Islak imza gerektirmez.<br>
        {{ now()->year }} &copy; {{ \App\Models\SystemSetting::first()->site_name ?? 'MIONEX' }}
    </div>

</body>

</html>