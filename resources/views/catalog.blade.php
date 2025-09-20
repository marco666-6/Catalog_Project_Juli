<!-- resources/views/catalog.blade.php -->
@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <h2 class="fw-bold">Katalog Produk</h2>
            <p class="text-muted">Temukan berbagai produk berkualitas untuk kebutuhan Anda</p>
        </div>
        <div class="col-lg-6">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('search') }}" class="d-flex gap-2">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" 
                                {{ request('category') == $category->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="q" class="form-control" placeholder="Cari produk..." value="{{ request('q') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Search Results Info -->
    @if(request('q') || request('category'))
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            Menampilkan {{ $products->total() }} produk
            @if(request('q'))
                untuk pencarian "{{ request('q') }}"
            @endif
            @if(request('category'))
                dalam kategori "{{ $categories->find(request('category'))->name ?? 'Tidak ditemukan' }}"
            @endif
            <a href="{{ route('catalog') }}" class="btn btn-sm btn-outline-primary ms-2">Reset Filter</a>
        </div>
    @endif

    <!-- Products Grid -->
    <div class="row">
        @forelse($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="{{ $product->image ? asset('storage/products/'.$product->image) : asset('images/no-image.png') }}" 
                         class="card-img-top product-image" alt="{{ $product->name }}">
                    @if($product->stock <= 5)
                        <span class="position-absolute top-0 end-0 badge bg-warning m-2">Stok Terbatas</span>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">{{ $product->name }}</h6>
                    <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 100) }}</p>
                    <div class="mb-2">
                        <span class="badge bg-secondary small">{{ $product->category->name }}</span>
                        <span class="text-muted small ms-2">Stok: {{ $product->stock }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="price-text">{{ $product->formatted_price }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('product.detail', $product->product_id) }}" 
                           class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        @auth
                            @if(auth()->user()->isCustomer())
                                <button class="btn btn-primary btn-sm flex-fill" 
                                        onclick="addToCart({{ $product->product_id }})"
                                        {{ $product->stock == 0 ? 'disabled' : '' }}>
                                    <i class="bi bi-cart-plus"></i> 
                                    {{ $product->stock == 0 ? 'Habis' : 'Keranjang' }}
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm flex-fill">
                                <i class="bi bi-cart-plus"></i> Keranjang
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-search display-1 text-muted"></i>
                <h4 class="mt-3">Produk Tidak Ditemukan</h4>
                <p class="text-muted">Coba kata kunci atau filter yang berbeda</p>
                <a href="{{ route('catalog') }}" class="btn btn-primary">Lihat Semua Produk</a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection