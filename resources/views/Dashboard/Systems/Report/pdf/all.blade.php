<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Semua Transaksi - feel</title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .restaurant-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .report-date {
            font-size: 12px;
            color: #666;
        }
        
        .summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .summary-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: bold;
        }
        
        .table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="restaurant-name">RESTAURANT FEEL</div>
        <div class="report-title">LAPORAN SEMUA TRANSAKSI</div>
        <div class="report-date">Dicetak pada: {{ now()->format('d F Y H:i:s') }}</div>
    </div>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Pelanggan</div>
                <div class="summary-value">{{ $totalCustomers }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Transaksi</div>
                <div class="summary-value">{{ $totalTransactions }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Dibayar</div>
                <div class="summary-value">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Meja</th>
                <th>Jumlah Transaksi</th>
                <th class="text-right">Total</th>
                <th class="text-right">Dibayar</th>
                <th class="text-right">Kembalian</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedTransaksis as $index => $group)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $group->customer->name_pelanggan }}</strong><br>
                        <small>{{ $group->customer->phone_number }}</small>
                    </td>
                    <td class="text-center">Meja {{ $group->meja->nomer_meja }}</td>
                    <td class="text-center">{{ $group->transaction_count }}</td>
                    <td class="text-right">Rp {{ number_format($group->total_amount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($group->total_paid, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($group->total_paid - $group->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $group->date->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem feel</p>
    </div>
</body>
</html>
