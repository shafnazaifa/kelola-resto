@extends('layouts.dashboard.app')

@section('title', 'Detail Report - feel')
@section('breadcrumb', 'Report / Detail')
@section('page-title', 'Detail Report')

@section('content')
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-0">Detail Report</h4>
        <small class="text-muted">Informasi lengkap transaksi</small>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('report.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
        <a href="{{ route('report.download', $transaksi->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-download me-1"></i>Download</a>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
      <h6 class="mb-0"><i class="fas fa-receipt me-2 text-success"></i>Struk Report</h6>
    </div>
    <div class="card-body">
      <div class="text-center mb-4">
        <h5 class="mb-1">feel</h5>
        <small class="text-muted d-block">Jl. Restoran No. 123, Jakarta</small>
        <small class="text-muted">Telp: (021) 1234-5678</small>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <div class="card border-0 bg-light">
            <div class="card-body">
              <div class="mb-2 text-muted small">ID Transaksi</div>
              <div class="fw-semibold">#{{ $transaksi->id }}</div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card border-0 bg-light">
            <div class="card-body">
              <div class="mb-2 text-muted small">Meja</div>
              <div class="fw-semibold">Meja {{ $transaksi->pesanan->meja->nomer_meja }}</div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card border-0 bg-light">
            <div class="card-body">
              <div class="mb-2 text-muted small">Pelanggan</div>
              <div class="fw-semibold">{{ $transaksi->pesanan->pelanggan->name_pelanggan }}</div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card border-0 bg-light">
            <div class="card-body">
              <div class="mb-2 text-muted small">Tanggal & Waktu</div>
              <div>{{ $transaksi->created_at->format('d M Y') }} • {{ $transaksi->created_at->format('H:i') }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mt-3">
        <div class="card-header bg-white">
          <h6 class="mb-0"><i class="fas fa-list me-2 text-primary"></i>Detail Pesanan ({{ $allTransaksis->count() }} transaksi)</h6>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>Menu</th>
                  <th style="width:120px;">Jumlah</th>
                  <th style="width:150px;">Harga</th>
                  <th style="width:150px;">Subtotal</th>
                  <th style="width:160px;">ID Transaksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($allTransaksis as $trans)
                  <tr>
                    <td>{{ $trans->pesanan->menu->name_menu }}</td>
                    <td>{{ $trans->pesanan->jumlah }}x</td>
                    <td>Rp {{ number_format($trans->pesanan->menu->harga, 0, ',', '.') }}</td>
                    <td class="fw-semibold">Rp {{ number_format($trans->pesanan->menu->harga * $trans->pesanan->jumlah, 0, ',', '.') }}</td>
                    <td>#{{ $trans->id }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="border-top pt-3 mt-3">
        <div class="d-flex justify-content-between"><span class="fw-semibold">Subtotal:</span><span class="fw-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span></div>
        <div class="d-flex justify-content-between"><span class="fw-semibold">Dibayar:</span><span class="fw-bold text-success">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span></div>
        <div class="d-flex justify-content-between border-top pt-2 mt-2"><span class="fw-bold">Kembalian:</span><span class="fw-bold text-primary">Rp {{ number_format($totalPaid - $totalAmount, 0, ',', '.') }}</span></div>
      </div>

      <div class="text-center text-muted small mt-4">Terima kasih telah berkunjung! • Silakan datang kembali</div>
    </div>
  </div>
</div>
@endsection
