<?php

namespace App\Http\Controllers\System\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Transaksi;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index()
    {
        $transaksis = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])->get();
        return view('Dashboard.Systems.Transaction.index', compact('transaksis'));
    }




    /**
     * Store a newly created transaction.
     */
    public function store(Request $request)
    {
        try {
            // Log the incoming request for debugging
            \Illuminate\Support\Facades\Log::info('Transaction store request:', [
                'all_data' => $request->all(),
                'headers' => $request->headers->all(),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type')
            ]);

            $validator = Validator::make($request->all(), [
                'id_meja' => 'required|integer|exists:mejas,id',
                'bayar' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                \Illuminate\Support\Facades\Log::error('Validation failed:', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Get all unpaid orders for this table
            $pesanans = Pesanan::where('id_meja', $request->id_meja)
                ->whereDoesntHave('transaksi')
                ->get();

            if ($pesanans->isEmpty()) {
                return redirect()->back()->with('failed', 'Tidak ada pesanan yang belum dibayar untuk meja ini');
            }

            // Calculate total amount
            $total = $pesanans->sum(function ($pesanan) {
                try {
                    return $pesanan->menu->harga * $pesanan->jumlah;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error calculating total for pesanan ' . $pesanan->id . ': ' . $e->getMessage());
                    return 0;
                }
            });

            // Check if payment amount is sufficient
            if ($request->bayar < $total) {
                return redirect()->back()->with('failed', 'Jumlah pembayaran tidak mencukupi. Total: Rp ' . number_format($total, 0, ',', '.'));
            }

            // Create transaction for each order with proportional payment
            $transaksis = [];
            $remainingPayment = $request->bayar; // Variabel penampung pembayaran
            $totalOrders = $pesanans->count();
            
            foreach ($pesanans as $index => $pesanan) {
                $orderTotal = $pesanan->menu->harga * $pesanan->jumlah;
                
                // Jika ini orderan terakhir, gunakan sisa pembayaran yang ada
                if ($index === $totalOrders - 1) {
                    $paymentForThisOrder = $remainingPayment;
                } else {
                    // Untuk orderan bukan terakhir, bayar sesuai harga orderan
                    $paymentForThisOrder = $orderTotal;
                    $remainingPayment -= $orderTotal;
                }
                
                $transaksi = Transaksi::create([
                    'id_pesanan' => $pesanan->id,
                    'total' => $orderTotal,
                    'bayar' => $paymentForThisOrder,
                ]);
                $transaksis[] = $transaksi;
            }

            // Update table status to available
            $meja = Meja::find($request->id_meja);
            $meja->update(['status' => Meja::STATUS_TERSEDIA]);

            // Get the first transaction for receipt generation
            $firstTransaksi = $transaksis[0];

            // Generate PDF receipt and download it
            try {
                // Get only the transactions that were just created for this payment
                $allTransaksis = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])
                    ->whereIn('id', collect($transaksis)->pluck('id'))
                    ->get();

                $totalAll = $allTransaksis->sum('total');
                $totalPaid = $allTransaksis->sum('bayar');

                // Use receipt view instead of generateReceiptHTML
                $view = view('Dashboard.Systems.Transaction.receipt', [
                    'transaksi' => $firstTransaksi,
                    'allTransaksis' => $allTransaksis,
                    'meja' => $meja,
                    'totalAll' => $totalAll,
                    'totalPaid' => $totalPaid
                ]);
                
                $pdf = Pdf::loadHTML($view->render());
                $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait'); // 80mm width for thermal printer
                $pdf->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'Courier New',
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => false,
                    'isFontSubsettingEnabled' => true,
                    'defaultMediaType' => 'print',
                    'debugKeepTemp' => false,
                    'debugCss' => false,
                    'debugLayout' => false,
                    'debugLayoutLines' => false,
                    'debugLayoutBlocks' => false,
                    'debugLayoutInline' => false,
                    'debugLayoutPaddingBox' => false,
                    'margin_top' => 0,
                    'margin_right' => 0,
                    'margin_bottom' => 0,
                    'margin_left' => 0,
                ]);
                
                // Return PDF download directly
                return $pdf->download('struk-transaksi-' . $firstTransaksi->id . '.pdf');
                
            } catch (\Exception $pdfError) {
                \Illuminate\Support\Facades\Log::error('PDF Generation Error: ' . $pdfError->getMessage());
                return redirect()->route('transaction.index')->with('success', 'Pembayaran berhasil! Gagal generate struk: ' . $pdfError->getMessage());
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Transaction store error: ' . $e->getMessage());
            return redirect()->back()->with('failed', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])->find($id);
        
        if (!$transaksi) {
            return redirect()->back()->with('failed', 'Transaksi tidak ditemukan');
        }

        // Get all transactions for the same customer
        $allTransaksis = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])
            ->whereHas('pesanan', function($query) use ($transaksi) {
                $query->where('id_pelanggan', $transaksi->pesanan->id_pelanggan);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $totalAmount = $allTransaksis->sum('total');
        $totalPaid = $allTransaksis->sum('bayar');

        return view('Dashboard.Systems.Transaction.show', compact('transaksi', 'allTransaksis', 'totalAmount', 'totalPaid'));
    }

    /**
     * Get unpaid orders for a specific table.
     */
    public function getUnpaidOrders(Request $request)
    {
        $mejaId = $request->id_meja;
        
        $pesanans = Pesanan::with(['menu', 'pelanggan'])
            ->where('id_meja', $mejaId)
            ->whereDoesntHave('transaksi')
            ->get();

        $total = $pesanans->sum(function ($pesanan) {
            return $pesanan->menu->harga * $pesanan->jumlah;
        });

        return response()->json([
            'pesanans' => $pesanans,
            'total' => $total,
        ]);
    }

    /**
     * Get available tables (not occupied by unpaid orders).
     */
    public function getAvailableTables()
    {
        // Get tables that have unpaid orders
        $occupiedTables = Pesanan::whereDoesntHave('transaksi')
            ->pluck('id_meja')
            ->unique();

        // Get all available tables
        $availableTables = Meja::where('status', 'tersedia')
            ->whereNotIn('id', $occupiedTables)
            ->get();

        return response()->json($availableTables);
    }

    /**
     * Display receipt for a transaction.
     */
    public function receipt($id)
    {
        $transaksi = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])
            ->findOrFail($id);

        // Get all transactions that were created in the same payment session
        // Find transactions with the same payment amount and created within 1 minute of each other
        $allTransaksis = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])
            ->where('bayar', $transaksi->bayar)
            ->whereBetween('created_at', [
                $transaksi->created_at->subMinute(),
                $transaksi->created_at->addMinute()
            ])
            ->whereHas('pesanan', function($query) use ($transaksi) {
                $query->where('id_meja', $transaksi->pesanan->id_meja);
            })
            ->get();

        $meja = $transaksi->pesanan->meja;
        $totalAll = $allTransaksis->sum('total');
        $totalPaid = $allTransaksis->sum('bayar');

        return view('Dashboard.Systems.Transaction.receipt', compact('transaksi', 'allTransaksis', 'meja', 'totalAll', 'totalPaid'));
    }

    /**
     * Download receipt PDF for a transaction.
     */
    public function receiptDownload($id)
    {
        $transaksi = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])
            ->findOrFail($id);

        // Get all transactions that were created in the same payment session
        // Find transactions with the same payment amount and created within 1 minute of each other
        $allTransaksis = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])
            ->where('bayar', $transaksi->bayar)
            ->whereBetween('created_at', [
                $transaksi->created_at->subMinute(),
                $transaksi->created_at->addMinute()
            ])
            ->whereHas('pesanan', function($query) use ($transaksi) {
                $query->where('id_meja', $transaksi->pesanan->id_meja);
            })
            ->get();

        $meja = $transaksi->pesanan->meja;
        $totalAll = $allTransaksis->sum('total');
        $totalPaid = $allTransaksis->sum('bayar');

        $view = view('Dashboard.Systems.Transaction.receipt', compact('transaksi', 'allTransaksis', 'meja', 'totalAll', 'totalPaid'));
        
        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            $pdf = Pdf::loadHTML($view->render())->setPaper([0, 0, 226.77, 841.89], 'portrait'); // 80mm width
            return $pdf->download('receipt-' . $transaksi->id . '.pdf');
        }
        
        return response($view);
    }


}
