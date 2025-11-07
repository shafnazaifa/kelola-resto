<?php

namespace App\Http\Controllers\System\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function downloadAllReports()
    {
        try {
            // Get all grouped transactions
            $groupedTransaksis = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])
                ->orderBy('id', 'asc')
                ->get()
                ->groupBy('pesanan.id_pelanggan')
                ->map(function ($transactions) {
                    $latestTransaction = $transactions->first();
                    $allTransactions = $transactions;
                    $totalAmount = $allTransactions->sum('total');
                    $totalPaid = $allTransactions->sum('bayar');
                    
                    return (object) [
                        'customer' => $latestTransaction->pesanan->pelanggan,
                        'latest_transaction' => $latestTransaction,
                        'all_transactions' => $allTransactions,
                        'total_amount' => $totalAmount,
                        'total_paid' => $totalPaid,
                        'transaction_count' => $allTransactions->count(),
                        'meja' => $latestTransaction->pesanan->meja,
                        'date' => $latestTransaction->created_at
                    ];
                });

            // Calculate overall statistics
            $totalCustomers = $groupedTransaksis->count();
            $totalTransactions = $groupedTransaksis->sum('transaction_count');
            $totalRevenue = $groupedTransaksis->sum('total_amount');
            $totalPaid = $groupedTransaksis->sum('total_paid');

            // Generate PDF
            $view = view('Dashboard.Systems.Report.pdf.all', compact(
                'groupedTransaksis', 
                'totalCustomers', 
                'totalTransactions', 
                'totalRevenue', 
                'totalPaid'
            ));
            
            $pdf = Pdf::loadHTML($view->render());
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
                'isFontSubsettingEnabled' => true,
                'defaultMediaType' => 'print',
                'margin_top' => 15,
                'margin_right' => 15,
                'margin_bottom' => 15,
                'margin_left' => 15,
            ]);
            
            $filename = 'laporan-semua-transaksi-' . now()->format('Y-m-d-H-i-s') . '.pdf';
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Download All Reports Error: ' . $e->getMessage());
            return redirect()->back()->with('failed', 'Gagal mengunduh laporan: ' . $e->getMessage());
        }
    }

    public function downloadReport($id)
    {
        try {
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

            // Generate PDF
            $view = view('Dashboard.Systems.Report.pdf.single', compact('transaksi', 'allTransaksis', 'totalAmount', 'totalPaid'));
            
            $pdf = Pdf::loadHTML($view->render());
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
                'isFontSubsettingEnabled' => true,
                'defaultMediaType' => 'print',
                'margin_top' => 15,
                'margin_right' => 15,
                'margin_bottom' => 15,
                'margin_left' => 15,
            ]);
            
            $customerName = str_replace(' ', '-', strtolower($transaksi->pesanan->pelanggan->name_pelanggan));
            $filename = 'laporan-' . $customerName . '-' . now()->format('Y-m-d-H-i-s') . '.pdf';
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Download Report Error: ' . $e->getMessage());
            return redirect()->back()->with('failed', 'Gagal mengunduh laporan: ' . $e->getMessage());
        }
    }
}
