<!-- resources/views/admin/products/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
<!-- Page Header -->
<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="dashboard-title mb-2">Kelola Produk</h1>
            <p class="dashboard-subtitle mb-0">Manajemen produk dalam katalog PT. Batam General Supplier</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus me-2"></i>Tambah Produk
            </a>
        </div>
    </div>
</div>

<div class="card modern-card">
    <div class="card-header">
        <form method="GET" action="{{ route('admin.products') }}" class="row g-2 align-items-center w-100">

            <!-- Search Bar -->
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari produk..." value="{{ request('search') }}">
            </div>

            <!-- Status Filter -->
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Produk</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Produk Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Produk Non-aktif</option>
                </select>
            </div>

            <!-- Stock Filter -->
            <div class="col-md-4">
                <select name="stock" class="form-select">
                    <option value="" {{ request('stock') == '' ? 'selected' : '' }}>Semua Stok</option>
                    <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('admin.products') }}" class="btn btn-light w-100">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
                <button class="btn btn-outline-secondary btn-sm" onclick="toggleView()">
                    <i class="bi bi-grid-3x3-gap" id="view-icon"></i>
                </button>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        @if($products->count() > 0)
            <!-- Table View -->
            <div id="table-view">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr class="text-center">
                                <th width="80">Gambar</th>
                                <th>Produk</th>
                                <th width="210">Kategori</th>
                                <th width="210">Harga</th>
                                <th width="80">Stok</th>
                                <th width="100">Status</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr class="product-row text-center">
                                <td>
                                    <div class="product-image-container">
                                        <img src="{{ $product->image ? asset('storage/products/'.$product->image) : asset('images/no-image.png') }}" 
                                             class="product-image" 
                                             alt="{{ $product->name }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="product-info">
                                        <div class="product-name">{{ $product->name }}</div>
                                        <small class="product-description">{{ Str::limit($product->description, 45) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge">{{ $product->category->name }}</span>
                                </td>
                                <td>
                                    <div class="price-info">
                                        <span class="price">{{ $product->formatted_price }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="stock-badge {{ $product->stock <= 5 ? 'low-stock' : 'normal-stock' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $product->is_active ? 'active' : 'inactive' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Non-aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.products.edit', $product->product_id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('admin.products.destroy', $product->product_id) }}" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger btn-delete"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
                <div class="products-grid">
                    @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-card-image">
                            <img src="{{ $product->image ? asset('storage/products/'.$product->image) : asset('images/no-image.png') }}" 
                                 alt="{{ $product->name }}">
                            <div class="product-card-overlay">
                                <div class="overlay-actions">
                                    <a href="{{ route('admin.products.edit', $product->product_id) }}" 
                                       class="btn btn-sm btn-light">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" 
                                          action="{{ route('admin.products.destroy', $product->product_id) }}" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger btn-delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="product-card-body">
                            <div class="product-card-header">
                                <h6 class="product-card-title">{{ $product->name }}</h6>
                                <span class="status-badge status-{{ $product->is_active ? 'active' : 'inactive' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </div>
                            <p class="product-card-description">{{ Str::limit($product->description, 80) }}</p>
                            <div class="product-card-meta">
                                <span class="category-badge">{{ $product->category->name }}</span>
                                <span class="stock-badge {{ $product->stock <= 5 ? 'low-stock' : 'normal-stock' }}">
                                    Stok: {{ $product->stock }}
                                </span>
                            </div>
                            <div class="product-card-price">
                                {{ $product->formatted_price }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if(method_exists($products, 'hasPages') && $products->hasPages())
                <div class="pagination-wrapper">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-box"></i>
                </div>
                <h4 class="empty-title">Belum Ada Produk</h4>
                <p class="empty-description">Mulai tambahkan produk ke dalam katalog untuk menampilkannya di sini</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus me-2"></i>Tambah Produk Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
      </div>
    </div>
  </div>
</div>

<style>
/* Product-specific styles extending the admin layout */

/* Product Image */
.product-image-container {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    overflow: hidden;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-row:hover .product-image {
    transform: scale(1.05);
}

/* Product Info */
.product-info .product-name {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.product-info .product-description {
    color: var(--gray-500);
    font-size: 0.875rem;
    line-height: 1.4;
}

/* Category Badge */
.category-badge {
    background: rgba(139, 92, 246, 0.1);
    color: var(--secondary);
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(139, 92, 246, 0.2);
}

/* Price Info */
.price-info .price {
    font-weight: 700;
    color: var(--primary);
    font-size: 1.1rem;
}

/* Stock Badge */
.stock-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    text-align: center;
    min-width: 50px;
    display: inline-block;
}

.stock-badge.normal-stock {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.stock-badge.low-stock {
    background: rgba(251, 191, 36, 0.1);
    color: var(--warning);
    border: 1px solid rgba(251, 191, 36, 0.2);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
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
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

.product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    border: 1px solid var(--gray-100);
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.product-card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: var(--gray-50);
}

.product-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-card-image img {
    transform: scale(1.05);
}

.product-card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.product-card:hover .product-card-overlay {
    opacity: 1;
}

.overlay-actions {
    display: flex;
    gap: 0.5rem;
}

.product-card-body {
    padding: 1.25rem;
}

.product-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.product-card-title {
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    line-height: 1.3;
}

.product-card-description {
    color: var(--gray-500);
    font-size: 0.875rem;
    line-height: 1.4;
    margin-bottom: 1rem;
}

.product-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.product-card-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary);
    text-align: center;
    padding-top: 0.75rem;
    border-top: 1px solid var(--gray-100);
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
    
    .products-grid {
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
    
    .product-info .product-name {
        font-size: 0.875rem;
    }
    
    .product-info .product-description {
        font-size: 0.75rem;
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
        localStorage.setItem('productView', 'table');
    } else {
        // Switch to grid view
        tableView.style.display = 'none';
        gridView.style.display = 'block';
        viewIcon.className = 'bi bi-table';
        localStorage.setItem('productView', 'grid');
    }
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('productView');
    if (savedView === 'grid') {
        toggleView();
    }
});

let deleteForm;

document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-delete')) {
        e.preventDefault();
        deleteForm = e.target.closest('form');
        
        let deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteModal.show();
    }
});

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteForm) {
        deleteForm.submit();
    }
});


</script>
@endsection