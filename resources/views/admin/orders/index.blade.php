<!-- resources/views/admin/orders/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
<!-- Page Header -->
<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="dashboard-title mb-2">Kelola Pesanan</h1>
            <p class="dashboard-subtitle mb-0">Pantau dan kelola semua pesanan customer PT. Batam General Supplier</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <div class="d-flex gap-2 justify-content-lg-end">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-funnel me-2"></i>Filter Status
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.orders') }}">Semua Status</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders') }}?status=pending">Pending</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders') }}?status=confirmed">Confirmed</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders') }}?status=processing">Processing</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders') }}?status=shipped">Shipped</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders') }}?status=delivered">Delivered</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card modern-card">
    <div class="card-header">
        <form method="GET" action="{{ route('admin.orders') }}" class="row g-2 align-items-center w-100">
            <!-- Search Bar -->
            <div class="col-md-3">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari pesanan..." value="{{ request('search') }}">
            </div>

            <!-- Status Filter -->
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>

            <!-- Payment Method Filter -->
            <div class="col-md-3">
                <select name="payment_method" class="form-select">
                    <option value="" {{ request('payment_method') == '' ? 'selected' : '' }}>Semua Pembayaran</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="cash_on_delivery" {{ request('payment_method') == 'cash_on_delivery' ? 'selected' : '' }}>Cash on Delivery</option>
                </select>
            </div>

            <!-- Date Range Filter -->
            <div class="col-md-3">
                <select name="date_range" class="form-select">
                    <option value="" {{ request('date_range') == '' ? 'selected' : '' }}>Semua Waktu</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('admin.orders') }}" class="btn btn-light w-100">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
                <button class="btn btn-outline-secondary btn-sm" onclick="toggleView()">
                    <i class="bi bi-grid-3x3-gap" id="view-icon"></i>
                </button>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        @if($orders->count() > 0)
            <!-- Table View -->
            <div id="table-view">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>No. Pesanan</th>
                                <th>Customer</th>
                                <th width="150">Tanggal</th>
                                <th width="150">Item</th>
                                <th width="200">Total</th>
                                <th width="200">Pembayaran</th>
                                <th width="120">Status</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr class="order-row text-center">
                                <td>
                                    <div class="order-number">
                                        <strong>{{ $order->order_number }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-name">{{ $order->user->fullname }}</div>
                                        <small class="customer-email">{{ $order->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="order-date">
                                        <div class="date">{{ $order->order_date->format('d M Y') }}</div>
                                        <small class="time">{{ $order->order_date->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="item-badge">{{ $order->orderDetails->count() }} item</span>
                                </td>
                                <td>
                                    <div class="price-info">
                                        <span class="price">{{ $order->formatted_total_price }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="payment-badge">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.orders.show', $order->order_id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <!-- <button class="btn btn-sm btn-outline-success" 
                                                onclick="showStatusModal({{ $order->order_id }}, '{{ $order->status }}')" 
                                                title="Update Status">
                                            <i class="bi bi-pencil-square"></i>
                                        </button> -->
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
                <div class="orders-grid">
                    @foreach($orders as $order)
                    <div class="order-card">
                        <div class="order-card-header">
                            <div class="order-number">{{ $order->order_number }}</div>
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="order-card-body">
                            <div class="customer-info">
                                <h6 class="customer-name">{{ $order->user->fullname }}</h6>
                                <small class="customer-email">{{ $order->user->email }}</small>
                            </div>
                            <div class="order-meta">
                                <div class="order-date">
                                    <i class="bi bi-calendar3"></i>
                                    <span>{{ $order->order_date->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="order-items">
                                    <span class="item-badge">{{ $order->orderDetails->count() }} item</span>
                                </div>
                            </div>
                            <div class="payment-info">
                                <span class="payment-badge">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                            </div>
                            <div class="order-price">
                                {{ $order->formatted_total_price }}
                            </div>
                            <div class="card-actions">
                                <a href="{{ route('admin.orders.show', $order->order_id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <!-- <button class="btn btn-sm btn-outline-success" 
                                        onclick="showStatusModal({{ $order->order_id }}, '{{ $order->status }}')">
                                    <i class="bi bi-pencil-square"></i> Status
                                </button> -->
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if(method_exists($orders, 'hasPages') && $orders->hasPages())
                <div class="pagination-wrapper">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-bag-x"></i>
                </div>
                <h4 class="empty-title">Belum Ada Pesanan</h4>
                <p class="empty-description">Pesanan akan muncul di sini setelah customer melakukan pemesanan</p>
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<!-- <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Baru</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<style>
/* Order-specific styles extending the admin layout */

/* Order Number */
.order-number {
    font-weight: 700;
    color: var(--primary);
    font-size: 0.95rem;
    letter-spacing: 0.5px;
}

/* Customer Info */
.customer-info .customer-name {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.customer-info .customer-email {
    color: var(--gray-500);
    font-size: 0.875rem;
}

/* Order Date */
.order-date .date {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.875rem;
}

.order-date .time {
    color: var(--gray-500);
    font-size: 0.75rem;
}

/* Item Badge */
.item-badge {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

/* Price Info */
.price-info .price {
    font-weight: 700;
    color: var(--primary);
    font-size: 1.1rem;
}

/* Payment Badge */
.payment-badge {
    background: rgba(139, 92, 246, 0.1);
    color: var(--secondary);
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(139, 92, 246, 0.2);
}

/* Status Badges */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
}

.status-pending {
    background: rgba(251, 191, 36, 0.1);
    color: var(--warning);
    border: 1px solid rgba(251, 191, 36, 0.2);
}

.status-confirmed {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.status-processing {
    background: rgba(139, 92, 246, 0.1);
    color: var(--secondary);
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.status-shipped {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-delivered {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
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
.orders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

.order-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    border: 1px solid var(--gray-100);
}

.order-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.order-card-header {
    padding: 1.25rem 1.25rem 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.order-card-body {
    padding: 0 1.25rem 1.25rem;
}

.customer-info {
    margin-bottom: 1rem;
}

.customer-info h6 {
    margin: 0 0 0.25rem 0;
    font-weight: 600;
    color: var(--gray-700);
}

.order-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--gray-100);
}

.order-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-600);
    font-size: 0.875rem;
}

.order-date i {
    color: var(--gray-400);
}

.payment-info {
    margin-bottom: 1rem;
}

.order-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary);
    text-align: center;
    padding: 1rem 0;
    margin-bottom: 1rem;
    background: rgba(236, 72, 153, 0.05);
    border-radius: 12px;
}

.card-actions {
    display: flex;
    gap: 0.75rem;
}

.card-actions .btn {
    flex: 1;
    border-radius: 8px;
    font-weight: 500;
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
    background: rgba(236, 72, 153, 0.05);
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
    
    .orders-grid {
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
    
    .customer-info .customer-name {
        font-size: 0.875rem;
    }
    
    .customer-info .customer-email {
        font-size: 0.75rem;
    }

    .order-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .card-actions {
        flex-direction: column;
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

    .orders-grid {
        grid-template-columns: 1fr;
    }
}

/* Modal Enhancements */
.modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: var(--shadow-lg);
}

.modal-header {
    border-bottom: 1px solid var(--gray-100);
    padding: 1.5rem 1.5rem 1rem;
}

.modal-body {
    padding: 1rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid var(--gray-100);
    padding: 1rem 1.5rem 1.5rem;
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
        localStorage.setItem('orderView', 'table');
    } else {
        // Switch to grid view
        tableView.style.display = 'none';
        gridView.style.display = 'block';
        viewIcon.className = 'bi bi-table';
        localStorage.setItem('orderView', 'grid');
    }
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('orderView');
    if (savedView === 'grid') {
        toggleView();
    }
});

// // Status update modal function
// function showStatusModal(orderId, currentStatus) {
//     document.getElementById('statusForm').action = `/admin/orders/${orderId}/status`;
//     document.getElementById('status').value = currentStatus;
//     new bootstrap.Modal(document.getElementById('statusModal')).show();
// }
</script>
@endsection