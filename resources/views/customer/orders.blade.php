<!-- resources/views/customer/orders.blade.php -->
@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <h2 class="fw-bold"><i class="bi bi-bag-check"></i> Riwayat Pesanan</h2>
            <p class="text-muted">Pantau status dan detail semua pesanan Anda</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('catalog') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Buat Pesanan Baru
            </a>
        </div>
    </div>

    @if($orders->count() > 0)
        <!-- Orders List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>Item</th>
                                        <th>Total</th>
                                        <th>Metode Bayar</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            <small>{{ $order->order_date->format('d M Y') }}</small><br>
                                            <small class="text-muted">{{ $order->order_date->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $order->orderDetails->count() }} item</span>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $order->formatted_total_price }}</strong>
                                        </td>
                                        <td>
                                            <small>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_badge }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('customer.order.show', $order->order_id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- No Orders -->
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-bag-x display-1 text-muted"></i>
                    <h3 class="mt-4">Belum Ada Pesanan</h3>
                    <p class="text-muted mb-4">Anda belum pernah membuat pesanan. Mulai berbelanja sekarang!</p>
                    <a href="{{ route('catalog') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-grid"></i> Lihat Katalog Produk
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection