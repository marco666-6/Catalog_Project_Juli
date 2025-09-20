<?php

namespace App\Models;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class SalesReportExport implements WithMultipleSheets
{
    protected $orders;
    protected $summary;
    protected $startDate;
    protected $endDate;

    public function __construct($orders, $summary, $startDate, $endDate)
    {
        $this->orders = $orders;
        $this->summary = $summary;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            'Ringkasan' => new SummarySheet($this->orders, $this->summary, $this->startDate, $this->endDate),
            'Detail Transaksi' => new TransactionSheet($this->orders),
            'Produk Terjual' => new ProductSheet($this->orders),
        ];
    }
}

class SummarySheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $orders;
    protected $summary;
    protected $startDate;
    protected $endDate;

    public function __construct($orders, $summary, $startDate, $endDate)
    {
        $this->orders = $orders;
        $this->summary = $summary;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $data = collect([
            ['Periode Laporan', date('d F Y', strtotime($this->startDate)) . ' - ' . date('d F Y', strtotime($this->endDate))],
            ['Tanggal Cetak', date('d F Y, H:i:s') . ' WIB'],
            ['', ''],
            ['RINGKASAN PENJUALAN', ''],
            ['Total Order', $this->summary['total_orders'] ?? $this->orders->count()],
            ['Total Pendapatan', 'Rp ' . number_format($this->summary['total_revenue'] ?? $this->orders->sum('total_price'), 0, ',', '.')],
            ['Rata-rata per Order', 'Rp ' . number_format($this->summary['average_order_value'] ?? $this->orders->avg('total_price'), 0, ',', '.')],
            ['Order Selesai', $this->orders->where('status', 'delivered')->count()],
            ['Order Pending', $this->orders->where('status', 'pending')->count()],
            ['Order Dibatalkan', $this->orders->where('status', 'cancelled')->count()],
            ['', ''],
            ['ANALISIS STATUS ORDER', ''],
        ]);

        // Add status breakdown
        $statusCounts = $this->orders->groupBy('status')->map(function ($group) {
            return $group->count();
        });

        foreach ($statusCounts as $status => $count) {
            $data->push([ucfirst($status), $count]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'LAPORAN PENJUALAN - PT. BATAM GENERAL SUPPLIER',
            ''
        ];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '2563eb']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            4 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'e5e7eb']],
            ],
            12 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'e5e7eb']],
            ],
            'A' => ['font' => ['bold' => true]],
        ];
    }
}

class TransactionSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'No. Order',
            'Pelanggan',
            'Email',
            'Tanggal Order',
            'Status',
            'Metode Pembayaran',
            'Total Harga',
            'Jumlah Item',
            'Catatan'
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number ?? 'ORD-' . str_pad($order->order_id, 5, '0', STR_PAD_LEFT),
            $order->user->fullname ?? $order->user->firstname . ' ' . $order->user->lastname ?? 'N/A',
            $order->user->email ?? 'N/A',
            $order->order_date ? $order->order_date->format('d/m/Y H:i') : 'N/A',
            ucfirst($order->status ?? 'pending'),
            $order->payment_method ?? 'N/A',
            $order->total_price ?? 0,
            $order->orderDetails->count() ?? 0,
            $order->confirmation ?? '-'
        ];
    }

    public function title(): string
    {
        return 'Detail Transaksi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'ffffff']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2563eb']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],
            'G' => ['numberFormat' => ['formatCode' => '#,##0']],
        ];
    }
}

class ProductSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        $productSales = collect();
        $productData = [];

        foreach ($this->orders as $order) {
            foreach ($order->orderDetails as $detail) {
                $productId = $detail->product_id;
                if (!isset($productData[$productId])) {
                    $productData[$productId] = [
                        'name' => $detail->product->name ?? 'Produk Tidak Ditemukan',
                        'category' => $detail->product->category->name ?? 'Tidak Ada Kategori',
                        'price' => $detail->product->price ?? 0,
                        'quantity' => 0,
                        'subtotal' => 0,
                        'orders_count' => 0
                    ];
                }
                $productData[$productId]['quantity'] += $detail->quantity ?? 0;
                $productData[$productId]['subtotal'] += ($detail->product->price ?? 0) * ($detail->quantity ?? 0);
                $productData[$productId]['orders_count']++;
            }
        }

        // Sort by quantity desc and convert to collection
        uasort($productData, function ($a, $b) {
            return $b['quantity'] <=> $a['quantity'];
        });

        foreach ($productData as $data) {
            $productSales->push([
                'name' => $data['name'],
                'category' => $data['category'],
                'price' => $data['price'],
                'quantity' => $data['quantity'],
                'subtotal' => $data['subtotal'],
                'orders_count' => $data['orders_count']
            ]);
        }

        return $productSales;
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Kategori',
            'Harga Satuan',
            'Jumlah Terjual',
            'Total Pendapatan',
            'Jumlah Order'
        ];
    }

    public function title(): string
    {
        return 'Produk Terjual';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'ffffff']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '059669']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],
            'C' => ['numberFormat' => ['formatCode' => '#,##0']],
            'E' => ['numberFormat' => ['formatCode' => '#,##0']],
        ];
    }
}