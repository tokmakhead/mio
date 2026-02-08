<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Gelir Analizi Raporu</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .kpi-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .kpi-box {
            width: 24%;
            float: left;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            margin-right: 1%;
        }

        .kpi-title {
            font-size: 12px;
            color: #666;
        }

        .kpi-value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        td:first-child {
            text-align: left;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Gelir Analizi Raporu</h1>
        <p>Dönem: Son {{ $period }} Ay | Oluşturulma Tarihi: {{ now()->format('d.m.Y H:i') }}</p>
    </div>

    <!-- KPIs -->
    <div class="kpi-container">
        <div class="kpi-box">
            <div class="kpi-title">Toplam Kesilen</div>
            <div class="kpi-value">{{ number_format($totalInvoiced, 2) }} ₺</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-title">Toplam Tahsilat</div>
            <div class="kpi-value">{{ number_format($totalCollected, 2) }} ₺</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-title">Toplam Bekleyen</div>
            <div class="kpi-value">{{ number_format($totalPending, 2) }} ₺</div>
        </div>
        <div class="kpi-box" style="margin-right: 0;">
            <div class="kpi-title">Ort. Fatura</div>
            <div class="kpi-value">{{ number_format($averageInvoice, 2) }} ₺</div>
        </div>
        <div class="clearfix"></div>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>Dönem</th>
                <th>Kesilen Fatura</th>
                <th>Tahsilat</th>
                <th>Bekleyen</th>
                <th>Adet</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyData as $data)
                <tr>
                    <td>{{ $data['month'] }}</td>
                    <td>{{ number_format($data['invoiced'], 2) }} ₺</td>
                    <td>{{ number_format($data['collected'], 2) }} ₺</td>
                    <td>{{ number_format($data['pending'], 2) }} ₺</td>
                    <td style="text-align: center;">{{ $data['invoice_count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>