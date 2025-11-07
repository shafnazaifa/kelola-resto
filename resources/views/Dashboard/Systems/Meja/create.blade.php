@extends('layouts.dashboard.app')

@section('title', 'Tambah Meja Baru - feel')
@section('breadcrumb', 'Meja')
@section('page-title', 'Tambah Meja Baru')

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
  
  .form-input {
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
  }
  
  .form-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }
  
  .form-input.error {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
  }
  
  .form-select {
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
  }
  
  .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }
  
  .form-select.error {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
  }
  
  .card-hover {
    transition: all 0.3s ease;
  }
  
  .card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  }
</style>
@endsection

@section('content')
<div class="w-full px-6 py-6 mx-auto">
  <!-- Header Section -->
  <div class="mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
      <div>
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">Tambah Meja Baru</h1>
        <p class="text-white dark:text-white text-lg">Buat meja baru untuk restoran Anda</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('meja.index') }}" class="btn-secondary inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm">
          <i class="fas fa-arrow-left mr-2"></i>
          Kembali ke Daftar Meja
        </a>
      </div>
    </div>
  </div>

  <!-- Form Card -->
  <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden card-hover">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700">
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center">
        <i class="fas fa-plus-circle mr-2 text-green-500"></i>
        Informasi Meja
      </h3>
    </div>
    
    <div class="p-6">
      <!-- Display errors if any -->
      @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
          <div class="flex items-start">
            <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-1 text-lg"></i>
            <div class="flex-1">
              <h4 class="text-red-800 font-semibold mb-2">Terjadi kesalahan:</h4>
              <ul class="text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                  <li>• {{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif

      <form method="POST" action="{{ route('meja.store') }}" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Nomor Meja -->
          <div>
            <label for="nomer_meja" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
              Nomor Meja <span class="text-red-500">*</span>
            </label>
            <input 
              type="text" 
              id="nomer_meja" 
              name="nomer_meja" 
              value="{{ old('nomer_meja') }}"
              class="form-input w-full px-4 py-3 rounded-xl text-slate-700 dark:text-white dark:bg-slate-700 @error('nomer_meja') error @enderror" 
              placeholder="Masukkan nomor meja"
              required
            />
            @error('nomer_meja')
              <p class="text-red-500 text-sm mt-2 flex items-center">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                {{ $message }}
              </p>
            @enderror
          </div>

          <!-- Jumlah Kursi -->
          <div>
            <label for="kursi" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
              Jumlah Kursi <span class="text-red-500">*</span>
            </label>
            <select 
              id="kursi" 
              name="kursi" 
              class="form-select w-full px-4 py-3 rounded-xl text-slate-700 dark:text-white dark:bg-slate-700 @error('kursi') error @enderror" 
              required
            >
              <option value="">Pilih jumlah kursi</option>
              <option value="2" {{ old('kursi') == '2' ? 'selected' : '' }}>2 Kursi</option>
              <option value="4" {{ old('kursi') == '4' ? 'selected' : '' }}>4 Kursi</option>
              <option value="6" {{ old('kursi') == '6' ? 'selected' : '' }}>6 Kursi</option>
              <option value="8" {{ old('kursi') == '8' ? 'selected' : '' }}>8 Kursi</option>
            </select>
            @error('kursi')
              <p class="text-red-500 text-sm mt-2 flex items-center">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                {{ $message }}
              </p>
            @enderror
          </div>

          <!-- Status -->
          <div class="lg:col-span-2">
            <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
              Status <span class="text-red-500">*</span>
            </label>
            <select 
              id="status" 
              name="status" 
              class="form-select w-full px-4 py-3 rounded-xl text-slate-700 dark:text-white dark:bg-slate-700 @error('status') error @enderror" 
              required
            >
              <option value="">Pilih status meja</option>
              <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
              <option value="tidak_tersedia" {{ old('status') == 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
            </select>
            @error('status')
              <p class="text-red-500 text-sm mt-2 flex items-center">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                {{ $message }}
              </p>
            @enderror
          </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-6 border-t border-slate-200 dark:border-slate-600">
          <a href="{{ route('meja.index') }}" class="btn-secondary inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm w-full sm:w-auto mx-2">
            <i class="fas fa-times mr-2"></i>
            Batal
          </a>
          <button type="submit" class="btn-primary inline-flex items-center justify-center px-8 py-3 rounded-xl font-semibold text-sm shadow-lg w-full sm:w-auto mx-2 mt-2">
            <i class="fas fa-save mr-2"></i>
            Simpan Meja
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
