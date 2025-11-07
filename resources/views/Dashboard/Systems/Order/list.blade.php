@extends('layouts.dashboard.app')

@section('title', 'Order List - feel')
@section('breadcrumb', 'Order List')
@section('page-title', 'Order List')

@section('content')
<div class="row">
  <div class="col-12">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2>Daftar Pesanan</h2>
        <p class="text-muted">Pesanan yang belum dibayar</p>
      </div>
      <div>
        <a href="{{ route('order.index') }}" class="btn btn-primary">
          <i class="fas fa-plus me-2"></i>Buat Pesanan Baru
        </a>
      </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('failed'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('failed') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- Orders List -->
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="fas fa-list me-2 text-primary"></i>
          Pesanan Belum Dibayar
        </h5>
        <small class="text-muted">Daftar pesanan yang menunggu pembayaran</small>
      </div>
      <div class="card-body">
        @if($pesanans->count() > 0)
          @foreach($pesanans as $mejaId => $mejaPesanans)
            @php
              $meja = $mejaPesanans->first()->meja;
              $totalAmount = $mejaPesanans->sum(function($pesanan) {
                return $pesanan->menu->harga * $pesanan->jumlah;
              });
            @endphp
            
            <!-- Table Card -->
            <div class="card mb-4 border-primary">
              <div class="card-body">
                <div class="row align-items-center mb-3">
                  <div class="col-md-8">
                    <div class="d-flex align-items-center">
                      <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <strong>{{ $meja->nomer_meja ?: $meja->id }}</strong>
                      </div>
                      <div>
                        <h5 class="mb-1">Meja {{ $meja->nomer_meja ?: $meja->id }}</h5>
                        <small class="text-muted">{{ $meja->kursi }} kursi • {{ $mejaPesanans->count() }} pesanan</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 text-end">
                    <h4 class="text-primary mb-0">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h4>
                    <small class="text-muted">Total</small>
                  </div>
                </div>

                <!-- Orders List -->
                <div class="row">
                  @foreach($mejaPesanans as $pesanan)
                    <div class="col-12 mb-3">
                      <div class="card">
                        <div class="card-body">
                          <div class="row align-items-center">
                            <div class="col-md-8">
                              <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3">
                                  <i class="fas fa-utensils text-muted"></i>
                                </div>
                                <div>
                                  <h6 class="mb-1">{{ $pesanan->menu->name_menu }}</h6>
                                  <small class="text-muted">
                                    {{ $pesanan->jumlah }}x • Rp {{ number_format($pesanan->menu->harga, 0, ',', '.') }}
                                  </small>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4 text-end">
                              <div class="fw-bold">
                                Rp {{ number_format($pesanan->menu->harga * $pesanan->jumlah, 0, ',', '.') }}
                              </div>
                              <small class="text-muted">
                                {{ $pesanan->created_at->format('H:i') }}
                              </small>
                            </div>
                          </div>
                          
                          <!-- Customer Info -->
                          <div class="mt-3 pt-3 border-top">
                            <div class="d-flex align-items-center text-muted">
                              <i class="fas fa-user me-2"></i>
                              <span>{{ $pesanan->pelanggan->name_pelanggan }}</span>
                              <span class="mx-2">•</span>
                              <i class="fas fa-phone me-1"></i>
                              <span>{{ $pesanan->pelanggan->phone_number }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
                
                <!-- Add Order Button -->
                <div class="mt-4 pt-3 border-top">
                  <button onclick="showAddOrderModal({{ $meja->id }}, '{{ $meja->nomer_meja ?: $meja->id }}')" 
                          class="btn btn-warning w-100">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Pesanan
                  </button>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <!-- Empty State -->
          <div class="text-center py-5">
            <div class="mb-4">
              <i class="fas fa-shopping-cart text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="mb-2">Tidak Ada Pesanan</h4>
            <p class="text-muted mb-4">Belum ada pesanan yang menunggu pembayaran</p>
            <a href="{{ route('order.index') }}" class="btn btn-primary">
              <i class="fas fa-plus me-2"></i>
              Buat Pesanan Baru
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- SweetAlert Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Global variables
let menusData = @json($menus ?? []);
let selectedMenus = [];

// Show add order modal with SweetAlert
async function showAddOrderModal(mejaId, mejaNumber) {
    selectedMenus = [];
    
    // Create menu options HTML
    let menuOptions = '';
    menusData.forEach(menu => {
        menuOptions += `
            <div class="menu-item border border-gray-200 rounded-lg p-3 mb-2 bg-white">
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <h5 class="font-semibold text-gray-800">${menu.name_menu}</h5>
                        <p class="text-sm text-gray-600">Rp ${new Intl.NumberFormat('id-ID').format(menu.harga)}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="decreaseQuantity(${menu.id})" class="w-6 h-6 bg-red-500  rounded-full flex items-center justify-center text-xs hover:bg-red-600">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span id="qty-${menu.id}" class="w-6 text-center font-medium text-sm">0</span>
                        <button type="button" onclick="increaseQuantity(${menu.id})" class="w-6 h-6 bg-green-500  rounded-full flex items-center justify-center text-xs hover:bg-green-600">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    // Show SweetAlert modal
    const { value: result } = await Swal.fire({
        title: `Tambah Pesanan - Meja ${mejaNumber}`,
        html: `
            <div class="text-left">
                <p class="text-gray-600 mb-4">Pilih menu dan jumlah yang ingin ditambahkan:</p>
                <div class="max-h-80 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50" id="menuContainer">
                    ${menuOptions}
                </div>
                <div id="selectedSummary" class="mt-4 hidden">
                    <h4 class="font-semibold text-gray-800 mb-2">Pesanan yang akan ditambahkan:</h4>
                    <div id="selectedList" class="space-y-2"></div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Tambah Pesanan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ff6b35',
        cancelButtonColor: '#e74c3c',
        width: '600px',
        allowOutsideClick: false,
        preConfirm: () => {
            if (selectedMenus.length === 0) {
                Swal.showValidationMessage('Pilih minimal satu menu');
                return false;
            }
            return selectedMenus;
        }
    });
    
    // Process result
    if (result && result.length > 0) {
        await submitAddOrder(mejaId, result);
    }
}

// Increase quantity
function increaseQuantity(menuId) {
    const menu = menusData.find(m => m.id === menuId);
    if (!menu) return;
    
    const existingIndex = selectedMenus.findIndex(m => m.id === menuId);
    if (existingIndex !== -1) {
        selectedMenus[existingIndex].quantity++;
    } else {
        selectedMenus.push({
            id: menu.id,
            name: menu.name_menu,
            price: menu.harga,
            quantity: 1
        });
    }
    
    updateQuantityDisplay(menuId);
    updateSelectedSummary();
}

// Decrease quantity
function decreaseQuantity(menuId) {
    const existingIndex = selectedMenus.findIndex(m => m.id === menuId);
    if (existingIndex !== -1) {
        selectedMenus[existingIndex].quantity--;
        if (selectedMenus[existingIndex].quantity <= 0) {
            selectedMenus.splice(existingIndex, 1);
        }
    }
    
    updateQuantityDisplay(menuId);
    updateSelectedSummary();
}

// Update quantity display
function updateQuantityDisplay(menuId) {
    const qtyElement = document.getElementById(`qty-${menuId}`);
    if (qtyElement) {
        const selectedMenu = selectedMenus.find(m => m.id === menuId);
        qtyElement.textContent = selectedMenu ? selectedMenu.quantity : 0;
    }
}

// Update selected summary
function updateSelectedSummary() {
    const summaryDiv = document.getElementById('selectedSummary');
    const selectedList = document.getElementById('selectedList');
    
    if (selectedMenus.length > 0) {
        summaryDiv.classList.remove('hidden');
        
        let html = '';
        selectedMenus.forEach(menu => {
            html += `
                <div class="flex justify-between items-center bg-orange-50 border border-orange-200 rounded p-2">
                    <span class="text-sm font-medium text-orange-800">${menu.name} x${menu.quantity}</span>
                    <span class="text-sm font-semibold text-orange-700">Rp ${new Intl.NumberFormat('id-ID').format(menu.price * menu.quantity)}</span>
                </div>
            `;
        });
        
        selectedList.innerHTML = html;
    } else {
        summaryDiv.classList.add('hidden');
    }
}

// Submit add order
async function submitAddOrder(mejaId, selectedMenus) {
    // Prepare order data
    const orderData = {
        id_meja: mejaId,
        orders: selectedMenus.map(menu => ({
            id_menu: menu.id,
            jumlah: menu.quantity
        }))
    };
    
    console.log('Submitting order data:', orderData);
    
    // Show loading
    Swal.fire({
        title: 'Memproses Pesanan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', csrfToken);
        
        const response = await fetch('/dashboard/order/add-to-existing', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(orderData)
        });
        
        // Check if response is ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get response text first to check if it's JSON
        const responseText = await response.text();
        console.log('Response text:', responseText);
        
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            console.error('JSON parse error:', parseError);
            console.error('Response was not JSON:', responseText);
            throw new Error('Server returned invalid response');
        }
        
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Pesanan berhasil ditambahkan',
                confirmButtonColor: '#ff6b35'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: result.message || 'Terjadi kesalahan saat menambah pesanan',
                confirmButtonColor: '#e74c3c'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Gagal mengirim pesanan: ' + error.message,
                     confirmButtonColor: '#e74c3c'
        });
    }
}
</script>

@endsection

