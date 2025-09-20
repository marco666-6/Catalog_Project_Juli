<!-- resources/views/admin/orders/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<!-- Page Header -->
<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h1 class="dashboard-title mb-1">Detail Pesanan {{ $order->order_number }}</h1>
                    <p class="dashboard-subtitle mb-0">{{ $order->order_date->format('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 text-lg-end">
            <div class="d-flex gap-2 justify-content-lg-end">
                <button class="btn btn-primary" onclick="showStatusModal({{ $order->order_id }}, '{{ $order->status }}')">
                    <i class="bi bi-pencil-square me-2"></i>Update Status
                </button>
                <button class="btn btn-success" onclick="showAddPaymentModal({{ $order->order_id }})">
                    <i class="bi bi-plus-circle me-2"></i>Add Payment
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $order->user->phone) }}?text=Halo {{ $order->user->fullname }}, pesanan Anda {{ $order->order_number }} sedang kami proses." target="_blank">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp Customer
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="mailto:{{ $order->user->email }}?subject=Update Pesanan {{ $order->order_number }}">
                                <i class="bi bi-envelope me-2"></i>Email Customer
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i>Print Detail
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-lg-8 mb-4">
        <!-- Customer Info -->
        <div class="card modern-card mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <h5 class="header-title mb-0">Informasi Customer</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-group mb-3">
                            <label class="info-label">Nama Lengkap</label>
                            <div class="info-value">{{ $order->user->fullname }}</div>
                        </div>
                        <div class="info-group mb-3">
                            <label class="info-label">Email</label>
                            <div class="info-value">{{ $order->user->email }}</div>
                        </div>
                        <div class="info-group">
                            <label class="info-label">Nomor Telepon</label>
                            <div class="info-value">{{ $order->user->phone ?: 'Tidak ada' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group">
                            <label class="info-label">Alamat Pengiriman</label>
                            <div class="info-value">
                                <address class="mb-0">
                                    {{ $order->user->shipaddress ?: 'Alamat belum diisi' }}
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="card modern-card mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="bi bi-bag"></i>
                    </div>
                    <h5 class="header-title mb-0">Detail Pesanan</h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table modern-table mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th width="80">Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderDetails as $detail)
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <img src="{{ $detail->product->image ? asset('storage/products/'.$detail->product->image) : asset('images/no-image.png') }}" 
                                             class="product-image me-3" alt="Product">
                                        <div class="product-details">
                                            <h6 class="product-name">{{ $detail->product->name }}</h6>
                                            <span class="product-category">{{ $detail->product->category->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="price-text">{{ $detail->formatted_price }}</span>
                                </td>
                                <td>
                                    <span class="quantity-badge">{{ $detail->quantity }}</span>
                                </td>
                                <td>
                                    <span class="subtotal-text">{{ $detail->formatted_subtotal }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <th colspan="3" class="text-end">Total Pesanan:</th>
                                <th class="total-amount">{{ $order->formatted_total_price }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Tracking -->
        <div class="card modern-card mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h5 class="header-title mb-0">Riwayat Pembayaran</h5>
                    </div>
                    <button class="btn btn-sm btn-success" onclick="showAddPaymentModal({{ $order->order_id }})">
                        <i class="bi bi-plus-circle me-1"></i>Add Payment
                    </button>
                </div>
            </div>
            <div class="card-body">
                @php
                    $totalPaid = $order->payments()->approved()->sum('amount_paid');
                    $remainingBalance = max(0, $order->total_price - $totalPaid);
                    $paymentProgress = $order->total_price > 0 ? ($totalPaid / $order->total_price) * 100 : 0;
                @endphp
                
                <!-- Payment Summary -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="info-group">
                            <label class="info-label">Total Pesanan</label>
                            <div class="info-value">{{ $order->formatted_total_price }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-group">
                            <label class="info-label">Total Dibayar</label>
                            <div class="info-value text-success">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-group">
                            <label class="info-label">Sisa Tagihan</label>
                            <div class="info-value text-warning">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="info-label">Progress Pembayaran</span>
                        <span class="info-label">{{ number_format($paymentProgress, 1) }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $paymentProgress }}%"></div>
                    </div>
                </div>

                <!-- Payment History -->
                @if($order->payments->count() > 0)
                <div class="table-responsive">
                    <table class="table modern-table mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Bukti</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                                <td>{{ $payment->formatted_amount_paid }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status_badge }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->payment_proof)
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="showPaymentProof('{{ $payment->payment_proof_url }}')">
                                            <i class="bi bi-eye"></i> Lihat
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $payment->admin_notes ?: '-' }}</small>
                                </td>
                                <td>
                                    @if($payment->isPending())
                                        <button class="btn btn-sm btn-success me-1" 
                                                onclick="updatePaymentStatus({{ $payment->payment_id }}, 'approved')">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" 
                                                onclick="showRejectModal({{ $payment->payment_id }})">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-credit-card display-4 text-muted mb-3"></i>
                    <h6 class="text-muted">Belum ada pembayaran</h6>
                    <p class="text-muted mb-0">Customer belum melakukan pembayaran untuk pesanan ini</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Notes -->
        @if($order->confirmation)
        <div class="card modern-card mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="bi bi-chat-square-text"></i>
                    </div>
                    <h5 class="header-title mb-0">Catatan Pesanan</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="note-content">
                    {{ $order->confirmation }}
                </div>
            </div>
        </div>
        @endif

        <!-- Confirmation Management -->
        <div class="card modern-card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="bi bi-chat-square-dots"></i>
                    </div>
                    <h5 class="header-title mb-0">Kelola Catatan Pesanan</h5>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.confirmation', $order->order_id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="confirmation" class="form-label">Catatan/Pesan</label>
                        <textarea class="form-control" id="confirmation" name="confirmation" 
                                rows="4" placeholder="Tambahkan catatan untuk pesanan ini...">{{ $order->confirmation }}</textarea>
                        <div class="form-text">Catatan ini akan terlihat oleh customer dan admin</div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('confirmation').value=''">
                            <i class="bi bi-x-circle me-2"></i>Clear
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Catatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card modern-card mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <h6 class="header-title mb-0">Status Pesanan</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="status-display text-center mb-4">
                    <span class="status-badge status-{{ $order->status }} fs-6">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                
                <div class="payment-details">
                    <div class="detail-row mb-3">
                        <label class="detail-label">Metode Pembayaran</label>
                        <span class="payment-badge">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>

                    <div class="detail-row mb-4">
                        <label class="detail-label">Rencana Cicilan</label>
                        <span class="installment-badge">{{ ucfirst(str_replace('_', ' ', $order->installment_plan)) }}</span>
                    </div>

                    <button class="btn btn-primary w-100" onclick="showStatusModal({{ $order->order_id }}, '{{ $order->status }}')">
                        <i class="bi bi-pencil-square me-2"></i>Update Status
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card modern-card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="bi bi-lightning"></i>
                    </div>
                    <h6 class="header-title mb-0">Aksi Cepat</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $order->user->phone) }}?text=Halo {{ $order->user->fullname }}, pesanan Anda {{ $order->order_number }} sedang kami proses." 
                       class="btn btn-success" target="_blank">
                        <i class="bi bi-whatsapp me-2"></i>WhatsApp Customer
                    </a>
                    <a href="mailto:{{ $order->user->email }}?subject=Update Pesanan {{ $order->order_number }}" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>Email Customer
                    </a>
                    <button class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Print Detail
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Baru</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="addPaymentForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount_paid" class="form-label">Jumlah Pembayaran</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="amount_paid" name="amount_paid" 
                                   step="0.01" min="0.01" required>
                        </div>
                        <div class="form-text">Maksimal: Rp {{ number_format($remainingBalance ?? 0, 0, ',', '.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" 
                               accept="image/jpeg,image/png,image/jpg" required>
                        <div class="form-text">Format: JPEG, PNG, JPG. Maksimal: 5MB</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Catatan Admin</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" 
                                rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Tambah Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Proof Modal -->
<div class="modal fade" id="paymentProofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center">
                <img id="paymentProofImage" src="" class="img-fluid" alt="Bukti Pembayaran">
            </div>
        </div>
    </div>
</div>

<!-- Reject Payment Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reject_reason" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="reject_reason" name="admin_notes" 
                                rows="3" required placeholder="Berikan alasan penolakan pembayaran"></textarea>
                    </div>
                    <input type="hidden" name="status" value="rejected">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Order Detail Page Specific Styles */

/* Header Icons */
.header-icon {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.1rem;
}

.header-title {
    color: var(--gray-700);
    font-weight: 600;
}

/* Info Groups */
.info-group {
    border-left: 4px solid var(--primary);
    padding-left: 1rem;
}

.info-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-500);
    margin-bottom: 0.25rem;
    display: block;
}

.info-value {
    font-size: 1rem;
    color: var(--gray-700);
    font-weight: 500;
}

/* Product Info */
.product-info {
    display: flex;
    align-items: center;
}

.product-image {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid var(--gray-100);
}

.product-name {
    margin: 0 0 0.25rem 0;
    font-weight: 600;
    color: var(--gray-700);
    font-size: 1rem;
}

.product-category {
    font-size: 0.875rem;
    color: var(--gray-500);
    background: rgba(139, 92, 246, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-weight: 500;
}

/* Price and Quantity */
.price-text {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 1rem;
}

.quantity-badge {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.subtotal-text {
    font-weight: 700;
    color: var(--primary);
    font-size: 1.1rem;
}

/* Total Row */
.total-row th {
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.05), rgba(139, 92, 246, 0.05));
    border-top: 2px solid var(--primary);
    padding: 1.25rem 1rem;
    font-size: 1.1rem;
}

.total-amount {
    color: var(--primary) !important;
    font-size: 1.5rem !important;
    font-weight: 700 !important;
}

/* Status Display */
.status-display {
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.05), rgba(139, 92, 246, 0.05));
    border-radius: 16px;
    margin-bottom: 1.5rem;
}

