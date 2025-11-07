@extends('layouts.dashboard.app')

@section('title', 'Manajemen Meja - feel')
@section('breadcrumb', 'Meja')
@section('page-title', 'Manajemen Meja')

@section('styles')
<style>
  .badge-status { font-weight: 600; font-size: 0.8rem; }
</style>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-0">Manajemen Meja</h4>
        <small class="text-muted">Kelola daftar meja restoran</small>
      </div>
      <div>
        <a href="{{ route('meja.create') }}" class="btn btn-primary">
          <i class="fas fa-plus me-2"></i>Tambah Meja Baru
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
              <th>Nomor Meja</th>
              <th style="width: 140px;">Kursi</th>
              <th style="width: 160px;">Status</th>
              <th style="width: 220px;" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($mejas as $index => $meja)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td class="fw-semibold">{{ $meja->nomer_meja }}</td>
                <td><span class="badge bg-secondary">{{ $meja->kursi }} Kursi</span></td>
                <td>
                  @php
                    $badgeClass = 'bg-secondary';
                    if ($meja->status === 'tersedia') $badgeClass = 'bg-success';
                    elseif ($meja->status === 'diisi') $badgeClass = 'bg-warning text-dark';
                    elseif ($meja->status === 'tidak_tersedia') $badgeClass = 'bg-danger';
                  @endphp
                  <span class="badge badge-status {{ $badgeClass }} text-uppercase">{{ str_replace('_', ' ', $meja->status) }}</span>
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group" aria-label="Aksi">
                    <a href="{{ route('meja.edit', $meja->id) }}" class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('meja.destroy', $meja->id) }}" class="d-inline" onsubmit="return confirm('Hapus meja ini?');">
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
                <td colspan="5" class="text-center py-4">
                  <div class="text-muted">Belum ada data meja.</div>
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

@section('scripts')
@endsection
