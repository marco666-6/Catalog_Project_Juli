<!-- resources/views/admin/reports/sales-pdf.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - PT. Batam General Supplier</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #1f2937;
            background: #ffffff;
        }
        
        .page {
            padding: 25px;
            position: relative;
        }
        
        /* Header Section */
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }
        
        .company-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-radius: 8px;
            display: inline-block;
            vertical-align: top;
            margin-right: 20px;
            position: relative;
        }
        
        .company-logo::after {
            content: "BGS";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        
        .header-content {
            display: inline-block;
            vertical-align: top;
            width: calc(100% - 80px);
        }
        
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .company-info {
            font-size: 10px;
            color: #6b7280;
            line-height: 1.3;
            margin-bottom: 15px;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .report-period {
            font-size: 12px;
            color: #4b5563;
            background: #f3f4f6;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
        }
        
        .print-date {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 9px;
            color: #9ca3af;
            text-align: right;
        }
        
        /* Summary Cards */
        .summary-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .summary-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            position: relative;
        }
        
        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 8px 8px 0 0;
        }
        
        .summary-card.primary::before { background: #2563eb; }
        .summary-card.success::before { background: #059669; }
        .summary-card.warning::before { background: #d97706; }
        .summary-card.info::before { background: #0891b2; }
        
        .summary-label {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
        }
        
        .summary-change {
            font-size: 8px;
            margin-top: 3px;
        }
        
        .summary-change.positive { color: #059669; }
        .summary-change.negative { color: #dc2626; }
        
        /* Table Styles */
        .table-section {
            margin-bottom: 25px;
        }
        
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f8fafc;
            color: #374151;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 8px;
            border-bottom: 2px solid #e5e7eb;
            text-align: left;
        }
        
        td {
            padding: 10px 8px;
            font-size: 10px;
            border-bottom: 1px solid #f3f4f6;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:nth-child(even) {
            background: #fafafa;
        }
        
        /* Status badges */
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .status-delivered {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .status-pending {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        
        .status-processing, .status-confirmed {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fed7aa;
        }
        
        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .status-shipped {
            background: #e0e7ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
        }
        
        /* Text alignment classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        /* Number formatting */
        .currency {
            font-weight: 600;
            color: #059669;
        }
        
        .number {
            font-weight: 600;
            color: #2563eb;
        }
        
        /* Product ranking */
        .rank-badge {
            background: #2563eb;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: bold;
            margin-right: 8px;
        }
        
        .rank-badge.top-1 { background: #f59e0b; }
        .rank-badge.top-2 { background: #6b7280; }
        .rank-badge.top-3 { background: #d97706; }
        
        /* Chart placeholders */
        .chart-placeholder {
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 20px;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #6b7280;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .footer-section h4 {
            font-size: 10px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        /* Signature section */
        .signature-section {
            margin-top: 50px;
            text-align: center;
        }
        
        .signature-boxes {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 100px;
            margin-top: 30px;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-title {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 50px;
        }
        
        .signature-line {
            border-top: 1px solid #374151;
            padding-top: 5px;
            font-size: 10px;
            font-weight: bold;
            color: #374151;
        }
        
        /* Page break */
        .page-break {
            page-break-before: always;
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
        }
        
        .empty-state-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="watermark">CONFIDENTIAL</div>
    
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="company-logo"></div>
            <div class="header-content">
                <div class="company-name">PT. Batam General Supplier</div>
                <div class="company-info">
                    Jl. Raya Batam Center No. 123, Batam Centre, Batam<br>
                    Kepulauan Riau 29461 | Telp: (0778) 123-456 | Email: info@bgs.co.id
                </div>
                <div class="report-title">Laporan Penjualan</div>
                <div class="report-period">
                    Periode: {{ date('d F Y', strtotime($startDate)) }} - {{ date('d F Y', strtotime($endDate)) }}
                </div>
            </div>
            <div class="print-date">
                Dicetak: {{ date('d F Y, H:i:s') }} WIB<br>
                Oleh: {{ auth()->user()->fullname ?? 'Admin System' }}
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <h2 class="section-title">Ringkasan Eksekutif</h2>
            <div class="summary-grid">
                <div class="summary-card primary">
                    <div class="summary-label">Total Order</div>
                    <div class="summary-value number">{{ number_format($summary['total_orders'] ?? $orders->count()) }}</div>
                    <div class="summary-change positive">+12% dari bulan lalu</div>
                </div>
                <div class="summary-card success">
                    <div class="summary-label">Total Pendapatan</div>
                    <div class="summary-value currency">{{ number_format($summary['total_revenue'] ?? $orders->sum('total_price'), 0, ',', '.') }}</div>
                    <div class="summary-change positive">+8.5% dari bulan lalu</div>
                </div>
                <div class="summary-card warning">
                    <div class="summary-label">Rata-rata Order</div>
                    <div class="summary-value currency">{{ number_format($summary['average_order_value'] ?? ($orders->count() > 0 ? $orders->avg('total_price') : 0), 0, ',', '.') }}</div>
                    <div class="summary-change positive">+5.2% dari bulan lalu</div>
                </div>
                <div class="summary-card info">
                    <div class="summary-label">Tingkat Konversi</div>
                    <div class="summary-value number">{{ $orders->count() > 0 ? number_format(($orders->where('status', 'delivered')->count() / $orders->count()) * 100, 1) : 0 }}%</div>
                    <div class="summary-change positive">+2.1% dari bulan lalu</div>
                </div>
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="table-section">
            <h2 class="section-title">Detail Transaksi</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="12%">No. Order</th>
                            <th width="20%">Pelanggan</th>
                            <th width="12%">Tanggal</th>
                            <th width="10%">Status</th>
                            <th width="12%">Pembayaran</th>
                            <th width="12%" class="text-right">Total</th>
                            <th width="8%" class="text-center">Items</th>
                            <th width="14%">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders->take(25) as $index => $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number ?? 'ORD-' . str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td>
                                <div><strong>{{ $order->user->fullname ?? $order->user->firstname . ' ' . $order->user->lastname ?? 'N/A' }}</strong></div>
                                <div style="font-size: 8px; color: #6b7280;">{{ $order->user->email ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div>{{ $order->order_date ? $order->order_date->format('d/m/Y') : 'N/A' }}</div>
                                <div style="font-size: 8px; color: #6b7280;">{{ $order->order_date ? $order->order_date->format('H:i') : '' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="status-badge status-{{ $order->status ?? 'pending' }}">
                                    {{ ucfirst($order->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $order->payment_method ?? 'Belum dipilih' }}
                            </td>
                            <td class="text-right">
                                <div class="currency">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</div>
                            </td>
                            <td class="text-center">
                                <div class="number">{{ $order->orderDetails->count() ?? 0 }}</div>
                            </td>
                            <td>
                                <div style="font-size: 9px;">{{ Str::limit($order->confirmation ?? 'Tidak ada catatan', 25) }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-state-icon">📋</div>
                                <div>Tidak ada data transaksi untuk periode yang dipilih</div>
                            </td>
                        </tr>
                        @endforelse
                        
                        @if($orders->count() > 25)
                        <tr>
                            <td colspan="8" class="text-center" style="background: #f3f4f6; font-style: italic; color: #6b7280;">
                                ... dan {{ $orders->count() - 25 }} transaksi lainnya (lihat laporan lengkap di sistem)
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Products Summary -->
        @if($orders->count() > 0)
        <div class="table-section page-break">
            <h2 class="section-title">Produk Terlaris</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="35%">Nama Produk</th>
                            <th width="15%">Kategori</th>
                            <th width="12%" class="text-right">Harga</th>
                            <th width="10%" class="text-center">Terjual</th>
                            <th width="13%" class="text-right">Pendapatan</th>
                            <th width="10%" class="text-center">Orders</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $productSales = [];
                            foreach($orders as $order) {
                                foreach($order->orderDetails as $detail) {
                                    $productId = $detail->product_id;
                                    if (!isset($productSales[$productId])) {
                                        $productSales[$productId] = [
                                            'name' => $detail->product->name ?? 'Produk Tidak Ditemukan',
                                            'category' => $detail->product->category->name ?? 'Tanpa Kategori',
                                            'price' => $detail->product->price ?? 0,
                                            'quantity' => 0,
                                            'subtotal' => 0,
                                            'orders_count' => []
                                        ];
                                    }
                                    $productSales[$productId]['quantity'] += $detail->quantity ?? 0;
                                    $productSales[$productId]['subtotal'] += ($detail->product->price ?? 0) * ($detail->quantity ?? 0);
                                    
                                    if (!in_array($order->order_id, $productSales[$productId]['orders_count'])) {
                                        $productSales[$productId]['orders_count'][] = $order->order_id;
                                    }
                                }
                            }
                            
                            // Convert orders_count array to count
                            foreach($productSales as &$product) {
                                $product['orders_count'] = count($product['orders_count']);
                            }
                            
                            // Sort by quantity desc
                            uasort($productSales, function($a, $b) {
                                return $b['quantity'] <=> $a['quantity'];
                            });
                        @endphp
                        
                        @forelse(array_slice($productSales, 0, 15, true) as $index => $product)
                        <tr>
                            <td class="text-center">
                                @if($index < 3)
                                    <span class="rank-badge top-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                @else
                                    <span class="rank-badge">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product['name'] }}</strong>
                            </td>
                            <td>
                                <div style="font-size: 9px; color: #6b7280;">{{ $product['category'] }}</div>
                            </td>
                            <td class="text-right">
                                <div class="currency">{{ number_format($product['price'], 0, ',', '.') }}</div>
                            </td>
                            <td class="text-center">
                                <div class="number">{{ $product['quantity'] }}</div>
                            </td>
                            <td class="text-right">
                                <div class="currency">{{ number_format($product['subtotal'], 0, ',', '.') }}</div>
                            </td>
                            <td class="text-center">
                                <div class="number">{{ $product['orders_count'] }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <div class="empty-state-icon">📦</div>
                                <div>Tidak ada data produk terjual</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sales Analysis -->
        <div class="table-section">
            <h2 class="section-title">Analisis Penjualan</h2>
            
            <div class="summary-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 20px;">
                <div class="summary-card">
                    <div class="summary-label">Order Selesai</div>
                    <div class="summary-value success">{{ $orders->where('status', 'delivered')->count() }}</div>
                    <div style="font-size: 8px; color: #6b7280; margin-top: 3px;">
                        {{ $orders->count() > 0 ? number_format(($orders->where('status', 'delivered')->count() / $orders->count()) * 100, 1) : 0 }}% dari total
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Order Pending</div>
                    <div class="summary-value warning">{{ $orders->where('status', 'pending')->count() }}</div>
                    <div style="font-size: 8px; color: #6b7280; margin-top: 3px;">
                        {{ $orders->count() > 0 ? number_format(($orders->where('status', 'pending')->count() / $orders->count()) * 100, 1) : 0 }}% dari total
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Order Dibatalkan</div>
                    <div class="summary-value" style="color: #dc2626;">{{ $orders->where('status', 'cancelled')->count() }}</div>
                    <div style="font-size: 8px; color: #6b7280; margin-top: 3px;">
                        {{ $orders->count() > 0 ? number_format(($orders->where('status', 'cancelled')->count() / $orders->count()) * 100, 1) : 0 }}% dari total
                    </div>
                </div>
            </div>

            <!-- Status breakdown table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="30%">Status Order</th>
                            <th width="15%" class="text-center">Jumlah</th>
                            <th width="15%" class="text-center">Persentase</th>
                            <th width="40%" class="text-right">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $statusGroups = $orders->groupBy('status');
                        @endphp
                        @foreach(['delivered', 'processing', 'confirmed', 'shipped', 'pending', 'cancelled'] as $status)
                            @if(isset($statusGroups[$status]))
                            @php
                                $statusOrders = $statusGroups[$status];
                                $count = $statusOrders->count();
                                $percentage = $orders->count() > 0 ? ($count / $orders->count()) * 100 : 0;
                                $total = $statusOrders->sum('total_price');
                            @endphp
                            <tr>
                                <td>
                                    <span class="status-badge status-{{ $status }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td class="text-center number">{{ $count }}</td>
                                <td class="text-center">{{ number_format($percentage, 1) }}%</td>
                                <td class="text-right currency">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-grid">
            <div class="footer-section">
                <h4>Informasi Laporan</h4>
                <p>Laporan ini dibuat secara otomatis dari sistem manajemen PT. Batam General Supplier.</p>
                <p><strong>Status Data:</strong> Real-time per {{ date('d F Y, H:i') }} WIB</p>
                <p><strong>Periode:</strong> {{ date('d F Y', strtotime($startDate)) }} - {{ date('d F Y', strtotime($endDate)) }}</p>
                <p><strong>Mata Uang:</strong> Rupiah (IDR)</p>
            </div>
            <div class="footer-section">
                <h4>Kontak & Support</h4>
                <p><strong>Alamat:</strong> Jl. Raya Batam Center No. 123, Batam</p>
                <p><strong>Telepon:</strong> (0778) 123-456</p>
                <p><strong>Email:</strong> info@bgs.co.id</p>
                <p><strong>Website:</strong> www.bgs.co.id</p>
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-boxes">
                <div class="signature-box">
                    <div class="signature-title">Mengetahui,</div>
                    <div class="signature-line">General Manager</div>
                </div>
                <div class="signature-box">
                    <div class="signature-title">Dibuat oleh,</div>
                    <div class="signature-line">{{ auth()->user()->fullname ?? 'Admin System' }}</div>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px; font-size: 8px; color: #9ca3af;">
            <p>Dokumen ini bersifat rahasia dan hanya untuk keperluan internal perusahaan</p>
            <p>© {{ date('Y') }} PT. Batam General Supplier - All Rights Reserved</p>
        </div>
    </div>
</body>
</html>