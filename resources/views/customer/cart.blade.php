<!-- resources/views/customer/cart.blade.php -->
@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <h2 class="fw-bold"><i class="bi bi-cart3"></i> Keranjang Belanja</h2>
            <p class="text-muted">Periksa dan kelola produk di keranjang Anda</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('catalog') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Lanjut Belanja
            </a>
        </div>
    </div>

    @if(!empty($cart) && count($cart) > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Item di Keranjang ({{ count($cart) }})</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cart as $productId => $item)
                        <div class="cart-item border-bottom p-3">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $item['image'] ? asset('storage/products/'.$item['image']) : asset('images/no-image.png') }}" 
                                         class="img-fluid rounded" alt="{{ $item['name'] }}" style="max-height: 80px;">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                    <p class="text-muted small mb-0">Harga: Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity({{ $productId }}, -1)">-</button>
                                        <input type="text" class="form-control text-center" value="{{ $item['quantity'] }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity({{ $productId }}, 1)">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <strong class="text-primary">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</strong>
                                </div>
                                <div class="col-md-2 text-end">
                                    <form method="POST" action="{{ route('customer.cart.remove', $productId) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus item dari keranjang?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Checkout Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ count($cart) }} item):</span>
                            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Estimasi Ongkir:</span>
                            <span class="text-muted">Hubungi Admin</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>

                        <!-- Checkout Form -->
                        <form method="POST" action="{{ route('customer.checkout') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran *</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="bank_transfer">Transfer Bank</option>
                                    <option value="cash">Cash/Tunai</option>
                                    <option value="other">Lainnya</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rencana Cicilan</label>
                                <select name="installment_plan" class="form-select">
                                    <option value="full_payment">Bayar Penuh</option>
                                    <option value="3_months">3 Bulan</option>
                                    <option value="6_months">6 Bulan</option>
                                    <option value="12_months">12 Bulan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat Pengiriman *</label>
                                <textarea name="shipaddress" class="form-control" rows="3" required placeholder="Masukkan alamat lengkap...">{{ auth()->user()->shipaddress }}</textarea>
                            </div>

                            <div class="alert alert-info small">
                                <i class="bi bi-info-circle"></i> 
                                Pesanan akan diproses setelah admin melakukan konfirmasi pembayaran.
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="bi bi-credit-card"></i> Checkout Sekarang
                            </button>
                        </form>

                        @php
                            $contact = \App\Models\Contact::getActive();
                            $whatsappMessage = "Halo, saya ingin konsultasi tentang pesanan di keranjang saya";
                            $whatsappUrl = $contact ? $contact->getWhatsAppUrl($whatsappMessage) : '#';
                        @endphp

                        <!-- Contact Admin -->
                        <div class="mt-3">
                            <a href=" {{ $whatsappUrl }} " 
                               class="btn btn-success w-100" target="_blank">
                                <i class="bi bi-whatsapp"></i> Konsultasi via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-cart-x display-1 text-muted"></i>
                    <h3 class="mt-4">Keranjang Kosong</h3>
                    <p class="text-muted mb-4">Anda belum menambahkan produk apapun ke keranjang</p>
                    <a href="{{ route('catalog') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-grid"></i> Mulai Berbelanja
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function updateQuantity(productId, change) {
    // This would require additional backend implementation for updating cart quantities
    // For now, we'll show a message to manually update
    alert('Fitur update quantity akan segera tersedia. Untuk sementara, hapus item dan tambahkan kembali dengan jumlah yang diinginkan.');
}
</script>
@endsection