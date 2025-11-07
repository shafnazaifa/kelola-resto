@extends('layouts.dashboard.app')

@section('title', 'Transaksi Management - feel')
@section('breadcrumb', 'Transaksi')
@section('page-title', 'Transaksi Management')

@section('styles')
<style>
  .order-item { border-left: 3px solid #4ade80; background: #f8fafc; }
</style>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-0">Transaksi</h4>
        <small class="text-muted">Kelola pembayaran pesanan restoran</small>
      </div>
      <div>
        <a href="{{ route('transaction.list') }}" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-list me-1"></i>Lihat Semua Transaksi
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

  @if($pesanans->count() > 0)
    <div class="accordion" id="accordionTransaksi">
      @foreach($pesanans as $mejaId => $tableOrders)
        @php
          $meja = $tableOrders->first()->meja;
          $total = $tableOrders->sum(function($order) { return $order->menu->harga * $order->jumlah; });
          $headingId = 'heading-'.$mejaId;
          $collapseId = 'collapse-'.$mejaId;
        @endphp
        <div class="accordion-item" data-meja-id="{{ $mejaId }}" data-table-number="{{ $meja->nomer_meja }}">
          <h2 class="accordion-header" id="{{ $headingId }}">
            <button class="accordion-button d-flex justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
              <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-primary-subtle text-primary me-2"><i class="fas fa-table"></i></span>
                <span class="fw-semibold">Meja {{ $meja->nomer_meja }}</span>
                <small class="text-muted ms-2">{{ $meja->kursi }} kursi</small>
              </div>
              <div class="ms-auto text-end">
                <div class="fw-bold text-success">Rp {{ number_format($total, 0, ',', '.') }}</div>
                <small class="text-muted">{{ $tableOrders->count() }} item</small>
              </div>
            </button>
          </h2>
          <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="{{ $headingId }}" data-bs-parent="#accordionTransaksi">
            <div class="accordion-body">
              <ul class="list-group list-group-flush mb-3">
                @foreach($tableOrders as $order)
                  <li class="list-group-item order-item rounded"
                      data-name="{{ $order->menu->name_menu }}"
                      data-qty="{{ (int)$order->jumlah }}"
                      data-price="{{ (int)$order->menu->harga }}"
                      data-subtotal="{{ (int)($order->menu->harga * $order->jumlah) }}"
                      data-customer="{{ $order->pelanggan->name_pelanggan }}">
                    <div class="d-flex justify-content-between align-items-start">
                      <div class="pe-2">
                        <div class="fw-semibold">{{ $order->menu->name_menu }}</div>
                        <small class="text-muted d-block"><span class="badge bg-secondary">{{ $order->jumlah }}x</span> Rp {{ number_format($order->menu->harga, 0, ',', '.') }}</small>
                        <small class="text-muted">Pelanggan: {{ $order->pelanggan->name_pelanggan }}</small>
                      </div>
                      <div class="text-end fw-semibold">Rp {{ number_format($order->menu->harga * $order->jumlah, 0, ',', '.') }}</div>
                    </div>
                  </li>
                @endforeach
              </ul>
              <button onclick="processPayment({{ $mejaId }}, {{ $total }})" class="btn btn-success w-100">
                <i class="fas fa-credit-card me-2"></i>Proses Pembayaran
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="card border-0 shadow-sm">
      <div class="card-body text-center py-5">
        <div class="mb-3"><i class="fas fa-receipt text-muted" style="font-size: 2rem;"></i></div>
        <h6 class="fw-semibold mb-1">Tidak Ada Pesanan Tertunda</h6>
        <small class="text-muted d-block mb-3">Semua pesanan sudah dibayar atau belum ada pesanan</small>
        <a href="{{ route('transaction.list') }}" class="btn btn-primary">
          <i class="fas fa-list me-2"></i>Lihat Riwayat Transaksi
        </a>
      </div>
    </div>
  @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function processPayment(mejaId, total) {
  const tableCard = document.querySelector(`[data-meja-id="${mejaId}"]`);
  let orderDetails = '';
  let customerName = '';
  let tableNumber = '';
  
  if (tableCard) {
    const tn = tableCard.getAttribute('data-table-number');
    tableNumber = tn ? `Meja ${tn}` : '';
    const orderItems = tableCard.querySelectorAll('.order-item');
    orderItems.forEach((item, index) => {
      const menuName = item.getAttribute('data-name') || item.querySelector('.fw-semibold')?.textContent || '';
      const quantityNum = parseInt(item.getAttribute('data-qty') || '0', 10);
      const priceNum = parseInt(item.getAttribute('data-price') || '0', 10);
      const subtotalNum = parseInt(item.getAttribute('data-subtotal') || '0', 10);
      const customerData = item.getAttribute('data-customer') || '';
      if (index === 0 && customerData) customerName = customerData;
      orderDetails += `
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
          <div>
            <div style="font-weight: 600; color: #1e293b;">${menuName}</div>
            <div style="font-size: 12px; color: #64748b;">${quantityNum} × Rp ${priceNum.toLocaleString('id-ID')}</div>
          </div>
          <div style="font-weight: 600; color: #059669;">Rp ${subtotalNum.toLocaleString('id-ID')}</div>
        </div>`;
    });
  }

  Swal.fire({
    title: `<div style="color: #1e293b; font-size: 24px; font-weight: 600;">Pembayaran ${tableNumber}</div>`,
    html: `
      <div style="background: #f8fafc; border-radius: 12px; padding: 15px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="display: flex; align-items: center; gap: 8px;">
              <div style="width: 8px; height: 8px; background: #667eea; border-radius: 50%;"></div>
              <span style="font-weight: 600; color: #374151;">${tableNumber}</span>
            </div>
            <div style="width: 1px; height: 20px; background: #d1d5db;"></div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <i class="fas fa-user" style="color: #6b7280; font-size: 14px;"></i>
              <span style="font-weight: 600; color: #374151;">${customerName}</span>
            </div>
          </div>
          <div style="text-align: right;">
            <div style="font-size: 20px; font-weight: bold; color: #059669;">Rp ${total.toLocaleString('id-ID')}</div>
            <div style="font-size: 12px; color: #6b7280;">Total Pembayaran</div>
          </div>
        </div>
      </div>
      
      <div style="display: flex; gap: 30px; margin-top: 20px;">
        <div style="flex: 1;">
          <h3 style="color: #374151; font-size: 16px; font-weight: 600; margin-bottom: 15px;">Detail Pesanan</h3>
          <div style="max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px;">
            ${orderDetails}
          </div>
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-top: 2px solid #e2e8f0; margin-top: 10px;">
            <span style="font-size: 18px; font-weight: 600; color: #1e293b;">Total:</span>
            <span style="font-size: 20px; font-weight: bold; color: #059669;">Rp ${total.toLocaleString('id-ID')}</span>
          </div>
        </div>
        <div style="flex: 1;">
          <h3 style="color: #374151; font-size: 16px; font-weight: 600; margin-bottom: 15px;">Pembayaran</h3>
          <form id="paymentForm" style="display: flex; flex-direction: column; gap: 20px;">
            <input type="hidden" id="meja_id" name="id_meja" value="${mejaId}">
            <div>
              <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Jumlah Pembayaran</label>
              <input type="number" id="bayar" name="bayar" value="${total}"
                     style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s ease; background: #ffffff;" 
                     onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                     onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                     required>
            </div>
          </form>
        </div>
      </div>
    `,
    showCancelButton: true,
    confirmButtonText: '<span style="font-weight: 600;">Proses Pembayaran</span>',
    cancelButtonText: '<span style="font-weight: 600;">Batal</span>',
    confirmButtonColor: '#4ade80',
    cancelButtonColor: '#ef4444',
    width: '800px',
    padding: '30px',
    didOpen: () => {
      setTimeout(() => {
        const bayarInput = document.getElementById('bayar');
        if (bayarInput) bayarInput.value = total;
      }, 50);
    },
    preConfirm: () => {
      const bayarInput = document.getElementById('bayar');
      const mejaIdInput = document.getElementById('meja_id');
      if (!bayarInput || !mejaIdInput) {
        Swal.showValidationMessage('Form tidak ditemukan');
        return false;
      }
      const bayar = parseFloat(bayarInput.value);
      const mejaIdVal = parseInt(mejaIdInput.value);
      if (!bayar || bayar < total) {
        Swal.showValidationMessage(`Jumlah pembayaran tidak mencukupi. Minimal: Rp ${total.toLocaleString('id-ID')}`);
        return false;
      }
      Swal.showLoading();
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("transaction.store") }}';
      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden'; csrfToken.name = '_token';
      csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      form.appendChild(csrfToken);
      const mejaInput = document.createElement('input');
      mejaInput.type = 'hidden'; mejaInput.name = 'id_meja'; mejaInput.value = mejaIdVal; form.appendChild(mejaInput);
      const bayarInputHidden = document.createElement('input');
      bayarInputHidden.type = 'hidden'; bayarInputHidden.name = 'bayar'; bayarInputHidden.value = bayar; form.appendChild(bayarInputHidden);
      document.body.appendChild(form);
      form.submit();
      Swal.close();
      setTimeout(() => { window.location.href = '{{ route("transaction.index") }}'; }, 1200);
      return false;
    }
  });
}
</script>
@endsection

