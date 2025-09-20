<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Dashboard Header -->
<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="dashboard-title mb-2">Dashboard Admin</h1>
            <p class="dashboard-subtitle mb-0">Selamat datang kembali, <b>{{ auth()->user()->fullname }}</b>! Berikut ringkasan aktivitas PT. Batam General Supplier hari ini.</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <div class="dashboard-date">
                <i class="bi bi-calendar3"></i>
                <span>{{ date('d F Y') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-5">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card primary dashboard-card">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-number">{{ $totalProducts }}</div>
                        <div class="stat-label">Total Produk</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card success dashboard-card">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-number">{{ $totalOrders }}</div>
                        <div class="stat-label">Total Pesanan</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card info dashboard-card">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-number">{{ $totalCustomers }}</div>
                        <div class="stat-label">Total Customer</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card warning dashboard-card">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-number">{{ $pendingOrders }}</div>
                        <div class="stat-label">Pesanan Pending</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-8 mb-4">
        <div class="card modern-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</h5>
                        <p class="text-muted mb-0">Aktivitas pesanan dalam 7 hari terakhir</p>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover modern-table mb-0">
                            <thead>
                                <tr>
                                    <th>Pesanan</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <div class="order-info">
                                            <div class="order-number">{{ $order->order_number }}</div>
                                            <small class="text-muted">ID: {{ $order->order_id }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <div class="customer-avatar">
                                                {{ substr($order->user->fullname, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="customer-name">{{ $order->user->fullname }}</div>
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="price-info">
                                            <span class="price">{{ $order->formatted_total_price }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            <div>{{ $order->order_date->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $order->order_date->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->order_id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-bag"></i>
                        </div>
                        <h6>Belum ada pesanan</h6>
                        <p class="text-muted">Pesanan akan muncul di sini ketika customer melakukan pemesanan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Low Stock Alert & Quick Actions -->
    <div class="col-lg-4">
        <!-- Low Stock Alert -->
        <div class="card modern-card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Stok Menipis</h5>
                        <p class="text-muted mb-0">Produk yang perlu diperhatikan</p>
                    </div>
                    <a href="{{ route('admin.products') }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-gear"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($lowStockProducts->count() > 0)
                    <div class="stock-list">
                        @foreach($lowStockProducts as $product)
                        <div class="stock-item d-flex justify-content-between">
                            <div class="stock-info">
                                <div class="product-name">{{ $product->name }}</div>
                                <small class="text-muted">{{ $product->category->name }}</small>
                            </div>
                            <div class="stock-badge">
                                <span class="badge bg-warning">{{ $product->stock }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon text-success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h6>Semua stok aman</h6>
                        <p class="text-muted">Tidak ada produk dengan stok menipis</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="{{ route('admin.products.create') }}" class="quick-action-btn">
                        <div class="action-icon bg-primary">
                            <i class="bi bi-plus-circle"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Tambah Produk</div>
                            <div class="action-desc">Produk baru</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.orders') }}?status=pending" class="quick-action-btn">
                        <div class="action-icon bg-warning">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Pesanan Pending</div>
                            <div class="action-desc">{{ $pendingOrders }} menunggu</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.reports') }}" class="quick-action-btn">
                        <div class="action-icon bg-info">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Laporan</div>
                            <div class="action-desc">Analisis data</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('home') }}" target="_blank" class="quick-action-btn">
                        <div class="action-icon bg-success">
                            <i class="bi bi-globe"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Lihat Website</div>
                            <div class="action-desc">Frontend</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard specific styles */
.dashboard-header {
    margin-bottom: 2rem;
}

.dashboard-title {
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
}

.dashboard-subtitle {
    color: var(--gray-600);
    font-size: 1rem;
}

.dashboard-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-500);
    font-weight: 500;
    background: rgba(236, 72, 153, 0.1);
    padding: 0.75rem 1rem;
    border-radius: 12px;
}

/* Enhanced stat cards */
.stat-card .card-body {
    padding: 2rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-bottom: 0.75rem;
}

.stat-change {
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.stat-icon {
    font-size: 3rem;
    opacity: 0.3;
}

.stat-progress {
    margin-top: 1.5rem;
}

.stat-progress .progress {
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

.stat-progress .progress-bar {
    background: rgba(255, 255, 255, 0.8);
    border-radius: 2px;
}

/* Modern card styles */
.modern-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

/* Enhanced table styles */
.modern-table {
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table th {
    background: var(--gray-50);
    font-weight: 700;
    color: var(--gray-700);
    padding: 1.25rem 1.5rem;
    border: none;
}

.modern-table td {
    padding: 1.25rem 1.5rem;
    border: none;
    border-bottom: 1px solid var(--gray-100);
}

.order-info .order-number {
    font-weight: 600;
    color: var(--primary);
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.customer-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.875rem;
}

.customer-name {
    font-weight: 600;
    color: var(--gray-700);
}

.price-info .price {
    font-weight: 700;
    color: var(--primary);
    font-size: 1.1rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
}

.status-pending {
    background: rgba(251, 191, 36, 0.1);
    color: #d97706;
}

.status-processing {
    background: rgba(59, 130, 246, 0.1);
    color: #1d4ed8;
}

.status-shipped {
    background: rgba(16, 185, 129, 0.1);
    color: #047857;
}

.status-delivered {
    background: rgba(16, 185, 129, 0.1);
    color: #04a33cff;
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

/* Empty state styles */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 1rem;
}

.empty-state h6 {
    color: var(--gray-600);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--gray-500);
    font-size: 0.875rem;
}

/* Stock list styles */
.stock-list {
    padding: 0;
}

.stock-item {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--gray-100);
}

.stock-item:last-child {
    border-bottom: none;
}

.stock-info .product-name {
    font-weight: 600;
    color: var(--gray-700);
}

.stock-badge .badge {
    font-weight: 700;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
}

/* Quick actions styles */
.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 1px solid var(--gray-100);
}

.quick-action-btn:hover {
    transform: translateX(4px);
    box-shadow: var(--shadow-lg);
    color: inherit;
    border-color: var(--primary);
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.action-icon.bg-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
}

.action-icon.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.action-icon.bg-info {
    background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
}

.action-icon.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.action-title {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.25rem;
}

.action-desc {
    font-size: 0.875rem;
    color: var(--gray-500);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .dashboard-title {
        font-size: 1.5rem;
    }
    
    .dashboard-subtitle {
        font-size: 0.875rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .modern-table th,
    .modern-table td {
        padding: 1rem;
    }
    
    .customer-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>
@endsection