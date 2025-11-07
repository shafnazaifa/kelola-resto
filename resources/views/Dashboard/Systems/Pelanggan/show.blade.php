@extends('layouts.dashboard.app')

@section('title', 'Detail Pelanggan - feel')
@section('breadcrumb', 'Pelanggan / Detail')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-0">Detail Pelanggan</h4>
        <small class="text-muted">Informasi lengkap pelanggan</small>
      </div>
      <div>
        <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary btn-sm">
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

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="d-flex align-items-center mb-3">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width:64px;height:64px;">
              <i class="fas fa-user"></i>
            </div>
            <div>
              <h5 class="mb-1">{{ $pelanggan->name_pelanggan }}</h5>
              <small class="text-muted">ID: #{{ $pelanggan->id }}</small>
            </div>
          </div>
          <div class="row g-3">
            <div class="col-12">
              <div class="d-flex align-items-center">
                <div class="me-2 text-muted"><i class="fas fa-venus-mars"></i></div>
                <div>
                  <div class="text-muted small">Jenis Kelamin</div>
                  @if($pelanggan->gender)
                    <span class="badge rounded-pill bg-primary-subtle text-primary"><i class="fas fa-mars me-1"></i>Laki-laki</span>
                  @else
                    <span class="badge rounded-pill bg-danger-subtle text-danger"><i class="fas fa-venus me-1"></i>Perempuan</span>
                  @endif
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="d-flex align-items-center">
                <div class="me-2 text-muted"><i class="fas fa-phone"></i></div>
                <div>
                  <div class="text-muted small">No. Telepon</div>
                  <div class="fw-semibold">{{ $pelanggan->phone_number }}</div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="d-flex align-items-start">
                <div class="me-2 text-muted"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                  <div class="text-muted small">Alamat</div>
                  <div class="fw-semibold">{{ $pelanggan->address }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if($pelanggan->pesanans->count() > 0)
    <div class="card border-0 shadow-sm mt-4">
      <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-0">Riwayat Pesanan</h6>
            <small class="text-muted">{{ $pelanggan->pesanans->count() }} pesanan • Total Rp {{ number_format($pelanggan->pesanans->sum(function($p) { return $p->menu->harga * $p->jumlah; }), 0, ',', '.') }}</small>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:60px;">#</th>
                <th>Menu</th>
                <th style="width:100px;">Jumlah</th>
                <th style="width:150px;">Harga</th>
                <th style="width:150px;">Subtotal</th>
                <th style="width:160px;">Tanggal</th>
                <th style="width:140px;">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($pelanggan->pesanans->take(10) as $index => $pesanan)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>
                    <div class="fw-semibold">{{ $pesanan->menu->name_menu }}</div>
                    <small class="text-muted">Meja {{ $pesanan->meja->nomer_meja }}</small>
                  </td>
                  <td><span class="badge bg-primary-subtle text-primary">{{ $pesanan->jumlah }}x</span></td>
                  <td>Rp {{ number_format($pesanan->menu->harga, 0, ',', '.') }}</td>
                  <td class="fw-semibold text-success">Rp {{ number_format($pesanan->menu->harga * $pesanan->jumlah, 0, ',', '.') }}</td>
                  <td>
                    <div>{{ $pesanan->created_at->format('d M Y') }}</div>
                    <small class="text-muted">{{ $pesanan->created_at->format('H:i') }}</small>
                  </td>
                  <td>
                    @if($pesanan->transaksi)
                      <span class="badge bg-success-subtle text-success"><i class="fas fa-check-circle me-1"></i>Sudah Dibayar</span>
                    @else
                      <span class="badge bg-warning-subtle text-warning"><i class="fas fa-clock me-1"></i>Belum Dibayar</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @else
    <div class="card border-0 shadow-sm mt-4">
      <div class="card-body text-center text-muted py-5">
        <div class="mb-2"><i class="fas fa-shopping-cart"></i></div>
        <div class="fw-semibold">Belum Ada Pesanan</div>
        <small>Pelanggan ini belum melakukan pesanan apapun</small>
      </div>
    </div>
  @endif
</div>
@endsection