.status-badge {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: var(--shadow);
}

/* Status Badge Colors (consistent with index page) */
.status-pending {
    background: rgba(251, 191, 36, 0.1);
    color: var(--warning);
    border: 2px solid rgba(251, 191, 36, 0.3);
}

.status-confirmed {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border: 2px solid rgba(59, 130, 246, 0.3);
}

.status-processing {
    background: rgba(139, 92, 246, 0.1);
    color: var(--secondary);
    border: 2px solid rgba(139, 92, 246, 0.3);
}

.status-shipped {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 2px solid rgba(16, 185, 129, 0.3);
}

.status-delivered {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border: 2px solid rgba(34, 197, 94, 0.3);
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
    border: 2px solid rgba(239, 68, 68, 0.3);
}

/* Payment Details */
.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-100);
}

.detail-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-500);
    margin: 0;
}

.payment-badge {
    background: rgba(139, 92, 246, 0.1);
    color: var(--secondary);
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.installment-badge {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

/* Note Content */
.note-content {
    background: rgba(59, 130, 246, 0.05);
    padding: 1.5rem;
    border-radius: 12px;
    border-left: 4px solid var(--primary);
    color: var(--gray-700);
    line-height: 1.6;
    font-style: italic;
}

/* Progress Bar */
.progress {
    height: 8px;
    border-radius: 4px;
    background: var(--gray-200);
}

.progress-bar {
    border-radius: 4px;
    transition: width 0.6s ease;
}

/* Badge Enhancements for Payment Status */
.bg-warning {
    background: rgba(251, 191, 36, 0.1) !important;
    color: #f59e0b !important;
}

.bg-success {
    background: rgba(34, 197, 94, 0.1) !important;
    color: #22c55e !important;
}

.bg-danger {
    background: rgba(239, 68, 68, 0.1) !important;
    color: #ef4444 !important;
}

/* Button Enhancements */
.btn {
    border-radius: 10px;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-success {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #16a34a, #15803d);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--secondary), var(--primary));
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
    border-radius: 6px;
}

/* Modal Enhancements */
.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: var(--shadow-xl);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-bottom: none;
    padding: 1.5rem;
}

.modal-title {
    font-weight: 700;
}

.btn-close {
    filter: brightness(0) invert(1);
}

.modal-body {
    padding: 2rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid var(--gray-100);
    padding: 1rem 1.5rem 1.5rem;
    background: var(--gray-50);
}

/* Form Controls */
.form-select {
    border-radius: 10px;
    border: 2px solid var(--gray-200);
    padding: 0.75rem 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.25);
}

.form-control {
    border-radius: 10px;
    border: 2px solid var(--gray-200);
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.25);
}

.input-group-text {
    background: var(--gray-100);
    border: 2px solid var(--gray-200);
    border-radius: 10px 0 0 10px;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }

    .col-lg-4 .text-lg-end {
        text-align: left !important;
    }

    .col-lg-4 .justify-content-lg-end {
        justify-content: flex-start !important;
    }

    .product-info {
        flex-direction: column;
        align-items: flex-start;
        text-align: center;
    }

    .product-image {
        margin-bottom: 0.75rem;
    }

    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .info-group {
        margin-bottom: 1.5rem !important;
    }

    .status-display {
        padding: 1rem;
    }

    .header-icon {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .dashboard-header {
        text-align: center;
    }

    .modern-table th,
    .modern-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
    }

    .product-name {
        font-size: 0.875rem;
    }

    .status-badge {
        font-size: 0.875rem !important;
        padding: 0.5rem 1rem;
    }

    .total-amount {
        font-size: 1.25rem !important;
    }
}

/* Print Styles */
@media print {
    .dashboard-header .col-lg-4,
    .btn,
    .modal {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .dashboard-header {
        margin-bottom: 2rem !important;
    }
    
    body {
        background: white !important;
    }
}
</style>

<script>
function showStatusModal(orderId, currentStatus) {
    document.getElementById('statusForm').action = `/admin/orders/${orderId}/status`;
    document.getElementById('status').value = currentStatus;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

function showAddPaymentModal(orderId) {
    document.getElementById('addPaymentForm').action = `/admin/orders/${orderId}/payment`;
    new bootstrap.Modal(document.getElementById('addPaymentModal')).show();
}

function showPaymentProof(imageUrl) {
    document.getElementById('paymentProofImage').src = imageUrl;
    new bootstrap.Modal(document.getElementById('paymentProofModal')).show();
}

function updatePaymentStatus(paymentId, status) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/payments/${paymentId}/status`;
    form.innerHTML = `
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="${status}">
    `;
    document.body.appendChild(form);
    form.submit();
}

function showRejectModal(paymentId) {
    document.getElementById('rejectForm').action = `/admin/payments/${paymentId}/status`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

// Enhanced print functionality
function printOrderDetail() {
    window.print();
}

// Status badge animation
document.addEventListener('DOMContentLoaded', function() {
    const statusBadge = document.querySelector('.status-badge');
    if (statusBadge) {
        statusBadge.style.animation = 'pulse 2s infinite';
    }
});

// File upload preview
document.getElementById('payment_proof')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // MB
        if (fileSize > 5) {
            alert('Ukuran file terlalu besar. Maksimal 5MB.');
            e.target.value = '';
        }
    }
});

// Form validation for payment amount
document.getElementById('addPaymentForm')?.addEventListener('submit', function(e) {
    const amountPaid = parseFloat(document.getElementById('amount_paid').value);
    const remainingBalance = {{ $remainingBalance ?? 0 }};
    
    if (amountPaid > remainingBalance) {
        e.preventDefault();
        alert('Jumlah pembayaran tidak boleh melebihi sisa tagihan.');
    }
});

// Add pulse animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(236, 72, 153, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(236, 72, 153, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(236, 72, 153, 0);
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection