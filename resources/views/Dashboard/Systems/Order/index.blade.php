@extends('layouts.dashboard.app')

@section('title', 'Buat Pesanan - feel')
@section('breadcrumb', 'Order')
@section('page-title', 'Buat Pesanan')


@section('content')
<div class="w-full px-6 py-6 mx-auto">
  <!-- Header Section -->
  <div class="mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
      <div>
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">Buat Pesanan Baru</h1>
        <p class="text-white dark:text-white text-lg">Pilih menu, meja, dan pelanggan untuk pesanan</p>
      </div>
    </div>
  </div>

  <!-- Success/Error Messages -->
  @if (session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg shadow-sm">
      <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
        <div class="flex-1">
          <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
        <button type="button" onclick="this.parentElement.parentElement.style.display='none'" class="text-green-600 hover:text-green-800 text-xl font-bold cursor-pointer ml-4">&times;</button>
      </div>
    </div>
  @endif

  @if(request('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg shadow-sm">
      <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
        <div class="flex-1">
          <p class="text-green-800 font-medium">Pesanan berhasil dibuat!</p>
        </div>
        <button type="button" onclick="this.parentElement.parentElement.style.display='none'" class="text-green-600 hover:text-green-800 text-xl font-bold cursor-pointer ml-4">&times;</button>
      </div>
    </div>
  @endif

  @if (session('failed'))
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg shadow-sm">
      <div class="flex items-center">
        <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg"></i>
        <div class="flex-1">
          <p class="text-red-800 font-medium">{{ session('failed') }}</p>
        </div>
        <button type="button" onclick="this.parentElement.parentElement.style.display='none'" class="text-red-600 hover:text-red-800 text-xl font-bold cursor-pointer ml-4">&times;</button>
      </div>
    </div>
  @endif

  <form method="POST" action="{{ route('order.store') }}" id="orderForm">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Table Selection -->
      <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden card-hover">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700">
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center">
            <i class="fas fa-chair mr-2 text-blue-500"></i>
            Pilih Meja
          </h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Klik tombol Pilih pada baris meja untuk memilih</p>
        </div>
        
        <div class="p-0">
          <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle table-selectable" id="tableList">
              <thead class="table-light">
                <tr>
                  <th style="width:60px;">#</th>
                  <th>Nomor Meja</th>
                  <th style="width:140px;">Kursi</th>
                  <th style="width:160px;">Status</th>
                  <th style="width:160px;" class="text-end">Aksi</th>
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
                      <span class="badge text-uppercase {{ $badgeClass }}">{{ str_replace('_', ' ', $meja->status) }}</span>
                    </td>
                    <td class="text-end">
                      <button type="button" class="btn btn-sm btn-outline-primary" {{ $meja->status !== 'tersedia' ? 'disabled' : '' }} onclick="selectTableFromList({{ $meja->id }}, '{{ $meja->nomer_meja }}', {{ $meja->kursi }}); markSelectedRow(this)">
                        <i class="fas fa-check me-1"></i>Pilih
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data meja.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>

      <!-- Menu Selection -->
      <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden card-hover mt-4">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700">
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center">
            <i class="fas fa-utensils mr-2 text-blue-500"></i>
            Pilih Menu
          </h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Klik menu yang ingin dipesan</p>
        </div>
        
        <div class="row g-3">
          @forelse($menus as $index => $menu)
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card h-100" role="button" onclick="selectMenu(this, {{ $menu->id }}, '({{ $menu->name_menu ?? $menu->name_menu }})', {{ (int) $menu->harga }})">
                <div class="card-body d-flex align-items-center">
                  
                  <div class="flex-grow-1">
                    <div class="fw-semibold" title="{{ $menu->name_menu }}">{{ $menu->name_menu }}</div>
                    <small class="text-muted">Rp {{ number_format($menu->harga, 0, ',', '.') }}</small>
                  </div>
                  <div class="menu-selection-indicator ms-2">
                    <i class="fas fa-plus"></i>
                  </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                  <small class="text-muted">Klik untuk pilih</small>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12 text-center py-4 text-muted">Belum ada menu.</div>
          @endforelse
        </div>
      </div>

      

    <!-- Order Summary -->
    <div class="mt-8 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden card-hover mt-4">
      <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center">
          <i class="fas fa-book mr-2 text-blue-500"></i>
          Ringkasan Pesanan
        </h3>
      </div>
      
      <div class="p-6">
        <div id="orderSummary" class="order-summary">
          <div class="text-center py-8">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-book text-2xl text-slate-400 dark:text-slate-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-600 dark:text-slate-400 mb-2">Belum Ada Item</h3>
            <p class="text-slate-500 dark:text-slate-500">Pilih menu, meja, dan pelanggan untuk melanjutkan</p>
          </div>
        </div>
        
        <!-- Hidden Inputs -->
        <input type="hidden" id="selectedTableId" name="id_meja" value="">
        <input type="hidden" id="selectedPelangganId" name="id_pelanggan" value="">
        
        <!-- Submit Button -->
        <div class="d-flex justify-content-end mt-3">
          <button type="button" id="submitOrder" class="btn btn-primary px-4" disabled onclick="showCustomerAlert()">
            <i class="fas fa-check mr-2"></i>
            Buat Pesanan
          </button>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
/* SweetAlert Custom Styles */
.swal2-popup-custom {
  border-radius: 20px !important;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
}

.swal2-confirm-custom {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  border: none !important;
  border-radius: 12px !important;
  padding: 12px 24px !important;
  font-weight: 600 !important;
  font-size: 14px !important;
  color: white !important;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
  transition: all 0.3s ease !important;
}

.swal2-confirm-custom:hover {
  background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%) !important;
  transform: translateY(-2px) !important;
  box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5) !important;
}

.swal2-cancel-custom {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
  border: none !important;
  border-radius: 12px !important;
  padding: 12px 24px !important;
  font-weight: 600 !important;
  font-size: 14px !important;
  color: white !important;
  box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4) !important;
  transition: all 0.3s ease !important;
}

