<!-- resources/views/customer/order-detail.blade.php -->
@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <h2 class="fw-bold">Detail Pesanan {{ $order->order_number }}</h2>
            <p class="text-muted">{{ $order->order_date->format('d F Y, H:i') }}</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Info -->
        <div class="col-lg-8 mb-4">
            <!-- Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Status Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Status Saat Ini:</strong><br>
                            <span class="badge bg-{{ $order->status_badge }} fs-6">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Metode Pembayaran:</strong><br>
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                        </div>
                    </div>
                    
                    @if($order->confirmation)
                        <div class="mt-3">
                            <strong>Catatan Admin:</strong>
                            <p class="text-muted mb-0">{{ $order->confirmation }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list"></i> Item Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $detail->product->image ? asset('storage/products/'.$detail->product->image) : asset('images/no-image.png') }}" 
                                                 class="me-3 rounded" width="50" height="50" style="object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0">{{ $detail->product->name }}</h6>
                                                <small class="text-muted">{{ $detail->product->category->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $detail->formatted_price }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td><strong>{{ $detail->formatted_subtotal }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-primary">{{ $order->formatted_total_price }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add this section to customer order detail view -->
            <!-- Payment Section for Customer -->
            <div class="card modern-card mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="header-icon">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <h5 class="header-title mb-0">Status Pembayaran</h5>
                        </div>
                        @if($order->payments()->approved()->sum('amount_paid') < $order->total_price)
                            <button class="btn btn-sm btn-primary" onclick="showPaymentModal()">
                                <i class="bi bi-plus-circle me-1"></i>Bayar Sekarang
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $totalPaid = $order->payments()->approved()->sum('amount_paid');
                        $remainingBalance = max(0, $order->total_price - $totalPaid);
                        $paymentProgress = $order->total_price > 0 ? ($totalPaid / $order->total_price) * 100 : 0;
                    @endphp
                    
                    <!-- Payment Summary -->
                    <div class="payment-summary mb-4">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="summary-card total">
                                    <div class="summary-value">{{ $order->formatted_total_price }}</div>
                                    <div class="summary-label">Total Pesanan</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="summary-card paid">
                                    <div class="summary-value">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                                    <div class="summary-label">Sudah Dibayar</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="summary-card remaining">
                                    <div class="summary-value">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</div>
                                    <div class="summary-label">Sisa Tagihan</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="payment-progress mt-4">
                            <div class="progress-header d-flex justify-content-between mb-2">
                                <span class="progress-label">Progress Pembayaran</span>
                                <span class="progress-percentage">{{ number_format($paymentProgress, 1) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" 
                                    style="width: {{ $paymentProgress }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History -->
                    @if($order->payments->count() > 0)
                    <div class="payment-history">
                        <h6 class="mb-3">Riwayat Pembayaran</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                                        <td>{{ $payment->formatted_amount_paid }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payment->status_badge }}">
                                                @if($payment->status == 'pending')
                                                    Menunggu Verifikasi
                                                @elseif($payment->status == 'approved')
                                                    Disetujui
                                                @else
                                                    Ditolak
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                @if($payment->admin_notes)
                                                    {{ $payment->admin_notes }}
                                                @else
                                                    -
                                                @endif
                                            </small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="empty-payments text-center py-4">
                        <i class="bi bi-credit-card-2-back display-4 text-muted mb-3"></i>
                        <h6 class="text-muted">Belum ada pembayaran</h6>
                        <p class="text-muted mb-3">Silakan lakukan pembayaran untuk melanjutkan pesanan</p>
                        @if($remainingBalance > 0)
                            <button class="btn btn-primary" onclick="showPaymentModal()">
                                <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                            </button>
                        @endif
                    </div>
                    @endif

                    @if($paymentProgress >= 100)
                    <div class="alert alert-success mt-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Pembayaran Lunas!</strong> Terima kasih, pembayaran Anda telah lunas.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Payment Modal -->
            <div class="modal fade" id="customerPaymentModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Bukti Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <form method="POST" action="{{ route('customer.payment.submit', $order->order_id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="payment-info mb-4">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="info-card">
                                                <div class="info-value">{{ $order->formatted_total_price }}</div>
                                                <div class="info-label">Total Pesanan</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-card">
                                                <div class="info-value">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</div>
                                                <div class="info-label">Sisa Tagihan</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="customer_amount_paid" class="form-label">Jumlah Pembayaran</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="customer_amount_paid" name="amount_paid" 
                                            step="0.01" min="0.01" max="{{ $remainingBalance }}" required>
                                    </div>
                                    <div class="form-text">Maksimal: Rp {{ number_format($remainingBalance, 0, ',', '.') }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="customer_payment_proof" class="form-label">Bukti Pembayaran</label>
                                    <input type="file" class="form-control" id="customer_payment_proof" name="payment_proof" 
                                        accept="image/jpeg,image/png,image/jpg" required>
                                    <div class="form-text">Upload foto bukti transfer. Format: JPEG, PNG, JPG. Maksimal: 5MB</div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Informasi Rekening:</strong><br>
                                    Bank BCA: 1234567890<br>
                                    a/n PT. Batam Supplier Catalog
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload me-2"></i>Upload Bukti Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <!-- Contact Admin -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-headset"></i> Butuh Bantuan?</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Hubungi admin untuk informasi lebih lanjut tentang pesanan ini.</p>
                    <a href="https://wa.me/6281234567890?text=Halo, saya ingin bertanya tentang pesanan {{ $order->order_number }}" 
                       class="btn btn-success w-100 mb-2" target="_blank">
                        <i class="bi bi-whatsapp"></i> WhatsApp Admin
                    </a>
                    <a href="mailto:admin@batamgeneralsupplier.com?subject=Pertanyaan Pesanan {{ $order->order_number }}" 
                       class="btn btn-outline-primary w-100">
                        <i class="bi bi-envelope"></i> Email Admin
                    </a>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Timeline Pesanan</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ in_array($order->status, ['pending', 'confirmed', 'processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pesanan Dibuat</h6>
                                <small class="text-muted">{{ $order->order_date->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Dikonfirmasi</h6>
                                <small class="text-muted">{{ $order->status !== 'pending' ? 'Sudah dikonfirmasi' : 'Menunggu konfirmasi' }}</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Diproses</h6>
                                <small class="text-muted">{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'Sedang diproses' : 'Belum diproses' }}</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : '' }}">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Dikirim</h6>
                                <small class="text-muted">{{ in_array($order->status, ['shipped', 'delivered']) ? 'Dalam pengiriman' : 'Belum dikirim' }}</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->status === 'delivered' ? 'active' : '' }}">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Selesai</h6>
                                <small class="text-muted">{{ $order->status === 'delivered' ? 'Pesanan selesai' : 'Belum selesai' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Customer Payment Styles */
.payment-summary {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(99, 102, 241, 0.05));
    border-radius: 16px;
    padding: 1.5rem;
}

.summary-card {
    background: white;
    padding: 1.25rem;
    border-radius: 12px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-2px);
}

.summary-card.total {
    border-left: 4px solid #3b82f6;
}

.summary-card.paid {
    border-left: 4px solid #22c55e;
}

.summary-card.remaining {
    border-left: 4px solid #f59e0b;
}

.summary-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.summary-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.payment-progress {
    background: white;
    padding: 1.25rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.progress {
    height: 12px;
    border-radius: 6px;
    background: var(--gray-200);
}

.progress-bar {
    border-radius: 6px;
    background: linear-gradient(135deg, #22c55e, #16a34a) !important;
    transition: width 0.6s ease;
}

.empty-payments {
    padding: 3rem 1rem;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.02), rgba(99, 102, 241, 0.02));
    border-radius: 12px;
    border: 2px dashed var(--gray-200);
}

.payment-info .info-card {
    background: var(--gray-50);
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid var(--gray-200);
}

.payment-info .info-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: 0.25rem;
}

.payment-info .info-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
}

.alert-success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(16, 185, 129, 0.1));
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #166534;
}

.alert-info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.1));
    border: 1px solid rgba(59, 130, 246, 0.3);
    color: #1e40af;
}
</style>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -12px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #e9ecef;
}

.timeline-item.active .timeline-marker {
    background: var(--bs-primary);
}

.timeline-content {
    margin-left: 10px;
}
</style>

<script>
function showPaymentModal() {
    new bootstrap.Modal(document.getElementById('customerPaymentModal')).show();
}

// File validation for customer upload
document.getElementById('customer_payment_proof')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // MB
        if (fileSize > 5) {
            alert('Ukuran file terlalu besar. Maksimal 5MB.');
            e.target.value = '';
        }
    }
});
</script>
@endsection