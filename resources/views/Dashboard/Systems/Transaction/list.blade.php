@extends('layouts.dashboard.app')

@section('title', 'Daftar Transaksi - feel')
@section('breadcrumb', 'Transaksi / Daftar')
@section('page-title', 'Daftar Transaksi')

@section('content')
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-0">Daftar Transaksi</h4>
        <small class="text-muted">Semua transaksi yang telah diproses</small>
      </div>
      <div>
        <a href="{{ route('transaction.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
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
              <th style="width:60px;">#</th>
              <th>Pelanggan</th>
              <th style="width:120px;">Meja</th>
              <th style="width:160px;">Jumlah Transaksi</th>
              <th style="width:150px;">Total</th>
              <th style="width:150px;">Dibayar</th>
              <th style="width:180px;">Tanggal</th>
              <th style="width:100px;" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($groupedTransaksis as $index => $group)
              @php
                $customer = optional($group->customer);
                $meja = optional($group->meja);
                $totalAmount = (int) ($group->total_amount ?? 0);
                $totalPaid = (int) ($group->total_paid ?? 0);
                $trxCount = (int) ($group->transaction_count ?? 0);
                $date = optional($group->date);
              @endphp
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  <div class="fw-semibold">{{ $customer->name_pelanggan ?? '-' }}</div>
                  <small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $customer->phone_number ?? '-' }}</small>
                </td>
                <td>Meja {{ $meja->nomer_meja ?? '-' }}</td>
                <td>{{ $trxCount }} transaksi</td>
                <td class="fw-semibold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                <td class="text-success">Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                <td>
                  <div>{{ $date ? $date->format('d M Y') : '-' }}</div>
                  <small class="text-muted">{{ $date ? $date->format('H:i') : '' }}</small>
                </td>
                <td class="text-end">
                  <a href="{{ route('report.show', optional($group->latest_transaction)->id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-eye me-1"></i>Lihat
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center py-4 text-muted">Belum ada transaksi.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