.swal2-cancel-custom:hover {
  background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
  transform: translateY(-2px) !important;
  box-shadow: 0 6px 16px rgba(239, 68, 68, 0.5) !important;
}

.swal2-title-custom {
  margin-bottom: 0 !important;
  padding-bottom: 0 !important;
}

/* SweetAlert Input Focus Styles */
.swal2-popup input:focus,
.swal2-popup select:focus,
.swal2-popup textarea:focus {
  outline: none !important;
  border-color: #667eea !important;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}

/* SweetAlert Form Styling */
.swal2-popup form {
  margin-top: 0 !important;
}

.swal2-popup label {
  font-weight: 500 !important;
  color: #374151 !important;
  margin-bottom: 8px !important;
  display: block !important;
}

.swal2-popup input,
.swal2-popup select,
.swal2-popup textarea {
  border-radius: 12px !important;
  border: 2px solid #e5e7eb !important;
  padding: 12px 16px !important;
  font-size: 14px !important;
  transition: all 0.3s ease !important;
  background: #ffffff !important;
}

.swal2-popup input:hover,
.swal2-popup select:hover,
.swal2-popup textarea:hover {
  border-color: #d1d5db !important;
}

/* SweetAlert Container */
.swal2-container {
  z-index: 9999 !important;
}

/* Hide any unwanted checkboxes */
.swal2-popup input[type="checkbox"] {
  display: none !important;
}

/* Ensure no checkbox styling */
.swal2-popup .swal2-checkbox {
  display: none !important;
}

/* Hide any default form elements that might appear */
/* Keep validation messages visible */
.swal2-popup .swal2-validation-message { display: block !important; }
</style>
<script>
let selectedMenus = []; // Array to store multiple selected menus
let selectedTable = null;

function selectTableFromList(tableId, tableNumber, tableSeats) {
    selectedTable = { id: tableId, number: tableNumber, seats: tableSeats };
    updateOrderSummary();
    checkFormValidity();
}

function selectMenu(el, menuId, menuName, menuPrice) {
    const existingMenuIndex = selectedMenus.findIndex(m => m.id === menuId);
    if (existingMenuIndex !== -1) {
        selectedMenus.splice(existingMenuIndex, 1);
        el.classList.remove('selected');
        const indicator = el.querySelector('.menu-selection-indicator');
        if (indicator) {
            indicator.innerHTML = '<i class="fas fa-plus"></i>';
            indicator.style.background = 'rgba(107, 114, 128, 0.1)';
            indicator.style.color = '#6b7280';
        }
    } else {
        selectedMenus.push({ id: menuId, name: menuName, price: Number(menuPrice), quantity: 1 });
        el.classList.add('selected');
        const indicator = el.querySelector('.menu-selection-indicator');
        if (indicator) {
            indicator.innerHTML = '<i class="fas fa-check"></i>';
            indicator.style.background = 'linear-gradient(135deg, #4ade80 0%, #22c55e 100%)';
            indicator.style.color = 'white';
        }
    }
    updateOrderSummary();
    checkFormValidity();
}

function selectTable(el, tableId, tableNumber, tableSeats) {
    selectedTable = { id: tableId, number: tableNumber, seats: tableSeats };
    document.querySelectorAll('.table-visual-order').forEach(table => table.classList.remove('selected'));
    el.classList.add('selected');
    updateOrderSummary();
    checkFormValidity();
}

function showCustomerAlert() {
    if (selectedMenus.length === 0 || !selectedTable) {
        Swal.fire({
            title: 'Pilih Menu dan Meja',
            text: 'Silakan pilih minimal 1 menu dan meja terlebih dahulu',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#667eea'
        });
        return;
    }
    
    Swal.fire({
        title: '<div style="color: #1e293b; font-size: 24px; font-weight: 600;">Informasi Pelanggan</div>',
        html: `
            <div style="display: flex; gap: 30px; margin-top: 20px;">
                <!-- Preview Section -->
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f8fafc; border-radius: 16px; padding: 30px; border: 2px solid #e2e8f0;">
                    <h3 style="color: #374151; font-size: 16px; font-weight: 600; margin-bottom: 20px;">Preview Pesanan</h3>
                    
                    <!-- Table Preview -->
                    <div style="margin-bottom: 25px;">
                        <div style="font-size: 14px; font-weight: 500; color: #6b7280; margin-bottom: 10px; text-align: center;">Meja Terpilih</div>
                        <div id="tablePreviewOrder" style="position: relative; width: 100px; height: 100px; margin: 0 auto;">
                            <div style="width: 70px; height: 70px; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 14px; border: 3px solid white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);">
                                ${selectedTable.number}
                            </div>
                        </div>
                        <div style="text-align: center; margin-top: 10px; font-size: 12px; color: #6b7280;">
                            ${selectedTable.seats} Kursi
                        </div>
                    </div>
                    
                    <!-- Menu Preview -->
                    <div style="width: 100%;">
                        <div style="font-size: 14px; font-weight: 500; color: #6b7280; margin-bottom: 15px; text-align: center;">Menu Terpilih</div>
                        <div id="menuPreviewOrder" style="max-height: 200px; overflow-y: auto; space-y: 8px;">
                            <!-- Menu items will be added here -->
                        </div>
                    </div>
                </div>
                
                <!-- Form Section -->
                <div style="flex: 1;">
                    <form id="customerForm" style="display: flex; flex-direction: column; gap: 20px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Nama Pelanggan</label>
                            <input type="text" id="customerNameInput" name="name_pelanggan" 
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s ease; background: #ffffff;" 
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                                   onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                                   placeholder="Masukkan nama pelanggan"
                                   required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Jenis Kelamin</label>
                            <select id="customerGenderInput" name="gender" 
                                    style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s ease; background: #ffffff; cursor: pointer;" 
                                    onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                                    required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="1">Laki-laki</option>
                                <option value="0">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Nomor Telepon</label>
                            <input type="text" id="customerPhoneInput" name="phone_number" 
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s ease; background: #ffffff;" 
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                                   onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                                   placeholder="08xxxxxxxxxx"
                                   required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Alamat</label>
                            <textarea id="customerAddressInput" name="address" 
                                      style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s ease; background: #ffffff; resize: vertical; min-height: 80px;" 
                                      onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                                      onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                                      placeholder="Masukkan alamat lengkap"
                                      required></textarea>
                        </div>
                    </form>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Buat Pesanan',
        cancelButtonText: 'Batal',
        width: '900px',
        padding: '30px',
        customClass: {
            popup: 'swal2-popup-custom',
            confirmButton: 'swal2-confirm-custom',
            cancelButton: 'swal2-cancel-custom',
            title: 'swal2-title-custom'
        },
        buttonsStyling: false,
        didOpen: () => {
            // Populate menu preview
            const menuPreview = document.getElementById('menuPreviewOrder');
            if (menuPreview) {
                let menuHtml = '';
                let totalPrice = 0;
                
                selectedMenus.forEach((menu, index) => {
                    const itemTotal = menu.price * menu.quantity;
                    totalPrice += itemTotal;
                    
                    menuHtml += `
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: white; border-radius: 8px; margin-bottom: 8px; border: 1px solid #e5e7eb;">
                            <div style="flex: 1;">
                                <div style="font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 2px;">${menu.name}</div>
                                <div style="font-size: 11px; color: #6b7280;">Qty: ${menu.quantity} × Rp ${menu.price.toLocaleString('id-ID')}</div>
                            </div>
                            <div style="font-size: 12px; font-weight: 600; color: #059669;">
                                Rp ${itemTotal.toLocaleString('id-ID')}
                            </div>
                        </div>
                    `;
                });
                
                if (totalPrice > 0) {
                    menuHtml += `
                        <div style="border-top: 2px solid #e5e7eb; padding-top: 8px; margin-top: 8px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; font-weight: 600; color: #1f2937;">
                                <span>Total:</span>
                                <span style="color: #059669;">Rp ${totalPrice.toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                    `;
                }
                
                menuPreview.innerHTML = menuHtml;
            }
        },
        preConfirm: () => {
            const form = document.getElementById('customerForm');
            const formData = new FormData(form);
            
            // Validate form
            const name = document.getElementById('customerNameInput').value.trim();
            const gender = document.getElementById('customerGenderInput').value;
            const phone = document.getElementById('customerPhoneInput').value.trim();
            const address = document.getElementById('customerAddressInput').value.trim();
            
            if (!name || !gender || !phone || !address) {
                Swal.showValidationMessage('Semua field harus diisi');
                return false;
            }
            
            // Validate phone number format
            if (!phone.match(/^08[0-9]{8,12}$/)) {
                Swal.showValidationMessage('Format nomor telepon tidak valid (contoh: 081234567890)');
                return false;
            }
            
            // Create customer via AJAX
            return fetch('/dashboard/pelanggan', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                const contentType = response.headers.get('content-type') || '';
                if (!response.ok) {
                    if (contentType.includes('application/json')) {
                        const err = await response.json();
                        const firstError = err?.errors ? Object.values(err.errors)[0][0] : (err?.message || 'Terjadi kesalahan');
                        throw new Error(firstError);
                    }
                    throw new Error('Gagal membuat pelanggan');
                }
                if (!contentType.includes('application/json')) {
                    throw new Error('Respon tidak valid dari server');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Set customer ID to hidden input
                    document.getElementById('selectedPelangganId').value = data.pelanggan.id;
                    // Build a single batched payload for multiple menus
                    const payload = new FormData();
                    payload.append('id_meja', selectedTable.id);
                    payload.append('id_pelanggan', data.pelanggan.id);
                    selectedMenus.forEach((menu, idx) => {
                        payload.append(`orders[${idx}][id_menu]`, menu.id);
                        payload.append(`orders[${idx}][jumlah]`, menu.quantity);
                    });
                    payload.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    return fetch('/dashboard/order', {
                        method: 'POST',
                        body: payload,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                } else {
                    throw new Error('Gagal membuat pelanggan');
                }
            })
            .then((resp) => {
                if (!resp.ok) throw new Error('Gagal membuat pesanan');
                // Redirect to order page with success message
                window.location.href = '/dashboard/order?success=1';
            })
            .catch(error => {
                Swal.showValidationMessage(`Error: ${error.message}`);
            });
        }
    });
}

function updateOrderSummary() {
    const summaryDiv = document.getElementById('orderSummary');
    
    if (selectedMenus.length === 0 || !selectedTable) {
        summaryDiv.innerHTML = `
            <div class="text-center py-5 text-muted">
                <i class="fas fa-book mb-2"></i>
                <div class="fw-semibold">Belum Ada Item</div>
                <small>Pilih menu dan meja untuk melanjutkan</small>
            </div>
        `;
        return;
    }
    
    let totalPrice = 0;
    let itemsHtml = '';
    
    selectedMenus.forEach((menu, index) => {
        const itemTotal = menu.price * menu.quantity;
        totalPrice += itemTotal;
        
        itemsHtml += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="me-3">
                    <div class="fw-semibold">${menu.name}</div>
                    <small class="text-muted">Rp ${menu.price.toLocaleString('id-ID')} × ${menu.quantity}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button onclick="changeMenuQuantity(${index}, -1)" class="btn btn-sm btn-outline-secondary"><i class="fas fa-minus"></i></button>
                    <input type="number" value="${menu.quantity}" min="1" class="form-control form-control-sm" style="width:70px" onchange="updateMenuQuantity(${index}, this.value)">
                    <button onclick="changeMenuQuantity(${index}, 1)" class="btn btn-sm btn-outline-secondary"><i class="fas fa-plus"></i></button>
                    <button onclick="removeMenu(${index})" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                </div>
            </li>
        `;
    });
    
    summaryDiv.innerHTML = `
        <ul class="list-group mb-3">
            ${itemsHtml}
        </ul>
        <div class="d-flex justify-content-between align-items-center border-top pt-3">
            <div class="text-muted small">Meja ${selectedTable.number} • ${selectedTable.seats} Kursi</div>
            <div class="fs-5 fw-bold text-success">Rp ${totalPrice.toLocaleString('id-ID')}</div>
        </div>
    `;
}

function changeMenuQuantity(menuIndex, change) {
    const newQuantity = selectedMenus[menuIndex].quantity + change;
    if (newQuantity >= 1) {
        selectedMenus[menuIndex].quantity = newQuantity;
        updateOrderSummary();
    }
}

function updateMenuQuantity(menuIndex, value) {
    const quantity = parseInt(value);
    if (quantity >= 1) {
        selectedMenus[menuIndex].quantity = quantity;
        updateOrderSummary();
    }
}

function removeMenu(menuIndex) {
    selectedMenus.splice(menuIndex, 1);
    
    // Update visual selection
    document.querySelectorAll('.menu-visual-order').forEach(card => {
        const menuId = card.getAttribute('onclick').match(/\d+/)[0];
        const isSelected = selectedMenus.some(menu => menu.id == menuId);
        
        if (isSelected) {
            card.classList.add('selected');
            const indicator = card.querySelector('.menu-selection-indicator');
            if (indicator) {
                indicator.innerHTML = '<i class="fas fa-check"></i>';
                indicator.style.background = 'linear-gradient(135deg, #4ade80 0%, #22c55e 100%)';
                indicator.style.color = 'white';
            }
        } else {
            card.classList.remove('selected');
            const indicator = card.querySelector('.menu-selection-indicator');
            if (indicator) {
                indicator.innerHTML = '<i class="fas fa-plus"></i>';
                indicator.style.background = 'rgba(107, 114, 128, 0.1)';
                indicator.style.color = '#6b7280';
            }
        }
    });
    
    updateOrderSummary();
    checkFormValidity();
}

function checkFormValidity() {
    const submitButton = document.getElementById('submitOrder');
    const isValid = selectedMenus.length > 0 && selectedTable;
    
    submitButton.disabled = !isValid;
    
    if (isValid) {
        // Update hidden inputs - we'll handle multiple menus in form submission
        document.getElementById('selectedTableId').value = selectedTable.id;
    }
}

function markSelectedRow(buttonEl) {
    const table = document.getElementById('tableList');
    if (!table) return;
    table.querySelectorAll('tbody tr').forEach(tr => tr.classList.remove('table-selected'));
    const tr = buttonEl.closest('tr');
    if (tr) tr.classList.add('table-selected');
}
</script>
@endsection

