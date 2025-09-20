<!-- resources/views/admin/customers/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Kelola Pelanggan')

@section('content')
<!-- Page Header -->
<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="dashboard-title mb-2">Kelola Pelanggan</h1>
            <p class="dashboard-subtitle mb-0">Manajemen data pelanggan PT. Batam General Supplier</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                <i class="bi bi-plus me-2"></i>Tambah Pelanggan
            </button>
        </div>
    </div>
</div>

<div class="card modern-card">
    <div class="card-header">
        <form method="GET" action="{{ route('admin.customers') }}" class="row g-2 align-items-center w-100">

            <!-- Search Bar -->
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari pelanggan..." value="{{ request('search') }}">
            </div>

            <!-- Status Filter -->
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <!-- Order Filter -->
            <div class="col-md-4">
                <select name="orders" class="form-select">
                    <option value="" {{ request('orders') == '' ? 'selected' : '' }}>Semua Pelanggan</option>
                    <option value="with_orders" {{ request('orders') == 'with_orders' ? 'selected' : '' }}>Pernah Order</option>
                    <option value="no_orders" {{ request('orders') == 'no_orders' ? 'selected' : '' }}>Belum Pernah Order</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('admin.customers') }}" class="btn btn-light w-100">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
                <button class="btn btn-outline-secondary btn-sm" onclick="toggleView()">
                    <i class="bi bi-grid-3x3-gap" id="view-icon"></i>
                </button>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        @if($customers->count() > 0)
            <!-- Table View -->
            <div id="table-view">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Pelanggan</th>
                                <th width="200">Email</th>
                                <th width="150">Telepon</th>
                                <th width="200">Total Order</th>
                                <th width="200">Terdaftar</th>
                                <th width="120">Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr class="customer-row text-center">
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-name">{{ $customer->fullname ?: $customer->firstname . ' ' . $customer->lastname }}</div>
                                        <small class="customer-username">{{ $customer->username }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-email">
                                        {{ $customer->email }}
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-phone">
                                        {{ $customer->phone ?: '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="order-count-badge">
                                        <i class="bi bi-bag me-1"></i>
                                        {{ $customer->orders_count }} Order
                                    </span>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <span class="date">{{ $customer->created_at->format('d M Y') }}</span>
                                        <small class="time">{{ $customer->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-active">
                                        Aktif
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-info btn-view" 
                                                data-id="{{ $customer->user_id }}"
                                                title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary btn-edit" 
                                                data-id="{{ $customer->user_id }}"
                                                data-username="{{ $customer->username }}"
                                                data-fullname="{{ $customer->fullname }}"
                                                data-firstname="{{ $customer->firstname }}"
                                                data-lastname="{{ $customer->lastname }}"
                                                data-email="{{ $customer->email }}"
                                                data-phone="{{ $customer->phone }}"
                                                data-shipaddress="{{ $customer->shipaddress }}"
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-delete" 
                                                data-id="{{ $customer->user_id }}"
                                                data-name="{{ $customer->fullname ?: $customer->firstname . ' ' . $customer->lastname }}"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Grid View (Hidden by default) -->
            <div id="grid-view" style="display: none;">
                <div class="customers-grid">
                    @foreach($customers as $customer)
                    <div class="customer-card">
                        <div class="customer-card-header">
                            <div class="customer-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="customer-actions">
                                <button class="btn btn-sm btn-light btn-view" 
                                        data-id="{{ $customer->user_id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-light btn-edit" 
                                        data-id="{{ $customer->user_id }}"
                                        data-username="{{ $customer->username }}"
                                        data-fullname="{{ $customer->fullname }}"
                                        data-firstname="{{ $customer->firstname }}"
                                        data-lastname="{{ $customer->lastname }}"
                                        data-email="{{ $customer->email }}"
                                        data-phone="{{ $customer->phone }}"
                                        data-shipaddress="{{ $customer->shipaddress }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete" 
                                        data-id="{{ $customer->user_id }}"
                                        data-name="{{ $customer->fullname ?: $customer->firstname . ' ' . $customer->lastname }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="customer-card-body">
                            <h5 class="customer-card-title">{{ $customer->fullname ?: $customer->firstname . ' ' . $customer->lastname }}</h5>
                            <p class="customer-card-username">@{{ $customer->username }}</p>
                            <div class="customer-card-info">
                                <div class="info-item">
                                    <i class="bi bi-envelope me-1"></i>
                                    <span>{{ $customer->email }}</span>
                                </div>
                                @if($customer->phone)
                                <div class="info-item">
                                    <i class="bi bi-telephone me-1"></i>
                                    <span>{{ $customer->phone }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="customer-card-meta">
                                <div class="order-count">
                                    <i class="bi bi-bag me-1"></i>
                                    {{ $customer->orders_count }} Order
                                </div>
                                <div class="join-date">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $customer->created_at->format('M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if(method_exists($customers, 'hasPages') && $customers->hasPages())
                <div class="pagination-wrapper">
                    {{ $customers->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h4 class="empty-title">Belum Ada Pelanggan</h4>
                <p class="empty-description">Belum ada pelanggan yang terdaftar dalam sistem</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="bi bi-plus me-2"></i>Tambah Pelanggan Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.customers.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pelanggan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="add_username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_username" name="username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="add_email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="add_firstname" class="form-label">Nama Depan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_firstname" name="firstname" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_lastname" class="form-label">Nama Belakang</label>
                            <input type="text" class="form-control" id="add_lastname" name="lastname">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="add_fullname" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="add_fullname" name="fullname" readonly>
                        <small class="text-muted">Akan terisi otomatis berdasarkan nama depan dan belakang</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="add_phone" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="add_phone" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="add_password" name="password" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="add_shipaddress" class="form-label">Alamat Pengiriman</label>
                        <textarea class="form-control" id="add_shipaddress" name="shipaddress" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pelanggan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" id="editCustomerForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_firstname" class="form-label">Nama Depan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_firstname" name="firstname" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_lastname" class="form-label">Nama Belakang</label>
                            <input type="text" class="form-control" id="edit_lastname" name="lastname">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_fullname" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_fullname" name="fullname" readonly>
                        <small class="text-muted">Akan terisi otomatis berdasarkan nama depan dan belakang</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_phone" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_password" class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_shipaddress" class="form-label">Alamat Pengiriman</label>
                        <textarea class="form-control" id="edit_shipaddress" name="shipaddress" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Pelanggan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Customer Modal -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="customerDetailContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pelanggan <strong id="deleteCustomerName"></strong>?</p>
                <p class="text-warning small">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Customer-specific styles extending the admin layout */

/* Customer Info */
.customer-info .customer-name {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.customer-info .customer-username {
    color: var(--gray-500);
    font-size: 0.75rem;
}

.customer-email, .customer-phone {
    color: var(--gray-600);
    font-size: 0.875rem;
}

/* Order Count Badge */
.order-count-badge {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(16, 185, 129, 0.2);
    display: inline-block;
}

/* Date Info */
.date-info .date {
    font-weight: 600;
    color: var(--gray-700);
    display: block;
}

.date-info .time {
    color: var(--gray-500);
    font-size: 0.75rem;
}

/* Status Badge */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
}

.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-inactive {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.action-buttons .btn {
    border-radius: 8px;
    padding: 0.5rem;
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-1px);
}

/* Grid View Styles */
.customers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

.customer-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    border: 1px solid var(--gray-100);
}

.customer-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.customer-card-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    padding: 1.5rem;
    position: relative;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.customer-avatar {
    font-size: 2.5rem;
    opacity: 0.9;
}

.customer-actions {
    display: flex;
    gap: 0.5rem;
}

.customer-card-body {
    padding: 1.5rem;
}

.customer-card-title {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.25rem;
    font-size: 1.125rem;
}

.customer-card-username {
    color: var(--gray-500);
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.customer-card-info {
    margin-bottom: 1.25rem;
}

.info-item {
    display: flex;
    align-items: center;
    color: var(--gray-600);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.customer-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--gray-100);
    font-size: 0.75rem;
}

.customer-card-meta .order-count {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-weight: 600;
}

.customer-card-meta .join-date {
    color: var(--gray-500);
}

/* Modal Styles */
.modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: var(--shadow-lg);
}

.modal-header {
    background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
    border-bottom: 1px solid var(--gray-100);
    border-radius: 16px 16px 0 0;
}

.modal-title {
    font-weight: 600;
    color: var(--gray-700);
}

.form-label {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

/* Pagination */
.pagination-wrapper {
    padding: 1.5rem;
    border-top: 1px solid var(--gray-100);
    background: var(--gray-50);
    display: flex;
    justify-content: center;
}

.pagination-wrapper .pagination {
    margin: 0;
}

.pagination .page-link {
    border: none;
    color: var(--gray-600);
    padding: 0.75rem 1rem;
    margin: 0 0.25rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
    background: var(--primary);
    color: white;
    box-shadow: var(--shadow);
}

/* Enhanced Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
}

.empty-icon {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 1.5rem;
    display: inline-block;
    padding: 2rem;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.05);
}

.empty-title {
    color: var(--gray-600);
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.empty-description {
    color: var(--gray-500);
    font-size: 1rem;
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.5;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-header .col-lg-4 {
        margin-top: 1rem;
        text-align: left !important;
    }
    
    .customers-grid {
        grid-template-columns: 1fr;
        padding: 1rem;
        gap: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .modern-table th,
    .modern-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .customer-card-meta {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}

@media (max-width: 576px) {
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start !important;
    }
    
    .empty-state {
        padding: 2rem 1rem;
    }
    
    .empty-icon {
        font-size: 3rem;
        padding: 1.5rem;
    }
    
    .customer-card-header {
        padding: 1rem;
    }
    
    .customer-card-body {
        padding: 1rem;
    }
}
</style>

<script>
// Toggle between table and grid view
function toggleView() {
    const tableView = document.getElementById('table-view');
    const gridView = document.getElementById('grid-view');
    const viewIcon = document.getElementById('view-icon');
    
    if (tableView.style.display === 'none') {
        // Switch to table view
        tableView.style.display = 'block';
        gridView.style.display = 'none';
        viewIcon.className = 'bi bi-grid-3x3-gap';
        localStorage.setItem('customerView', 'table');
    } else {
        // Switch to grid view
        tableView.style.display = 'none';
        gridView.style.display = 'block';
        viewIcon.className = 'bi bi-table';
        localStorage.setItem('customerView', 'grid');
    }
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('customerView');
    if (savedView === 'grid') {
        toggleView();
    }

    // Function to create full name functionality
    function setupFullNameAutoFill(firstNameId, lastNameId, fullNameId, modalId) {
        const firstNameInput = document.getElementById(firstNameId);
        const lastNameInput = document.getElementById(lastNameId);
        const fullNameInput = document.getElementById(fullNameId);

        function updateFullName() {
            const firstName = firstNameInput.value.trim();
            const lastName = lastNameInput.value.trim();
            
            if (firstName && lastName) {
                fullNameInput.value = firstName + ' ' + lastName;
            } else if (firstName) {
                fullNameInput.value = firstName;
            } else if (lastName) {
                fullNameInput.value = lastName;
            } else {
                fullNameInput.value = '';
            }
        }

        // Listen for input events on both name fields
        firstNameInput.addEventListener('input', updateFullName);
        lastNameInput.addEventListener('input', updateFullName);

        // Also listen for paste events
        firstNameInput.addEventListener('paste', function() {
            setTimeout(updateFullName, 10);
        });
        lastNameInput.addEventListener('paste', function() {
            setTimeout(updateFullName, 10);
        });

        // Clear form when modal is hidden (for add modal)
        if (modalId === 'addCustomerModal') {
            document.getElementById(modalId).addEventListener('hidden.bs.modal', function() {
                const form = this.querySelector('form');
                if (form) {
                    form.reset();
                    fullNameInput.value = ''; // Ensure full name is also cleared
                }
            });
        }

        // For edit modal, update full name when modal is shown with existing data
        if (modalId === 'editCustomerModal') {
            document.getElementById(modalId).addEventListener('shown.bs.modal', function() {
                // Small delay to ensure data is populated first
                setTimeout(updateFullName, 100);
            });
        }
    }

    // Setup for Add Customer Modal
    setupFullNameAutoFill('add_firstname', 'add_lastname', 'add_fullname', 'addCustomerModal');
    
    // Setup for Edit Customer Modal
    setupFullNameAutoFill('edit_firstname', 'edit_lastname', 'edit_fullname', 'editCustomerModal');
});

// Handle Edit Customer
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-edit')) {
        const btn = e.target.closest('.btn-edit');
        const customerId = btn.getAttribute('data-id');
        
        // Generate URL by replacing :id
        const updateUrl = `{{ route('admin.customers.update', ':id') }}`.replace(':id', customerId);
        
        // Set form action
        document.getElementById('editCustomerForm').action = updateUrl;
        
        // Fill form fields
        document.getElementById('edit_username').value = btn.getAttribute('data-username');
        document.getElementById('edit_fullname').value = btn.getAttribute('data-fullname') || '';
        document.getElementById('edit_firstname').value = btn.getAttribute('data-firstname') || '';
        document.getElementById('edit_lastname').value = btn.getAttribute('data-lastname') || '';
        document.getElementById('edit_email').value = btn.getAttribute('data-email');
        document.getElementById('edit_phone').value = btn.getAttribute('data-phone') || '';
        document.getElementById('edit_shipaddress').value = btn.getAttribute('data-shipaddress') || '';
        
        // Show modal
        const editModal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
        editModal.show();
    }
});

// Handle View Customer
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-view')) {
        const btn = e.target.closest('.btn-view');
        const customerId = btn.getAttribute('data-id');
        
        // You can load customer details via AJAX here
        // For now, we'll show a placeholder
        document.getElementById('customerDetailContent').innerHTML = `
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat detail pelanggan...</p>
            </div>
        `;
        
        const viewModal = new bootstrap.Modal(document.getElementById('viewCustomerModal'));
        viewModal.show();
        
        // Simulate loading customer details
        setTimeout(() => {
            document.getElementById('customerDetailContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Dasar</h6>
                        <table class="table table-sm">
                            <tr><td><strong>ID:</strong></td><td>${customerId}</td></tr>
                            <tr><td><strong>Username:</strong></td><td>@customer_username</td></tr>
                            <tr><td><strong>Email:</strong></td><td>customer@email.com</td></tr>
                            <tr><td><strong>Telepon:</strong></td><td>+62 123 456 789</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Statistik Order</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Total Order:</strong></td><td>5 Order</td></tr>
                            <tr><td><strong>Status:</strong></td><td><span class="badge bg-success">Aktif</span></td></tr>
                            <tr><td><strong>Bergabung:</strong></td><td>01 Jan 2024</td></tr>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Alamat Pengiriman</h6>
                    <p class="text-muted">Jl. Contoh No. 123, Batam, Kepulauan Riau</p>
                </div>
            `;
        }, 1000);
    }
});

// Handle Delete Customer
let deleteCustomerId;

document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-delete')) {
        e.preventDefault();
        const btn = e.target.closest('.btn-delete');
        deleteCustomerId = btn.getAttribute('data-id');
        const customerName = btn.getAttribute('data-name');
        
        document.getElementById('deleteCustomerName').textContent = customerName;
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteModal.show();
    }
});

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteCustomerId) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.customers.destroy', ':id') }}`.replace(':id', deleteCustomerId);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
});

// Clear forms when modals are closed
document.getElementById('addCustomerModal').addEventListener('hidden.bs.modal', function() {
    document.querySelector('#addCustomerModal form').reset();
});

document.getElementById('editCustomerModal').addEventListener('hidden.bs.modal', function() {
    document.querySelector('#editCustomerModal form').reset();
});
</script>
@endsection