<!-- resources/views/admin/products/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<!-- Page Header -->
<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="dashboard-title mb-2">Tambah Produk Baru</h1>
            <p class="dashboard-subtitle mb-0">Tambahkan produk baru ke dalam katalog PT. Batam General Supplier</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Informasi Produk
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">
                            <i class="bi bi-tag me-2"></i>Nama Produk *
                        </label>
                        <input type="text" class="form-control modern-input @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Masukkan nama produk..." required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="category_id" class="form-label fw-semibold">
                                <i class="bi bi-folder me-2"></i>Kategori *
                            </label>
                            <select class="form-select modern-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="price" class="form-label fw-semibold">
                                <i class="bi bi-currency-dollar me-2"></i>Harga *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text modern-input-group-text">Rp</span>
                                <input type="number" class="form-control modern-input @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" 
                                       min="0" step="1000" placeholder="0" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="stock" class="form-label fw-semibold">
                            <i class="bi bi-box me-2"></i>Stok *
                        </label>
                        <input type="number" class="form-control modern-input @error('stock') is-invalid @enderror" 
                               id="stock" name="stock" value="{{ old('stock', 0) }}" 
                               min="0" placeholder="Jumlah stok..." required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-file-text me-2"></i>Deskripsi *
                        </label>
                        <textarea class="form-control modern-textarea @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5" 
                                  placeholder="Masukkan deskripsi lengkap produk..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-semibold">
                            <i class="bi bi-image me-2"></i>Gambar Produk
                        </label>
                        <div class="image-upload-container">
                            <input type="file" class="form-control modern-file-input @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*" onchange="previewImage(this)">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Format yang didukung: JPG, PNG, GIF. Maksimal ukuran 2MB.
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4 gap-3 d-flex justify-content-center align-items-center">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" 
                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">
                            Produk Aktif
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Simpan Produk
                        </button>
                        <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Image Preview Card -->
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-eye me-2"></i>Preview Gambar
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="image-preview-container">
                    <img id="image-preview" 
                        src="{{ asset('images/no-image.png') }}" 
                        data-fallback="{{ asset('images/no-image.png') }}" 
                        style="display:block;"
                        class="preview-image">
                </div>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>Tips & Panduan
                </h5>
            </div>
            <div class="card-body">
                <div class="tips-list">
                    <div class="tip-item">
                        <div class="tip-icon">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                        <div class="tip-content">
                            <strong>Nama Produk:</strong> Gunakan nama yang jelas dan deskriptif
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                        <div class="tip-content">
                            <strong>Kategori:</strong> Pilih kategori yang tepat untuk memudahkan pencarian
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                        <div class="tip-content">
                            <strong>Deskripsi:</strong> Sertakan spesifikasi lengkap dan detail penting
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                        <div class="tip-content">
                            <strong>Gambar:</strong> Upload foto berkualitas tinggi dan menarik
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                        <div class="tip-content">
                            <strong>Stok:</strong> Pastikan jumlah sesuai ketersediaan real
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Form Styles matching admin layout */

/* Form Controls */
.modern-input,
.modern-select,
.modern-textarea {
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    padding: 0.875rem 1.125rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.modern-input:focus,
.modern-select:focus,
.modern-textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.1);
    background: white;
}

.modern-input-group-text {
    background: var(--gray-50);
    border: 2px solid var(--gray-200);
    border-right: none;
    border-radius: 12px 0 0 12px;
    color: var(--gray-600);
    font-weight: 600;
}

.modern-file-input {
    border: 2px dashed var(--gray-300);
    border-radius: 12px;
    padding: 1.5rem;
    background: var(--gray-50);
    transition: all 0.3s ease;
    cursor: pointer;
}

.modern-file-input:hover {
    border-color: var(--primary);
    background: rgba(236, 72, 153, 0.05);
}

/* Labels */
.form-label {
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-label i {
    color: var(--primary);
    font-size: 1rem;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--gray-100);
    margin-top: 2rem;
}

.form-actions .btn {
    border-radius: 12px;
    font-weight: 600;
    padding: 0.875rem 2rem;
    transition: all 0.3s ease;
}

.form-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

/* Form Switch */
.form-check-input[type=checkbox] {
    width: 3rem;
    height: 1.5rem;
    background-color: var(--gray-200);
    border: none;
    border-radius: 1rem;
}

.form-check-input[type=checkbox]:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}

.form-check-input[type=checkbox]:focus {
    box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.25);
}

/* Image Preview */
.image-preview-container {
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    overflow: hidden;
    background: var(--gray-50);
}

.preview-image {
    max-width: 100%;
    max-height: 200px;
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: transform 0.3s ease;
}

.preview-image:hover {
    transform: scale(1.05);
}

.placeholder-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.6;
}

.placeholder-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--gray-500);
}

.placeholder-text {
    font-size: 0.875rem;
    margin: 0;
    color: var(--gray-400);
}

/* Tips Section */
.tips-list {
    space-y: 1rem;
}

.tip-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-100);
}

.tip-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.tip-icon {
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.tip-content {
    font-size: 0.875rem;
    line-height: 1.5;
    color: var(--gray-600);
}

.tip-content strong {
    color: var(--gray-700);
    font-weight: 600;
}

/* Sticky Card */
.sticky-card {
    position: sticky;
    top: 2rem;
}

/* Invalid Feedback Enhancement */
.invalid-feedback {
    font-size: 0.875rem;
    margin-top: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 8px;
    color: var(--danger);
}

/* Form Text Enhancement */
.form-text {
    font-size: 0.8125rem;
    color: var(--gray-500);
    margin-top: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: var(--gray-50);
    border-radius: 8px;
    border-left: 3px solid var(--gray-300);
}

.hidden { display: none !important; }

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-header .col-lg-4 {
        margin-top: 1rem;
        text-align: left !important;
    }
    
    .sticky-card {
        position: static;
        margin-top: 2rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    .tip-item {
        padding: 1rem 0;
    }
}

@media (max-width: 576px) {
    .row .col-md-6 {
        margin-bottom: 1.5rem;
    }
    
    .modern-input,
    .modern-select,
    .modern-textarea {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
    
    .image-preview-container {
        min-height: 150px;
    }
    
    .placeholder-icon {
        font-size: 2.5rem;
    }
}
</style>

<script>
// Image preview functionality
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    // Get fallback image path from data attribute (set in Blade)
    const fallback = preview.dataset.fallback;

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = fallback;
        preview.style.display = 'block';
    }
}

// Auto-format price input
document.getElementById('price').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value) {
        e.target.value = value;
    }
});

// Form validation enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        inputs.forEach(input => {
            if (input.value.trim() === '') {
                input.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first invalid input
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }
        }
    });
});

// Smooth scrolling for form navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endsection