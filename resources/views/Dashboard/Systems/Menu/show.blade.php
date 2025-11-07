@extends('layouts.dashboard.app')

@section('title', 'Detail Menu - feel')
@section('breadcrumb', 'Menu')
@section('page-title', 'Detail Menu')

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
  
  .btn-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
  }
  
  .btn-danger:hover {
    background: linear-gradient(135deg, #ff5252 0%, #e53e3e 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
  }
  
  .card-hover {
    transition: all 0.3s ease;
  }
  
  .card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  }
  
  .info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 1px solid #e2e8f0;
  }
  
  .dark .info-card {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border: 1px solid #475569;
  }
</style>
@endsection

@section('content')
<div class="w-full px-6 py-6 mx-auto">
  <!-- Header Section -->
  <div class="mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
      <div>
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">Detail Menu</h1>
        <p class="text-white dark:text-white text-lg">Informasi lengkap menu restoran</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('menu.index') }}" class="btn-secondary inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm">
          <i class="fas fa-arrow-left mr-2"></i>
          Kembali ke Daftar Menu
        </a>
      </div>
    </div>
  </div>

  <!-- Menu Details Card -->
  <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden card-hover">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700">
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center">
        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
        Informasi Menu
      </h3>
    </div>
    
    <div class="p-6">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Menu Name -->
        <div class="lg:col-span-2">
          <div class="info-card rounded-xl p-6">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-utensils"></i>
              </div>
              <h4 class="text-sm font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Nama Menu</h4>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $menu->name_menu }}</h2>
          </div>
        </div>

        <!-- Price -->
        <div class="lg:col-span-2 mt-4">
          <div class="info-card rounded-xl p-6">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-tag"></i>
              </div>
              <h4 class="text-sm font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Harga</h4>
            </div>
            <div class="text-3xl font-bold text-green-600 dark:text-green-400">
              Rp {{ number_format($menu->harga, 0, ',', '.') }}
            </div>
          </div>
        </div>

        <!-- Created At -->
        <div class="mt-4">
          <div class="info-card rounded-xl p-6">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-calendar-plus"></i>
              </div>
              <h4 class="text-sm font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Dibuat</h4>
            </div>
            <div class="text-lg font-semibold text-slate-700 dark:text-white">
              {{ $menu->created_at->format('d M Y') }}
            </div>
            <div class="text-sm text-slate-500 dark:text-slate-400">
              {{ $menu->created_at->format('H:i') }} WIB
            </div>
          </div>
        </div>

        <!-- Updated At -->
        <div class="mt-4" >
          <div class="info-card rounded-xl p-6">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-calendar-check"></i>
              </div>
              <h4 class="text-sm font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Terakhir Diperbarui</h4>
            </div>
            <div class="text-lg font-semibold text-slate-700 dark:text-white">
              {{ $menu->updated_at->format('d M Y') }}
            </div>
            <div class="text-sm text-slate-500 dark:text-slate-400">
              {{ $menu->updated_at->format('H:i') }} WIB
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-600">
        <form method="POST" action="{{ route('menu.destroy', $menu->id) }}" class="inline-block w-full sm:w-auto mx-2" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini? Tindakan ini tidak dapat dibatalkan.')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-danger inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm shadow-lg w-full">
            <i class="fas fa-trash mr-2"></i>
            Hapus Menu
          </button>
        </form>
        <a href="{{ route('menu.edit', $menu->id) }}" class="btn-primary inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm shadow-lg w-full sm:w-auto mx-2 mt-2">
          <i class="fas fa-edit mr-2"></i>
          Edit Menu
        </a>
      </div>
    </div>
  </div>
</div>
@endsection