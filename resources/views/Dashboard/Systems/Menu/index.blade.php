@extends('layouts.dashboard.app')

@section('title', 'Manajemen Menu - feel')
@section('breadcrumb', 'Menu')
@section('page-title', 'Manajemen Menu')

@section('styles')
<style>
  .badge-status { font-weight: 600; font-size: 0.8rem; }
  .table td, .table th { vertical-align: middle; }
  .price { font-weight: 700; color: #198754; }
  .table-title { margin: 0; }
</style>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-0">Manajemen Menu</h4>
        <small class="text-muted">Kelola daftar menu restoran</small>
      </div>
      <div>
        <a href="{{ route('menu.create') }}" class="btn btn-primary">
          <i class="fas fa-plus me-2"></i>Tambah Menu Baru
        </a>
      </div>
    </div>
  </div>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if (session('failed'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('failed') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th style="width: 60px;">#</th>
              <th>Nama Menu</th>
              <th style="width: 200px;">Harga</th>
              <th style="width: 260px;" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($menus as $index => $menu)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td class="fw-semibold">{{ $menu->name_menu }}</td>
                <td class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                <td class="text-end">
                  <div class="btn-group" role="group" aria-label="Aksi">
                    <a href="{{ route('menu.show', $menu->id) }}" class="btn btn-sm btn-outline-secondary">
                      <i class="fas fa-eye me-1"></i>Lihat
                    </a>
                    <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('menu.destroy', $menu->id) }}" class="d-inline" onsubmit="return confirm('Hapus menu ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash me-1"></i>Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center py-4">
                  <div class="text-muted">Belum ada data menu.</div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection