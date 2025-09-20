<!-- resources/views/product-detail.blade.php -->
@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Katalog</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Detail -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <img src="{{ $product->image ? asset('storage/products/'.$product->image) : asset('images/no-image.png') }}" 
                     class="card-img-top" style="height: 400px; object-fit: cover;" alt="{{ $product->name }}">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                    <h2 class="card-title fw-bold">{{ $product->name }}</h2>
                    <h3 class="price-text mb-3">{{ $product->formatted_price }}</h3>
                    
                    <div class="mb-3">
                        <strong>Ketersediaan: </strong>
                        @if($product->stock > 0)
                            <span class="text-success">
                                <i class="bi bi-check-circle"></i> Tersedia ({{ $product->stock }} unit)
                            </span>
                        @else
                            <span class="text-danger">
                                <i class="bi bi-x-circle"></i> Stok Habis
                            </span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <strong>Deskripsi Produk:</strong>
                        <p class="mt-2">{{ $product->description }}</p>
                    </div>

                    @auth
                        @if(auth()->user()->isCustomer())
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Jumlah:</label>
                                    <input type="number" class="form-control" id="quantity" value="1" 
                                           min="1" max="{{ $product->stock }}" {{ $product->stock == 0 ? 'disabled' : '' }}>
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <button class="btn btn-primary btn-lg flex-fill" 
                                        onclick="addToCartWithQuantity({{ $product->product_id }})"
                                        {{ $product->stock == 0 ? 'disabled' : '' }}>
                                    <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                </button>
                                <a href="https://wa.me/6281234567890?text=Halo, saya tertarik dengan produk {{ urlencode($product->name) }}" 
                                   class="btn btn-success btn-lg" target="_blank">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Silakan <a href="{{ route('login') }}" class="alert-link">login</a> untuk menambahkan produk ke keranjang atau hubungi kami langsung.
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg flex-fill">
                                <i class="bi bi-person-plus"></i> Login untuk Pesan
                            </a>
                            <a href="https://wa.me/6281234567890?text=Halo, saya tertarik dengan produk {{ urlencode($product->name) }}" 
                               class="btn btn-success btn-lg" target="_blank">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h4 class="fw-bold mb-4">Produk Terkait</h4>
        <div class="row">
            @foreach($relatedProducts as $related)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <img src="{{ $related->image ? asset('storage/products/'.$related->image) : asset('images/no-image.png') }}" 
                         class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $related->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $related->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($related->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="price-text">{{ $related->formatted_price }}</span>
                        </div>
                        <a href="{{ route('product.detail', $related->product_id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function addToCartWithQuantity(productId) {
    const quantity = document.getElementById('quantity').value;
    addToCart(productId, quantity);
}
</script>
@endsection