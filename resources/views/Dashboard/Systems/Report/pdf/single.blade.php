<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - feel</title>
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
        
        .customer-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .info-item {
            padding: 10px;
            background: white;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .info-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .info-value {
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
        
        .summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        
        .summary-row.total {
            border-top: 2px solid #333;
            padding-top: 10px;
            font-weight: bold;
            font-size: 14px;
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
        <div class="report-title">LAPORAN TRANSAKSI PELANGGAN</div>
        <div class="report-date">Dicetak pada: {{ now()->format('d F Y H:i:s') }}</div>
    </div>

    <!-- Customer Info -->
    <div class="customer-info">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nama Pelanggan</div>
                <div class="info-value">{{ $transaksi->pesanan->pelanggan->name_pelanggan }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Nomor Telepon</div>
                <div class="info-value">{{ $transaksi->pesanan->pelanggan->phone_number }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Meja</div>
                <div class="info-value">Meja {{ $transaksi->pesanan->meja->nomer_meja }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Jumlah Transaksi</div>
                <div class="info-value">{{ $allTransaksis->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Menu</th>
                <th>Jumlah</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Dibayar</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allTransaksis as $index => $trans)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">#{{ $trans->id }}</td>
                    <td>{{ $trans->pesanan->menu->name_menu }}</td>
                    <td class="text-center">{{ $trans->pesanan->jumlah }}x</td>
                    <td class="text-right">Rp {{ number_format($trans->pesanan->menu->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($trans->pesanan->menu->harga * $trans->pesanan->jumlah, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($trans->bayar, 0, ',', '.') }}</td>
                    <td>{{ $trans->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Total Dibayar:</span>
            <span>Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row total">
            <span>Kembalian:</span>
            <span>Rp {{ number_format($totalPaid - $totalAmount, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem feel</p>
    </div>
</body>
</html>
