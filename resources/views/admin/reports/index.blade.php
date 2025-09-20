<!-- resources/views/admin/reports/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Laporan & Analisis')

@section('content')
<!-- Page Header -->
<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="dashboard-title mb-2">Laporan & Analisis</h1>
            <p class="dashboard-subtitle mb-0">Dashboard analisis penjualan dan laporan PT. Batam General Supplier</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <div class="btn-group" role="group">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="bi bi-download me-2"></i>Export Laporan
                </button>
                <button class="btn btn-primary" onclick="refreshData()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card primary">
            <div class="stats-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stats-content">
                <h3 class="stats-number">{{ number_format($totalSales, 0, ',', '.') }}</h3>
                <p class="stats-label">Total Penjualan</p>
                <span class="stats-change positive">
                    <i class="bi bi-arrow-up"></i> +12.5%
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card success">
            <div class="stats-icon">
                <i class="bi bi-bag-check"></i>
            </div>
            <div class="stats-content">
                <h3 class="stats-number">{{ $completedOrders ?? 0 }}</h3>
                <p class="stats-label">Order Selesai</p>
                <span class="stats-change positive">
                    <i class="bi bi-arrow-up"></i> +8.3%
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card warning">
            <div class="stats-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="stats-content">
                <h3 class="stats-number">{{ $totalCustomers ?? 0 }}</h3>
                <p class="stats-label">Total Pelanggan</p>
                <span class="stats-change positive">
                    <i class="bi bi-arrow-up"></i> +15.2%
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card info">
            <div class="stats-icon">
                <i class="bi bi-box"></i>
            </div>
            <div class="stats-content">
                <h3 class="stats-number">{{ $totalProducts ?? 0 }}</h3>
                <p class="stats-label">Total Produk</p>
                <span class="stats-change neutral">
                    <i class="bi bi-dash"></i> 0%
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Sales Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card modern-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Grafik Penjualan</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="chartPeriod" id="monthly" checked>
                        <label class="btn btn-outline-primary" for="monthly">Bulanan</label>
                        
                        <input type="radio" class="btn-check" name="chartPeriod" id="weekly">
                        <label class="btn btn-outline-primary" for="weekly">Mingguan</label>
                        
                        <input type="radio" class="btn-check" name="chartPeriod" id="daily">
                        <label class="btn btn-outline-primary" for="daily">Harian</label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Order Status Distribution -->
    <div class="col-lg-4 mb-4">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">Status Order</h5>
            </div>
            <div class="card-body">
                <canvas id="orderStatusChart"></canvas>
                <div class="chart-legend mt-3">
                    <div class="legend-item">
                        <div class="legend-color" style="background: #10b981;"></div>
                        <span>Selesai (65%)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #f59e0b;"></div>
                        <span>Proses (20%)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #3b82f6;"></div>
                        <span>Pending (10%)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #ef4444;"></div>
                        <span>Dibatal (5%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Section -->
<div class="row">
    <!-- Top Products -->
    <div class="col-lg-6 mb-4">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">Produk Terlaris</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Terjual</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts ?? [] as $index => $product)
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <div class="product-rank">{{ $index + 1 }}</div>
                                        <div>
                                            <div class="product-name">{{ $product->name }}</div>
                                            <small class="text-muted">{{ $product->category->name ?? 'No Category' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="quantity-badge">{{ $product->order_details_sum_quantity ?? 0 }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="revenue-info">
                                        <span class="revenue-amount">Rp {{ number_format(($product->price ?? 0) * ($product->order_details_sum_quantity ?? 0), 0, ',', '.') }}</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="bi bi-box me-2"></i>Belum ada data produk
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Sales -->
    <div class="col-lg-6 mb-4">
        <div class="card modern-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Penjualan Terkini</h5>
                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                            <tr>
                                <td>
                                    <div class="order-info">
                                        <div class="order-number">#{{ $order->order_number ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $order->user->fullname ?? $order->user->firstname . ' ' . $order->user->lastname ?? 'Unknown' }}</small>
                                        <div class="order-date">{{ $order->order_date ? $order->order_date->format('d M, H:i') : 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge status-{{ $order->status ?? 'pending' }}">
                                        {{ ucfirst($order->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="order-total">
                                        Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="bi bi-bag me-2"></i>Belum ada order terkini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="GET" action="{{ route('admin.reports.export') }}">
                <div class="modal-header">
                    <h5 class="modal-title">Export Laporan Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ date('Y-m-01') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ date('Y-m-t') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="format" class="form-label">Format Export</label>
                        <select class="form-select" id="format" name="format">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Filter Status</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="delivered" id="delivered" name="status[]" checked>
                            <label class="form-check-label" for="delivered">Order Selesai</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="processing" id="processing" name="status[]">
                            <label class="form-check-label" for="processing">Dalam Proses</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="cancelled" id="cancelled" name="status[]">
                            <label class="form-check-label" for="cancelled">Dibatalkan</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-download me-2"></i>Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Reports-specific styles extending the admin layout */

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-100);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary);
}

.stats-card.primary::before { background: var(--primary); }
.stats-card.success::before { background: var(--success); }
.stats-card.warning::before { background: var(--warning); }
.stats-card.info::before { background: var(--info); }

.stats-card {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stats-card.primary .stats-icon {
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary);
}

.stats-card.success .stats-icon {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.stats-card.warning .stats-icon {
    background: rgba(251, 191, 36, 0.1);
    color: var(--warning);
}

.stats-card.info .stats-icon {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
}

.stats-content {
    flex: 1;
}

.stats-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0;
    line-height: 1.2;
}

.stats-label {
    color: var(--gray-600);
    font-size: 0.875rem;
    margin: 0.25rem 0 0.5rem 0;
    font-weight: 500;
}

.stats-change {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.stats-change.positive {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.stats-change.negative {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
}

.stats-change.neutral {
    background: rgba(107, 114, 128, 0.1);
    color: var(--gray-600);
}

/* Chart Styles */
#salesChart, #orderStatusChart {
    max-height: 300px;
}

.chart-legend {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

/* Table Styles */
.product-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.product-rank {
    width: 24px;
    height: 24px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    flex-shrink: 0;
}

.product-name {
    font-weight: 600;
    color: var(--gray-700);
    line-height: 1.3;
}

.quantity-badge {
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary);
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
}

.revenue-amount {
    font-weight: 600;
    color: var(--gray-700);
}

/* Order Info */
.order-info .order-number {
    font-weight: 600;
    color: var(--gray-700);
    line-height: 1.3;
}

.order-info .order-date {
    font-size: 0.75rem;
    color: var(--gray-500);
    margin-top: 0.25rem;
}

.order-total {
    font-weight: 600;
    color: var(--gray-700);
}

/* Status Badges */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: capitalize;
}

.status-delivered, .status-shipped {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.status-processing, .status-confirmed {
    background: rgba(251, 191, 36, 0.1);
    color: var(--warning);
}

.status-pending {
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary);
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
}

/* Card Titles */
.card-title {
    font-weight: 600;
    color: var(--gray-700);
}

/* Button Groups */
.btn-group .btn-check:checked + .btn {
    background-color: var(--primary);
    border-color: var(--primary);
    color: white;
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

.form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-header .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .dashboard-header .btn-group .btn {
        border-radius: 8px !important;
        margin-bottom: 0.5rem;
    }
    
    .stats-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .stats-number {
        font-size: 1.5rem;
    }
    
    .chart-legend {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .product-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}

@media (max-width: 576px) {
    .dashboard-header .col-lg-4 {
        margin-top: 1rem;
        text-align: left !important;
    }
    
    .btn-group[role="group"] {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group[role="group"] .btn {
        border-radius: 8px !important;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// Chart Data
const monthlyData = @json($monthlyDataComplete ?? []);
const weeklyData = @json($weeklyDataComplete ?? []);
const dailyData = @json($dailyDataComplete ?? []);
const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

// Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
let salesChart;

// Function to initialize the chart
function initSalesChart() {
    salesChart = new Chart(salesCtx, {
        type: 'line',
        data: getChartData('monthly'),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [5, 5],
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value/1000000).toFixed(1) + 'Jt';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value/1000).toFixed(0) + 'Rb';
                            }
                            return 'Rp ' + value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: 'rgb(59, 130, 246)'
                }
            }
        }
    });
}

// Function to get chart data based on period
function getChartData(period) {
    let labels = [];
    let data = [];
    
    switch(period) {
        case 'daily':
            // Get current date to determine which days to show
            const today = new Date();
            const daysInMonth = @json($daysInMonth ?? 30);
            const daysToShow = Math.min(today.getDate(), daysInMonth);
            
            labels = Array.from({length: daysToShow}, (_, i) => (i + 1) + ' ' + monthNames[today.getMonth()]);
            data = dailyData.slice(0, daysToShow).map(item => item.total || 0);
            break;
            
        case 'weekly':
            const currentWeek = @json($currentWeek ?? 1);
            labels = Array.from({length: currentWeek}, (_, i) => 'Minggu ' + (i + 1));
            data = weeklyData.slice(0, currentWeek).map(item => item.total || 0);
            break;
            
        default: // monthly
            labels = monthNames;
            data = monthlyData.map(item => item.total || 0);
    }
    
    return {
        labels: labels,
        datasets: [{
            label: 'Penjualan (Rp)',
            data: data,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: 'rgb(59, 130, 246)',
            pointBorderColor: 'white',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    };
}

// Function to update chart based on selected period
function updateSalesChart(period) {
    salesChart.data = getChartData(period);
    salesChart.update();
}

// Initialize the chart
initSalesChart();

// Chart Period Toggle
document.addEventListener('change', function(e) {
    if (e.target.name === 'chartPeriod') {
        const period = e.target.id;
        updateSalesChart(period);
    }
});

// Order Status Chart with Real Data
const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
const orderStatusData = @json($orderStatusData ?? []);
const statusColors = {
    'delivered': '#10b981',
    'processing': '#f59e0b', 
    'pending': '#3b82f6',
    'cancelled': '#ef4444',
    'shipped': '#8b5cf6'
};

// Prepare data for order status chart
const statusLabels = Object.keys(orderStatusData).map(status => {
    return status.charAt(0).toUpperCase() + status.slice(1);
});

const statusValues = Object.values(orderStatusData);
const statusBackgrounds = Object.keys(orderStatusData).map(status => statusColors[status] || '#6b7280');

// Calculate percentages for legend
const totalOrders = statusValues.reduce((sum, value) => sum + value, 0);
const statusPercentages = statusValues.map(value => {
    return totalOrders > 0 ? Math.round((value / totalOrders) * 100) : 0;
});

// Update the legend with real data
function updateOrderStatusLegend() {
    const legendContainer = document.querySelector('.chart-legend');
    legendContainer.innerHTML = '';
    
    Object.keys(orderStatusData).forEach((status, index) => {
        const percentage = statusPercentages[index];
        const color = statusColors[status] || '#6b7280';
        
        const legendItem = document.createElement('div');
        legendItem.className = 'legend-item';
        legendItem.innerHTML = `
            <div class="legend-color" style="background: ${color};"></div>
            <span>${status.charAt(0).toUpperCase() + status.slice(1)} (${percentage}%)</span>
        `;
        
        legendContainer.appendChild(legendItem);
    });
}

// Initialize order status chart
const orderStatusChart = new Chart(orderStatusCtx, {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusValues,
            backgroundColor: statusBackgrounds,
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const percentage = statusPercentages[context.dataIndex];
                        return `${context.label}: ${context.raw} orders (${percentage}%)`;
                    }
                }
            }
        },
        cutout: '60%'
    }
});

// Update the legend with real data
updateOrderStatusLegend();

// Refresh Data Function with AJAX
function refreshData() {
    const refreshBtn = document.querySelector('button[onclick="refreshData()"]');
    const originalText = refreshBtn.innerHTML;
    
    refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2 spinning"></i>Refreshing...';
    refreshBtn.disabled = true;
    
    // Make AJAX request to refresh data
    fetch('{{ route("admin.reports.refresh") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update charts with new data
            updateSalesChart('monthly');
            
            // Show success message
            showAlert('Data berhasil diperbarui', 'success');
        } else {
            showAlert('Gagal memperbarui data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat memperbarui data', 'error');
    })
    .finally(() => {
        refreshBtn.innerHTML = originalText;
        refreshBtn.disabled = false;
    });
}

// Helper function to show alerts
function showAlert(message, type = 'success') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alert.style = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    
    alert.innerHTML = `
        <i class="bi bi-${icon} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.parentNode.removeChild(alert);
        }
    }, 3000);
}

// Add spinning animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .spinning {
        animation: spin 1s linear infinite;
    }
`;
document.head.appendChild(style);
</script>
@endsection