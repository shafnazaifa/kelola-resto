<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ViewController extends Controller
{
    public function dashboard()
    {
        // Get today's date
        $today = now()->toDateString();
        
        // Get today's transactions
        $todayTransactions = Transaksi::whereDate('created_at', $today)->get();
        
        // Calculate today's statistics
        $todayRevenue = $todayTransactions->sum('total');
        $todayPaid = $todayTransactions->sum('bayar');
        $todayTransactionCount = $todayTransactions->count();
        
        // Get today's orders count
        $todayOrders = Pesanan::whereDate('created_at', $today)->count();
        
        // Get active tables (occupied)
        $activeTables = Meja::where('status', 'tidak_tersedia')->count();
        $availableTables = Meja::where('status', 'tersedia')->count();
        
        // Get total customers today
        $todayCustomers = Pesanan::whereDate('created_at', $today)
            ->distinct('id_pelanggan')
            ->count();
        
        // Get chart data for last 7 days
        $chartData = [];
        $chartTransactionCount = [];
        $chartLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dayName = now()->subDays($i)->format('l');
            
            // Convert day name to Indonesian
            $dayNames = [
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa', 
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
                'Sunday' => 'Minggu'
            ];
            
            $dailyRevenue = Transaksi::whereDate('created_at', $date)->sum('total');
            $dailyTransactionCount = Transaksi::whereDate('created_at', $date)->count();
            
            $chartData[] = $dailyRevenue;
            $chartTransactionCount[] = $dailyTransactionCount;
            $chartLabels[] = $dayNames[$dayName] ?? $dayName;
        }
        
        // Get user info
        $user = Auth::user();
        
        // Fallback values if user is not authenticated
        if (!$user) {
            $user = (object) [
                'name_user' => 'Guest',
                'role' => 'guest'
            ];
        }
        
        return view('Dashboard.index', compact(
            'todayRevenue',
            'todayPaid', 
            'todayTransactionCount',
            'todayOrders',
            'activeTables',
            'availableTables',
            'todayCustomers',
            'chartData',
            'chartTransactionCount',
            'chartLabels',
            'user'
        ));
    }

    public function order(){
        $menus = Menu::all();
        $mejas = Meja::all();

        return view('Dashboard.Systems.Order.index', compact('menus', 'mejas'));
    }

    public function order_list(){
        // Get all orders that haven't been paid yet, grouped by table
        $pesanans = Pesanan::with(['menu', 'pelanggan', 'meja', 'user'])
            ->whereDoesntHave('transaksi')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('id_meja');
        
        // Get all menus for adding new orders
        $menus = Menu::all();
        
        return view('Dashboard.Systems.Order.list', compact('pesanans', 'menus'));
    }

    public function transaction(){
        // Get all orders that haven't been paid yet, grouped by table
        $pesanans = Pesanan::with(['menu', 'pelanggan', 'meja', 'user'])
            ->whereDoesntHave('transaksi')
            ->get()
            ->groupBy('id_meja');
        
        return view('Dashboard.Systems.Transaction.index', compact('pesanans'));
    }

    public function transaction_list(){
        // Group transactions by customer ID and get the latest transaction for each customer
        $groupedTransaksis = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('pesanan.id_pelanggan')
            ->map(function ($transactions) {
                // Get the latest transaction for this customer
                $latestTransaction = $transactions->first();
                
                // Get all transactions for this customer
                $allTransactions = $transactions;
                
                // Calculate totals
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
        
        return view('Dashboard.Systems.Transaction.list', compact('groupedTransaksis'));
    }

    public function report(){
        $groupedTransaksis = Transaksi::with(['pesanan.menu', 'pesanan.pelanggan', 'pesanan.meja', 'pesanan.user'])
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('pesanan.id_pelanggan')
            ->map(function ($transactions) {
                // Get the latest transaction for this customer
                $latestTransaction = $transactions->first();
                
                // Get all transactions for this customer
                $allTransactions = $transactions;
                
                // Calculate totals
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
        
        return view('Dashboard.Systems.Report.index', compact('groupedTransaksis'));
    }

    public function report_show($id){
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

        return view('Dashboard.Systems.Report.show', compact('transaksi', 'allTransaksis', 'totalAmount', 'totalPaid'));
    }

    
}
