<!-- resources/views/customer/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard Customer')

@section('content')
<div class="container py-4">
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="dashboard-title mb-2">Dashboard Customer</h1>
                <p class="dashboard-subtitle mb-0">Selamat datang kembali, <b>{{ auth()->user()->fullname }}</b>! Kelola pesanan dan aktivitas belanja Anda dengan mudah.</p>
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
                            <div class="stat-number">{{ $totalOrders }}</div>
                            <div class="stat-label">Total Pesanan</div>
                            <div class="stat-change">
                                <i class="bi bi-arrow-up"></i>
                                <span>Semua waktu</span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-bag-check"></i>
                        </div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ min(($totalOrders / 10) * 100, 100) }}%"></div>
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
                            <div class="stat-number">{{ $completedOrders }}</div>
                            <div class="stat-label">Pesanan Selesai</div>
                            <div class="stat-change">
                                <i class="bi bi-check-circle"></i>
                                <span>Berhasil</span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0 }}%"></div>
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
                            <div class="stat-number" id="cart-total-dashboard">0</div>
                            <div class="stat-label">Item di Keranjang</div>
                            <div class="stat-change">
                                <i class="bi bi-cart3"></i>
                                <span>Siap checkout</span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-cart3"></i>
                        </div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar" style="width: 75%"></div>
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
                            <div class="stat-number">{{ $totalOrders - $completedOrders }}</div>
                            <div class="stat-label">Pesanan Aktif</div>
                            <div class="stat-change">
                                <i class="bi bi-clock"></i>
                                <span>Dalam proses</span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $totalOrders > 0 ? (($totalOrders - $completedOrders) / $totalOrders) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card modern-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ route('catalog') }}" class="quick-action-btn">
                            <div class="action-icon bg-primary">
                                <i class="bi bi-grid"></i>
                            </div>
                            <div class="action-content">
                                <div class="action-title">Lihat Katalog</div>
                                <div class="action-desc">Jelajahi produk</div>
                            </div>
                        </a>
                        
                        <a href="{{ route('customer.cart') }}" class="quick-action-btn">
                            <div class="action-icon bg-success">
                                <i class="bi bi-cart3"></i>
                            </div>
                            <div class="action-content">
                                <div class="action-title">Keranjang Saya</div>
                                <div class="action-desc"><span id="cart-count-action">0</span> item</div>
                            </div>
                        </a>
                        
                        <a href="{{ route('customer.orders') }}" class="quick-action-btn">
                            <div class="action-icon bg-info">
                                <i class="bi bi-bag-check"></i>
                            </div>
                            <div class="action-content">
                                <div class="action-title">Riwayat Pesanan</div>
                                <div class="action-desc">{{ $totalOrders }} pesanan</div>
                            </div>
                        </a>
                        
                        <a href="{{ route('customer.profile') }}" class="quick-action-btn">
                            <div class="action-icon bg-warning">
                                <i class="bi bi-person-gear"></i>
                            </div>
                            <div class="action-content">
                                <div class="action-title">Edit Profile</div>
                                <div class="action-desc">Pengaturan akun</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-8 mb-4">
            <div class="card modern-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</h5>
                            <p class="text-muted mb-0">Aktivitas pesanan Anda dalam 30 hari terakhir</p>
                        </div>
                        <a href="{{ route('customer.orders') }}" class="btn btn-outline-primary btn-sm">
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
                                            <a href="{{ route('customer.order.show', $order->order_id) }}" 
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
                            <h6>Belum Ada Pesanan</h6>
                            <p class="text-muted">Mulai berbelanja sekarang dan buat pesanan pertama Anda!</p>
                            <a href="{{ route('catalog') }}" class="btn btn-primary">
                                <i class="bi bi-grid me-2"></i>Lihat Katalog Produk
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products or Shopping Suggestions -->
    <div class="row">
        <div class="col-12">
            <div class="card modern-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><i class="bi bi-star me-2"></i>Rekomendasi Untuk Anda</h5>
                            <p class="text-muted mb-0">Produk pilihan yang mungkin Anda sukai</p>
                        </div>
                        <a href="{{ route('catalog') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-grid me-1"></i>Lihat Katalog
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="recommendation-card">
                                <div class="rec-icon">
                                    <i class="bi bi-basket"></i>
                                </div>
                                <div class="rec-content">
                                    <h6>Peralatan Kantor</h6>
                                    <p class="text-muted mb-2">Lengkapi kebutuhan kantor Anda dengan produk berkualitas</p>
                                    <a href="{{ route('catalog') }}?category=office" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="recommendation-card">
                                <div class="rec-icon">
                                    <i class="bi bi-tools"></i>
                                </div>
                                <div class="rec-content">
                                    <h6>Peralatan Industri</h6>
                                    <p class="text-muted mb-2">Solusi terpercaya untuk kebutuhan industri</p>
                                    <a href="{{ route('catalog') }}?category=industrial" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="recommendation-card">
                                <div class="rec-icon">
                                    <i class="bi bi-house"></i>
                                </div>
                                <div class="rec-content">
                                    <h6>Kebutuhan Rumah Tangga</h6>
                                    <p class="text-muted mb-2">Produk rumah tangga untuk kenyamanan keluarga</p>
                                    <a href="{{ route('catalog') }}?category=household" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard specific styles matching admin layout */
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
    opacity: 0.8;
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

.stat-card.primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
}

.stat-card.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.stat-card.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.stat-card.info {
    background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
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
    color: #047857;
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

.action-icon.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.action-icon.bg-info {
    background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
}

.action-icon.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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

/* Recommendation cards */
.recommendation-card {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    border: 1px solid var(--gray-100);
    border-radius: 16px;
    transition: all 0.3s ease;
    height: 100%;
}

.recommendation-card:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.rec-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.rec-content h6 {
    color: var(--gray-700);
    font-weight: 700;
    margin-bottom: 0.5rem;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dashboard-card {
    animation: fadeInUp 0.6s ease-out;
}

.dashboard-card:nth-child(2) {
    animation-delay: 0.1s;
}

.dashboard-card:nth-child(3) {
    animation-delay: 0.2s;
}

.dashboard-card:nth-child(4) {
    animation-delay: 0.3s;
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
    
    .recommendation-card {
        flex-direction: column;
        text-align: center;
    }
    
    .rec-icon {
        margin: 0 auto;
    }
}
</style>

<script>
$(document).ready(function() {
    // Update cart totals in dashboard
    $.get('/api/cart-count', function(data) {
        $('#cart-total-dashboard').text(data.count);
        $('#cart-count-action').text(data.count);
    });
});
</script>
@endsection