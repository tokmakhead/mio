<!DOCTYPE html>
<html lang="tr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cari Hesap Ekstresi - {{ $customer->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .customer-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .customer-info td {
            border: none;
            padding: 2px 0;
        }

        .summary-container {
            width: 100%;
            margin-bottom: 25px;
        }

        .summary-box {
            width: 31%;
            float: left;
            border: 1px solid #ddd;
            padding: 10px;
            margin-right: 2%;
            background-color: #f9f9f9;
        }

        .summary-title {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }

        .summary-status {
            font-size: 8px;
            margin-top: 2px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #eee;
            padding: 6px 4px;
            text-align: right;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .debit {
            color: #d32f2f;
        }

        .credit {
            color: #388e3c;
        }

        .balance-pos {
            color: #d32f2f;
            font-weight: bold;
        }

        .balance-neg {
            color: #388e3c;
            font-weight: bold;
        }

        .currency-header {
            background-color: #444;
            color: white;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 20px;
        }

        .clearfix {
            clear: both;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 18px;">Cari Hesap Ekstresi</h1>
        <p style="margin: 5px 0; color: #666;">Oluşturulma Tarihi: {{ now()->format('d.m.Y H:i') }}</p>
    </div>

    <table class="customer-info">
        <tr>
            <td style="width: 50%;">
                <strong>Müşteri Bilgileri:</strong><br>
                {{ $customer->name }}<br>
                @if($customer->tax_office) {{ $customer->tax_office }} V.D. / @endif
                @if($customer->tax_number) {{ $customer->tax_number }} @endif
                <br>
                {{ $customer->phone }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>MIONEX Finans</strong><br>
                Otomatik Sistem Raporu
            </td>
        </tr>
    </table>

    <div class="summary-container">
        @php $count = 0; @endphp
        @foreach($summaryBalances as $currency => $balance)
            @php $count++; @endphp
            <div class="summary-box" @if($count % 3 == 0) style="margin-right: 0;" @endif>
                <div class="summary-title">{{ $currency }} Bakiyesi</div>
                <div class="summary-value {{ $balance > 0 ? 'debit' : ($balance < 0 ? 'credit' : '') }}">
                    {{ number_format(abs($balance), 2) }} {{ $currency }}
                </div>
                <div class="summary-status">
                    {{ $balance > 0 ? 'BORÇLU' : ($balance < 0 ? 'ALACAKLI' : 'BAKİYE YOK') }}
                </div>
            </div>
            @if($count % 3 == 0)
            <div class="clearfix" style="margin-bottom: 10px;"></div> @endif
        @endforeach
        <div class="clearfix"></div>
    </div>

    @foreach($groupedEntries as $currency => $entries)
        <div class="currency-header">{{ $currency }} EKSTRESİ</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Tarih</th>
                    <th style="width: 45%;">Açıklama</th>
                    <th style="width: 13%;">Borç</th>
                    <th style="width: 13%;">Alacak</th>
                    <th style="width: 14%;">Bakiye</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $entry)
                    <tr>
                        <td class="text-center">{{ $entry->occurred_at->format('d.m.Y') }}</td>
                        <td class="text-left">
                            {{ $entry->description }}
                            @if($entry->ref_type === 'App\Models\Invoice')
                                (Fatura #{{ $entry->ref->number ?? $entry->ref_id }})
                            @endif
                        </td>
                        <td class="debit">
                            {{ $entry->type === 'debit' ? number_format($entry->amount, 2) : '-' }}
                        </td>
                        <td class="credit">
                            {{ $entry->type === 'credit' ? number_format($entry->amount, 2) : '-' }}
                        </td>
                        <td class="{{ $entry->balance > 0 ? 'balance-pos' : ($entry->balance < 0 ? 'balance-neg' : '') }}">
                            {{ number_format(abs($entry->balance), 2) }}
                            {{ $entry->balance > 0 ? '(B)' : ($entry->balance < 0 ? '(A)' : '') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        Bu belge sistem tarafından otomatik oluşturulmuştur. | MIONEX v2.0
    </div>
</body>

</html>