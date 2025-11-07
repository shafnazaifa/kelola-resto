@extends('layouts.dashboard.app')

@section('title', 'Dashboard - feel')
@section('breadcrumb', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
      <div class="container-fluid">
        <div class="card mb-4 border-0 shadow-sm">
          <div class="card-body d-flex align-items-center justify-content-between py-3">
            <div>
              <h5 class="mb-1">Selamat Datang, {{ $user->name_user ?? 'User' }}!</h5>
              <div class="text-muted small">{{ ucfirst($user->role ?? 'user') }} • {{ now()->format('d F Y') }}</div>
            </div>
            <div class="text-end">
              <div class="fs-4 fw-semibold">{{ now()->format('H:i') }}</div>
              <div class="text-muted small">{{ now()->format('l') }}</div>
            </div>
          </div>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body d-flex align-items-start justify-content-between">
                <div>
                  <div class="text-muted text-uppercase small mb-1">Pendapatan Hari Ini</div>
                  <div class="fs-5 fw-semibold">Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</div>
                  <div class="small text-muted">{{ $todayTransactionCount ?? 0 }} transaksi</div>
                </div>
                <div class="rounded-circle bg-light border text-secondary d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                  <i class="fas fa-money-bill-wave"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body d-flex align-items-start justify-content-between">
                <div>
                  <div class="text-muted text-uppercase small mb-1">Pelanggan Hari Ini</div>
                  <div class="fs-5 fw-semibold">{{ $todayCustomers ?? 0 }}</div>
                  <div class="small text-muted">{{ $todayOrders ?? 0 }} pesanan</div>
                </div>
                <div class="rounded-circle bg-light border text-secondary d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                  <i class="fas fa-users"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body d-flex align-items-start justify-content-between">
                <div>
                  <div class="text-muted text-uppercase small mb-1">Status Meja</div>
                  <div class="fs-5 fw-semibold">{{ $activeTables ?? 0 }}/{{ ($activeTables ?? 0) + ($availableTables ?? 0) }}</div>
                  <div class="small text-muted">{{ $availableTables ?? 0 }} tersedia</div>
                </div>
                <div class="rounded-circle bg-light border text-secondary d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                  <i class="fas fa-table"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body d-flex align-items-start justify-content-between">
                <div>
                  <div class="text-muted text-uppercase small mb-1">Total Dibayar</div>
                  <div class="fs-5 fw-semibold">Rp {{ number_format($todayPaid ?? 0, 0, ',', '.') }}</div>
                  <div class="small text-muted">Kembalian: Rp {{ number_format(($todayPaid ?? 0) - ($todayRevenue ?? 0), 0, ',', '.') }}</div>
                </div>
                <div class="rounded-circle bg-light border text-secondary d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                  <i class="fas fa-coins"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-lg-7">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="mb-0">Statistik Restoran</h6>
                    <small class="text-muted"><i class="fa fa-calendar me-1 text-success"></i>{{ now()->format('d F Y') }} • Data hari ini</small>
                  </div>
                </div>
              </div>
              <div class="card-body" style="height: 360px;">
                <canvas id="chart-line"></canvas>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-header">
                <h6 class="mb-0">Ringkasan Hari Ini</h6>
              </div>
              <div class="card-body">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex align-items-center">
                    <span class="badge rounded-pill bg-primary-subtle text-primary me-3"><i class="fas fa-shopping-cart"></i></span>
                    <div>
                      <div class="fw-semibold">Pesanan Hari Ini</div>
                      <small class="text-muted">{{ $todayOrders ?? 0 }} pesanan • {{ $todayTransactionCount ?? 0 }} transaksi</small>
                    </div>
                  </li>
                  <li class="list-group-item d-flex align-items-center">
                    <span class="badge rounded-pill bg-success-subtle text-success me-3"><i class="fas fa-table"></i></span>
                    <div>
                      <div class="fw-semibold">Status Meja</div>
                      <small class="text-muted">{{ $activeTables ?? 0 }} terisi • {{ $availableTables ?? 0 }} tersedia</small>
                    </div>
                  </li>
                  <li class="list-group-item d-flex align-items-center">
                    <span class="badge rounded-pill bg-warning-subtle text-warning me-3"><i class="fas fa-money-bill-wave"></i></span>
                    <div>
                      <div class="fw-semibold">Pendapatan</div>
                      <small class="text-muted">Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }} hari ini</small>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart data from database
    const chartData = {
        labels: {!! json_encode($chartLabels ?? []) !!},
        datasets: [{
            label: 'Pendapatan Harian',
            data: {!! json_encode($chartData ?? []) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            yAxisID: 'y'
        }, {
            label: 'Jumlah Transaksi',
            data: {!! json_encode($chartTransactionCount ?? []) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1,
            yAxisID: 'y1'
        }]
    };

    // Chart configuration
    const config = {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Statistik Pendapatan Restoran'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' transaksi';
                        }
                    }
                }
            }
        }
    };

    // Create chart
    const ctx = document.getElementById('chart-line').getContext('2d');
    new Chart(ctx, config);
});
</script>
@endsection
