<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Solusi Lengkap Kebutuhan Industri & Bisnis Anda</h1>
                <p class="lead mb-4">PT. Batam General Supplier menyediakan berbagai produk berkualitas tinggi untuk mendukung operasional perusahaan dan kebutuhan sehari-hari dengan pelayanan terpercaya.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('catalog') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-grid"></i> Lihat Katalog
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-telephone"></i> Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="bi bi-building display-1"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mengapa Memilih Kami?</h2>
            <p class="text-muted">Keunggulan layanan yang kami berikan untuk kepuasan pelanggan</p>
        </div>
        
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h5>Produk Berkualitas</h5>
                <p class="text-muted">Semua produk telah melewati seleksi ketat untuk memastikan kualitas terbaik bagi pelanggan.</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon">
                    <i class="bi bi-truck"></i>
                </div>
                <h5>Pengiriman Cepat</h5>
                <p class="text-muted">Sistem distribusi yang efisien memastikan produk sampai tepat waktu sesuai kebutuhan Anda.</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon">
                    <i class="bi bi-headset"></i>
                </div>
                <h5>Layanan 24/7</h5>
                <p class="text-muted">Tim customer service kami siap membantu Anda kapan saja dengan respon yang cepat dan profesional.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Produk Unggulan</h2>
            <p class="text-muted">Pilihan produk terbaik yang paling diminati pelanggan</p>
        </div>
        
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <img src="{{ $product->image ? asset('storage/products/'.$product->image) : asset('images/no-image.png') }}" 
                         class="card-img-top product-image" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $product->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price-text">{{ $product->formatted_price }}</span>
                            <span class="badge bg-secondary">{{ $product->category->name }}</span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('product.detail', $product->product_id) }}" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('catalog') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-grid"></i> Lihat Semua Produk
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Kategori Produk</h2>
            <p class="text-muted">Temukan produk sesuai kebutuhan Anda</p>
        </div>
        
        <div class="row">
            @foreach($categories as $category)
            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('catalog', ['category' => $category->category_id]) }}" class="text-decoration-none">
                    <div class="card border-0 bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-tags display-4 mb-3"></i>
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text">{{ $category->description }}</p>
                            <span class="badge bg-light text-primary">{{ $category->products_count }} Produk</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Siap Berbelanja dengan Kami?</h3>
                <p class="mb-0">Daftarkan diri Anda atau hubungi tim kami untuk mendapatkan penawaran terbaik dan pelayanan yang memuaskan.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg me-2">
                        <i class="bi bi-person-plus"></i> Login
                    </a>
                @endguest
                <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>
@endsection