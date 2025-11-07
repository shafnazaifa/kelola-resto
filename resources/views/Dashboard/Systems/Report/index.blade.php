@extends('layouts.dashboard.app')

@section('title', 'Riwayat Report - feel')
@section('breadcrumb', 'Report / Riwayat')
@section('page-title', 'Report Transaksi')

@section('content')
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-0">Riwayat Report</h4>
        <small class="text-muted">Lihat semua transaksi yang telah diproses</small>
      </div>
      <div>
        <a href="{{ route('report.download.all') }}" class="btn btn-primary btn-sm">
          <i class="fas fa-download me-1"></i>Download Semua Laporan
        </a>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-light border text-secondary d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;">
            <i class="fas fa-users"></i>
          </div>
          <div>
            <div class="fs-5 fw-semibold">{{ $groupedTransaksis->count() }}</div>
            <small class="text-muted">Total Pelanggan</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-light border text-secondary d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;">
            <i class="fas fa-money-bill-wave"></i>
          </div>
          <div>
            <div class="fs-5 fw-semibold">Rp {{ number_format($groupedTransaksis->sum('total_amount'), 0, ',', '.') }}</div>
            <small class="text-muted">Total Pendapatan</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-light border text-secondary d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;">
            <i class="fas fa-coins"></i>
          </div>
          <div>
            <div class="fs-5 fw-semibold">Rp {{ number_format($groupedTransaksis->sum('total_paid'), 0, ',', '.') }}</div>
            <small class="text-muted">Total Dibayar</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th style="width:60px;">#</th>
              <th>Pelanggan</th>
              <th style="width:120px;">Meja</th>
              <th style="width:160px;">Jumlah Transaksi</th>
              <th style="width:160px;">Total</th>
              <th style="width:160px;">Dibayar</th>
              <th style="width:180px;">Tanggal</th>
              <th style="width:100px;" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($groupedTransaksis as $index => $group)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  <div class="fw-semibold">{{ $group->customer->name_pelanggan }}</div>
                  <small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $group->customer->phone_number }}</small>
                </td>
                <td>Meja {{ $group->meja->nomer_meja }}</td>
                <td>{{ $group->transaction_count }} transaksi</td>
                <td>Rp {{ number_format($group->total_amount, 0, ',', '.') }}</td>
                <td class="text-success">Rp {{ number_format($group->total_paid, 0, ',', '.') }}</td>
                <td>
                  <div>{{ $group->date->format('d M Y') }}</div>
                  <small class="text-muted">{{ $group->date->format('H:i') }}</small>
                </td>
                <td class="text-end">
                  <a href="{{ route('report.show', $group->latest_transaction->id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-eye me-1"></i>Lihat
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center py-4 text-muted">Belum ada report.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

