@extends('layouts.dashboard.app')

@section('title', 'Pelanggan Management - feel')
@section('breadcrumb', 'Pelanggan')
@section('page-title', 'Pelanggan Management')

@section('content')
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-0">Manajemen Pelanggan</h4>
        <small class="text-muted">Lihat data pelanggan restoran</small>
      </div>
      <div>
        <a href="{{ route('dashboard.page') }}" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left me-1"></i>Kembali
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
              <th>Nama Pelanggan</th>
              <th style="width: 160px;">Jenis Kelamin</th>
              <th style="width: 180px;">No. Telepon</th>
              <th>Alamat</th>
              <th style="width: 120px;" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pelanggans as $index => $pelanggan)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td class="fw-semibold">{{ $pelanggan->name_pelanggan }}</td>
                <td>
                  @if($pelanggan->gender)
                    <span class="badge rounded-pill bg-primary-subtle text-primary"><i class="fas fa-mars me-1"></i>Laki-laki</span>
                  @else
                    <span class="badge rounded-pill bg-danger-subtle text-danger"><i class="fas fa-venus me-1"></i>Perempuan</span>
                  @endif
                </td>
                <td><i class="fas fa-phone me-1 text-muted"></i>{{ $pelanggan->phone_number }}</td>
                <td><i class="fas fa-map-marker-alt me-1 text-muted"></i>{{ $pelanggan->address }}</td>
                <td class="text-end">
                  <a href="{{ route('pelanggan.show', $pelanggan->id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-eye me-1"></i>Lihat
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">Belum ada data pelanggan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
