<!-- resources/views/admin/categories/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
<!-- Page Header -->
<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="dashboard-title mb-2">Kelola Kategori</h1>
            <p class="dashboard-subtitle mb-0">Manajemen kategori produk dalam katalog PT. Batam General Supplier</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus me-2"></i>Tambah Kategori
            </button>
        </div>
    </div>
</div>

<div class="card modern-card">
    <div class="card-header">
        <form method="GET" action="{{ route('admin.categories') }}" class="row g-2 align-items-center w-100">

            <!-- Search Bar -->
            <div class="col-md-6">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari kategori..." value="{{ request('search') }}">
            </div>

            <!-- Sort Filter -->
            <div class="col-md-6">
                <select name="sort" class="form-select">
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                    <option value="products_desc" {{ request('sort') == 'products_desc' ? 'selected' : '' }}>Terbanyak Produk</option>
                    <option value="products_asc" {{ request('sort') == 'products_asc' ? 'selected' : '' }}>Tersedikit Produk</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('admin.categories') }}" class="btn btn-light w-100">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
                <button class="btn btn-outline-secondary btn-sm" onclick="toggleView()">
                    <i class="bi bi-grid-3x3-gap" id="view-icon"></i>
                </button>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        @if($categories->count() > 0)
            <!-- Table View -->
            <div id="table-view">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr class="text-center align-middle">
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th width="200">Jumlah Produk</th>
                                <th width="180">Dibuat</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr class="category-row text-center align-middle">
                                <td>
                                    <div class="category-info">
                                        <div class="category-name">{{ $category->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="category-description">
                                        {{ $category->description ? Str::limit($category->description, 60) : '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="product-count-badge">
                                        <i class="bi bi-box me-1"></i>
                                        {{ $category->products_count }} Produk
                                    </span>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <span class="date">{{ $category->created_at->format('d M Y') }}</span>
                                        <small class="time">{{ $category->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary btn-edit" 
                                                data-id="{{ $category->category_id }}"
                                                data-name="{{ $category->name }}"
                                                data-description="{{ $category->description }}"
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @if($category->products_count == 0)
                                        <form method="POST" 
                                              action="{{ route('admin.categories.destroy', $category->category_id) }}" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger btn-delete"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @else
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                title="Tidak dapat dihapus (ada produk)" disabled>
                                            <i class="bi bi-lock"></i>
                                        </button>
                                        @endif
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
                <div class="categories-grid">
                    @foreach($categories as $category)
                    <div class="category-card">
                        <div class="category-card-header">
                            <div class="category-icon">
                                <i class="bi bi-folder"></i>
                            </div>
                            <div class="category-actions">
                                <button class="btn btn-sm btn-light btn-edit" 
                                        data-id="{{ $category->category_id }}"
                                        data-name="{{ $category->name }}"
                                        data-description="{{ $category->description }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($category->products_count == 0)
                                <form method="POST" 
                                      action="{{ route('admin.categories.destroy', $category->category_id) }}" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-sm btn-secondary" disabled>
                                    <i class="bi bi-lock"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="category-card-body">
                            <h5 class="category-card-title">{{ $category->name }}</h5>
                            <p class="category-card-description">
                                {{ $category->description ? Str::limit($category->description, 100) : 'Tidak ada deskripsi' }}
                            </p>
                            <div class="category-card-meta">
                                <div class="product-count">
                                    <i class="bi bi-box me-1"></i>
                                    {{ $category->products_count }} Produk
                                </div>
                                <div class="created-date">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $category->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if(method_exists($categories, 'hasPages') && $categories->hasPages())
                <div class="pagination-wrapper">
                    {{ $categories->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-folder"></i>
                </div>
                <h4 class="empty-title">Belum Ada Kategori</h4>
                <p class="empty-description">Mulai dengan menambahkan kategori untuk mengorganisir produk Anda</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus me-2"></i>Tambah Kategori Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="add_description" name="description" rows="3" placeholder="Deskripsi kategori (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="editCategoryForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" placeholder="Deskripsi kategori (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Kategori</button>
                </div>
            </form>
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
                Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Category-specific styles extending the admin layout */

/* Category Info */
.category-info .category-name {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.category-description {
    color: var(--gray-500);
    font-size: 0.875rem;
    line-height: 1.4;
}

/* Product Count Badge */
.product-count-badge {
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary);
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(59, 130, 246, 0.2);
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

.action-buttons .btn:hover:not(:disabled) {
    transform: translateY(-1px);
}

/* Grid View Styles */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

.category-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    border: 1px solid var(--gray-100);
}

.category-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.category-card-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    padding: 1.5rem;
    position: relative;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.category-icon {
    font-size: 2rem;
    opacity: 0.9;
}

.category-actions {
    display: flex;
    gap: 0.5rem;
}

.category-card-body {
    padding: 1.5rem;
}

.category-card-title {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.75rem;
    font-size: 1.125rem;
}

.category-card-description {
    color: var(--gray-500);
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 1.25rem;
    min-height: 2.5rem;
}

.category-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--gray-100);
    font-size: 0.75rem;
}

.category-card-meta .product-count {
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-weight: 600;
}

.category-card-meta .created-date {
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
    background: rgba(139, 92, 246, 0.05);
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
    
    .categories-grid {
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
    
    .category-card-meta {
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
    
    .category-card-header {
        padding: 1rem;
    }
    
    .category-card-body {
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
        localStorage.setItem('categoryView', 'table');
    } else {
        // Switch to grid view
        tableView.style.display = 'none';
        gridView.style.display = 'block';
        viewIcon.className = 'bi bi-table';
        localStorage.setItem('categoryView', 'grid');
    }
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('categoryView');
    if (savedView === 'grid') {
        toggleView();
    }
});

// Handle Edit Category
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-edit')) {
        const btn = e.target.closest('.btn-edit');
        const categoryId = btn.getAttribute('data-id');
        const categoryName = btn.getAttribute('data-name');
        const categoryDescription = btn.getAttribute('data-description');

        // Generate URL by replacing :id
        const updateUrl = `{{ route('admin.categories.update', ':id') }}`.replace(':id', categoryId);
        
        // Set form action
        document.getElementById('editCategoryForm').action = updateUrl;
        
        // Fill form fields
        document.getElementById('edit_name').value = categoryName;
        document.getElementById('edit_description').value = categoryDescription || '';
        
        // Show modal
        const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        editModal.show();
    }
});

// Handle Delete Category
let deleteForm;

document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-delete')) {
        e.preventDefault();
        deleteForm = e.target.closest('form');
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteModal.show();
    }
});

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteForm) {
        deleteForm.submit();
    }
});

// Clear form when add modal is closed
document.getElementById('addCategoryModal').addEventListener('hidden.bs.modal', function() {
    document.querySelector('#addCategoryModal form').reset();
});

// Clear form when edit modal is closed
document.getElementById('editCategoryModal').addEventListener('hidden.bs.modal', function() {
    document.querySelector('#editCategoryModal form').reset();
});
</script>
@endsection