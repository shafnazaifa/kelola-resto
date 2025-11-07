@extends('layouts.dashboard.app')

@section('title', 'Detail Transaksi - feel')
@section('breadcrumb', 'Transaksi / Detail')
@section('page-title', 'Detail Transaksi')

@section('styles')
<style>
  .text-slate-700 { color: #334155 !important; }
  .text-slate-500 { color: #64748b !important; }
  .text-slate-400 { color: #94a3b8 !important; }
  .dark .text-white { color: #ffffff !important; }
  .dark .text-slate-400 { color: #94a3b8 !important; }
  
  .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
  }
  
  .btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
  }
  
  .btn-secondary {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    color: #475569;
    transition: all 0.3s ease;
  }
  
  .btn-secondary:hover {
    background: #e2e8f0;
    border-color: #cbd5e1;
    transform: translateY(-1px);
  }
  
  .card-hover {
    transition: all 0.3s ease;
  }
  
  .card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  }
  
  .receipt-card {
    border-left: 4px solid #4ade80;
  }
</style>
@endsection

@section('content')
<div class="w-full px-6 py-6 mx-auto">
  <!-- Header Section -->
  <div class="mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
      <div>
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">Detail Transaksi</h1>
        <p class="text-white dark:text-white text-lg">Informasi lengkap transaksi</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('transaction.list') }}" class="btn-secondary inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm shadow-lg">
          <i class="fas fa-arrow-left mr-2"></i>
          Kembali ke Riwayat
        </a>
      </div>
    </div>
  </div>

  <!-- Transaction Receipt -->
  <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden card-hover receipt-card">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700">
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center">
        <i class="fas fa-receipt mr-2 text-green-500"></i>
        Struk Transaksi
      </h3>
    </div>
    
    <div class="p-6">
      <!-- Restaurant Header -->
      <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">feel</h2>
        <p class="text-slate-600 dark:text-slate-400">Jl. Restoran No. 123, Jakarta</p>
        <p class="text-slate-600 dark:text-slate-400">Telp: (021) 1234-5678</p>
      </div>
      
      <!-- Transaction Info -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="space-y-4">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
              <i class="fas fa-receipt text-blue-600 dark:text-blue-400"></i>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-slate-400">ID Transaksi</p>
              <p class="text-lg font-semibold text-slate-800 dark:text-white">#{{ $transaksi->id }}</p>
            </div>
          </div>
          
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
              <i class="fas fa-table text-green-600 dark:text-green-400"></i>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-slate-400">Meja</p>
              <p class="text-lg font-semibold text-slate-800 dark:text-white">Meja {{ $transaksi->pesanan->meja->nomer_meja }}</p>
            </div>
          </div>
          
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
              <i class="fas fa-user text-purple-600 dark:text-purple-400"></i>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-slate-400">Pelanggan</p>
              <p class="text-lg font-semibold text-slate-800 dark:text-white">{{ $transaksi->pesanan->pelanggan->name_pelanggan }}</p>
            </div>
          </div>
        </div>
        
        <div class="space-y-4">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
              <i class="fas fa-calendar text-orange-600 dark:text-orange-400"></i>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-slate-400">Tanggal</p>
              <p class="text-lg font-semibold text-slate-800 dark:text-white">{{ $transaksi->created_at->format('d M Y') }}</p>
            </div>
          </div>
          
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
              <i class="fas fa-clock text-red-600 dark:text-red-400"></i>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-slate-400">Waktu</p>
              <p class="text-lg font-semibold text-slate-800 dark:text-white">{{ $transaksi->created_at->format('H:i') }}</p>
            </div>
          </div>
          
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
              <i class="fas fa-user-tie text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-slate-400">Waiter</p>
              <p class="text-lg font-semibold text-slate-800 dark:text-white">{{ $transaksi->pesanan->user->name_user }}</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Order Details -->
      <div class="mb-8">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center">
          <i class="fas fa-list mr-2 text-blue-500"></i>
          Detail Pesanan ({{ $allTransaksis->count() }} transaksi)
        </h3>
        
        <div class="space-y-4">
          @foreach($allTransaksis as $trans)
            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-utensils text-white text-sm"></i>
                  </div>
                  <div>
                    <h4 class="font-semibold text-slate-800 dark:text-white">{{ $trans->pesanan->menu->name_menu }}</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $trans->pesanan->jumlah }}x × Rp {{ number_format($trans->pesanan->menu->harga, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">ID Transaksi: #{{ $trans->id }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <div class="text-lg font-bold text-slate-800 dark:text-white">
                    Rp {{ number_format($trans->pesanan->menu->harga * $trans->pesanan->jumlah, 0, ',', '.') }}
                  </div>
                  <div class="text-sm text-green-600 dark:text-green-400">
                    Dibayar: Rp {{ number_format($trans->bayar, 0, ',', '.') }}
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      
      <!-- Payment Summary -->
      <div class="border-t border-slate-200 dark:border-slate-600 pt-6">
        <div class="space-y-3">
          <div class="flex justify-between items-center">
            <span class="text-lg font-semibold text-slate-800 dark:text-white">Subtotal:</span>
            <span class="text-lg font-bold text-slate-800 dark:text-white">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
          </div>
          
          <div class="flex justify-between items-center">
            <span class="text-lg font-semibold text-slate-800 dark:text-white">Dibayar:</span>
            <span class="text-lg font-bold text-green-600 dark:text-green-400">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
          </div>
          
          <div class="flex justify-between items-center border-t border-slate-200 dark:border-slate-600 pt-3">
            <span class="text-xl font-bold text-slate-800 dark:text-white">Kembalian:</span>
            <span class="text-xl font-bold text-blue-600 dark:text-blue-400">
              Rp {{ number_format($totalPaid - $totalAmount, 0, ',', '.') }}
            </span>
          </div>
        </div>
      </div>
      
      <!-- Footer -->
      <div class="text-center mt-8 pt-6 border-t border-slate-200 dark:border-slate-600">
        <p class="text-slate-600 dark:text-slate-400 text-sm">Terima kasih telah berkunjung!</p>
        <p class="text-slate-600 dark:text-slate-400 text-sm">Silakan datang kembali</p>
      </div>
    </div>
  </div>
</div>
@endsection
