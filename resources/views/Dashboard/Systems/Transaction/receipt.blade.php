<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - feel</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: 80mm auto;
        }
        
        body {
            font-family: "Courier New", monospace;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            background: white;
            color: black;
            width: 80mm;
            min-height: 100vh;
            display: block;
        }
        
        .receipt {
            width: 80mm;
            margin: 0;
            padding: 5px;
            box-sizing: border-box;
        }
        
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
            width: 100%;
        }
        
        .restaurant-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
            text-align: center;
        }
        
        .restaurant-info {
            font-size: 8px;
            margin-bottom: 2px;
            text-align: center;
        }
        
        .transaction-info {
            margin-bottom: 10px;
            width: 100%;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 9px;
            width: 100%;
            align-items: center;
        }
        
        .items {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 8px 0;
            margin: 10px 0;
            width: 100%;
        }
        
        .item {
            margin-bottom: 6px;
            width: 100%;
        }
        
        .item-name {
            font-weight: bold;
            font-size: 9px;
            text-align: left;
        }
        
        .item-details {
            font-size: 8px;
            margin-left: 8px;
            text-align: left;
        }
        
        .totals {
            margin-top: 10px;
            width: 100%;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 9px;
            width: 100%;
            align-items: center;
        }
        
        .grand-total {
            border-top: 1px solid #000;
            padding-top: 3px;
            font-weight: bold;
            font-size: 11px;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 8px;
            width: 100%;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
            width: 100%;
        }
        
        /* Ensure all text elements are properly aligned */
        * {
            box-sizing: border-box;
        }
        
        /* Fix any potential text alignment issues */
        .text-center {
            text-align: center !important;
        }
        
        .text-left {
            text-align: left !important;
        }
        
        .text-right {
            text-align: right !important;
        }
        
        /* Remove any default margins and paddings */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Ensure proper alignment for PDF */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            width: 80mm !important;
            overflow-x: hidden;
        }
        
        /* Fix any potential centering issues */
        .receipt {
            position: relative;
            left: 0;
            right: 0;
            margin-left: 0;
            margin-right: 0;
        }
        
        .print-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 10px;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Success Message -->
        @if(session('success'))
            <div class="success-message" style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px; border-radius: 5px; text-align: center; font-weight: bold;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="header">
            <div class="restaurant-name">RESTAURANT FEEL</div>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div class="info-row">
                <span>ID Transaksi:</span>
                <span>#{{ $transaksi->id }}</span>
            </div>
            <div class="info-row">
                <span>Tanggal:</span>
                <span>{{ $transaksi->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span>Waktu:</span>
                <span>{{ $transaksi->created_at->format('H:i:s') }}</span>
            </div>
            <div class="info-row">
                <span>Meja:</span>
                <span>{{ $meja->nomer_meja }}</span>
            </div>
            <div class="info-row">
                <span>Pelanggan:</span>
                <span>{{ $transaksi->pesanan->pelanggan->name_pelanggan }}</span>
            </div>
            <div class="info-row">
                <span>Kasir:</span>
                <span>{{ $transaksi->pesanan->user->name_user }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Items -->
        <div class="items">
            @foreach($allTransaksis as $trans)
                <div class="item">
                    <div class="item-name">{{ $trans->pesanan->menu->name_menu }}</div>
                    <div class="item-details">
                        {{ $trans->pesanan->jumlah }}x × Rp {{ number_format($trans->pesanan->menu->harga, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($totalAll, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Dibayar:</span>
                <span>Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
            </div>
            <div class="total-row grand-total">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($totalPaid - $totalAll, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>Terima kasih telah berkunjung!</div>
            <div>Silakan datang kembali</div>
            <div style="margin-top: 8px;">
                <div>================================</div>
                <div>Struk ini adalah bukti pembayaran</div>
                <div>yang sah dari feel</div>
            </div>
        </div>
    </div>
</body>
</html>
