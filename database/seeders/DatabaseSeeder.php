<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Report;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Users
        $this->seedUsers();
        
        // Seed Categories
        $this->seedCategories();
        
        // Seed Products
        $this->seedProducts();
        
        // Seed Orders with OrderDetails
        $this->seedOrders();
        
        // Seed Payments
        $this->seedPayments();
        
        // Seed Reports
        $this->seedReports();

        Contact::create([
            'company_name' => 'PT. Batam General Supplier',
            'address' => 'Jl. Industrial Estate Blok A No. 15, Batam Centre, Batam 29461, Kepulauan Riau, Indonesia',
            'phone_primary' => '0778123456',
            'phone_secondary' => '081234567890',
            'email_primary' => 'info@batamgeneralsupplier.com',
            'email_secondary' => 'sales@batamgeneralsupplier.com',
            'whatsapp' => '6281234567890',
            'operational_hours' => Contact::getDefaultOperationalHours(),
            'is_active' => true
        ]);
    }

    private function seedUsers()
    {
        // Admin User
        User::create([
            'username' => 'admin',
            'password' => 'admin123',
            'fullname' => 'Administrator System',
            'role' => 'admin',
            'level' => 5,
            'email' => 'admin@batamgeneralsupplier.com',
            'firstname' => 'Administrator',
            'lastname' => 'System',
            'phone' => '+62778123456',
            'shipaddress' => 'Jl. Sudirman No. 123, Batam Center, Batam',
            'email_verified_at' => Carbon::now(),
        ]);

        // Super Admin
        User::create([
            'username' => 'superadmin',
            'password' => 'super123',
            'fullname' => 'Super Administrator',
            'role' => 'admin',
            'level' => 10,
            'email' => 'superadmin@batamgeneralsupplier.com',
            'firstname' => 'Super',
            'lastname' => 'Administrator',
            'phone' => '+62778123457',
            'shipaddress' => 'Jl. Ahmad Yani No. 456, Nagoya, Batam',
            'email_verified_at' => Carbon::now(),
        ]);

        // Customer Users
        User::create([
            'username' => 'customer1',
            'password' => 'customer123',
            'fullname' => 'John Doe',
            'role' => 'customer',
            'level' => 1,
            'email' => 'john.doe@email.com',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'phone' => '+62812345678',
            'shipaddress' => 'Jl. Merdeka No. 789, Batu Ampar, Batam',
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'username' => 'customer2',
            'password' => 'customer123',
            'fullname' => 'Jane Smith',
            'role' => 'customer',
            'level' => 1,
            'email' => 'jane.smith@email.com',
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'phone' => '+62823456789',
            'shipaddress' => 'Jl. Kartini No. 321, Sekupang, Batam',
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'username' => 'customer3',
            'password' => 'customer123',
            'fullname' => 'Ahmad Rahman',
            'role' => 'customer',
            'level' => 1,
            'email' => 'ahmad.rahman@email.com',
            'firstname' => 'Ahmad',
            'lastname' => 'Rahman',
            'phone' => '+62834567890',
            'shipaddress' => 'Jl. Diponegoro No. 654, Tiban, Batam',
            'email_verified_at' => Carbon::now(),
        ]);
    }

    private function seedCategories()
    {
        $categories = [
            [
                'name' => 'Office Supplies',
                'description' => 'Perlengkapan kantor dan alat tulis'
            ],
            [
                'name' => 'Electronics',
                'description' => 'Peralatan elektronik dan komputer'
            ],
            [
                'name' => 'Furniture',
                'description' => 'Furnitur kantor dan rumah'
            ],
            [
                'name' => 'Tools & Equipment',
                'description' => 'Peralatan dan mesin industri'
            ],
            [
                'name' => 'Safety Equipment',
                'description' => 'Alat pelindung diri dan keselamatan kerja'
            ],
            [
                'name' => 'Cleaning Supplies',
                'description' => 'Perlengkapan kebersihan dan sanitasi'
            ],
            [
                'name' => 'Construction Materials',
                'description' => 'Bahan bangunan dan konstruksi'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }

    private function seedProducts()
    {
        $products = [
            // Office Supplies
            [
                'name' => 'Kertas A4 80gsm',
                'price' => 45000,
                'description' => 'Kertas A4 putih berkualitas tinggi, cocok untuk fotokopi dan printer',
                'stock' => 500,
                'category_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Ballpoint Pilot',
                'price' => 5000,
                'description' => 'Pulpen ballpoint biru dengan tinta berkualitas',
                'stock' => 200,
                'category_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Folder L Plastik',
                'price' => 8000,
                'description' => 'Folder L transparan untuk menyimpan dokumen',
                'stock' => 150,
                'category_id' => 1,
                'is_active' => true,
            ],

            // Electronics
            [
                'name' => 'Laptop Asus VivoBook',
                'price' => 7500000,
                'description' => 'Laptop dengan processor Intel i5, RAM 8GB, SSD 512GB',
                'stock' => 25,
                'category_id' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Mouse Wireless Logitech',
                'price' => 250000,
                'description' => 'Mouse wireless ergonomis dengan baterai tahan lama',
                'stock' => 80,
                'category_id' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Keyboard Mechanical',
                'price' => 850000,
                'description' => 'Keyboard mechanical dengan backlight RGB',
                'stock' => 35,
                'category_id' => 2,
                'is_active' => true,
            ],

            // Furniture
            [
                'name' => 'Kursi Kantor Ergonomis',
                'price' => 1200000,
                'description' => 'Kursi kantor dengan sandaran punggung dan lengan yang dapat disesuaikan',
                'stock' => 40,
                'category_id' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Meja Kerja L-Shape',
                'price' => 2500000,
                'description' => 'Meja kerja bentuk L dengan laci dan rak penyimpanan',
                'stock' => 15,
                'category_id' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Lemari Arsip 4 Laci',
                'price' => 1800000,
                'description' => 'Lemari arsip besi dengan 4 laci dan kunci',
                'stock' => 20,
                'category_id' => 3,
                'is_active' => true,
            ],

            // Tools & Equipment
            [
                'name' => 'Bor Listrik Bosch',
                'price' => 650000,
                'description' => 'Bor listrik dengan berbagai mata bor',
                'stock' => 30,
                'category_id' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Generator 2000W',
                'price' => 3500000,
                'description' => 'Generator portable untuk backup listrik',
                'stock' => 10,
                'category_id' => 4,
                'is_active' => true,
            ],

            // Safety Equipment
            [
                'name' => 'Helm Safety Proyek',
                'price' => 85000,
                'description' => 'Helm pengaman untuk konstruksi dan industri',
                'stock' => 100,
                'category_id' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Sepatu Safety Steel Toe',
                'price' => 320000,
                'description' => 'Sepatu keselamatan dengan pelindung kaki baja',
                'stock' => 60,
                'category_id' => 5,
                'is_active' => true,
            ],

            // Cleaning Supplies
            [
                'name' => 'Sabun Cuci Piring 1L',
                'price' => 25000,
                'description' => 'Sabun cuci piring konsentrat aroma lemon',
                'stock' => 200,
                'category_id' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Deterjen Bubuk 5kg',
                'price' => 65000,
                'description' => 'Deterjen bubuk untuk mesin cuci',
                'stock' => 120,
                'category_id' => 6,
                'is_active' => true,
            ],

            // Construction Materials
            [
                'name' => 'Semen Portland 50kg',
                'price' => 75000,
                'description' => 'Semen berkualitas untuk konstruksi bangunan',
                'stock' => 300,
                'category_id' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Cat Tembok Avian 20L',
                'price' => 450000,
                'description' => 'Cat tembok interior dan eksterior warna putih',
                'stock' => 50,
                'category_id' => 7,
                'is_active' => true,
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }

    private function seedOrders()
    {
        // Order 1 - Completed
        $order1 = Order::create([
            'user_id' => 3, // customer1
            'order_date' => Carbon::now()->subDays(10),
            'status' => 'delivered',
            'installment_plan' => 'full_payment',
            'payment_method' => 'bank_transfer',
            'total_price' => 7750000,
            'confirmation' => 'Pesanan telah dikonfirmasi dan dikirim',
        ]);

        OrderDetail::create([
            'order_id' => $order1->order_id,
            'product_id' => 4, // Laptop Asus VivoBook
            'quantity' => 1,
            'price_at_purchase' => 7500000,
            'subtotal' => 7500000,
        ]);

        OrderDetail::create([
            'order_id' => $order1->order_id,
            'product_id' => 5, // Mouse Wireless
            'quantity' => 1,
            'price_at_purchase' => 250000,
            'subtotal' => 250000,
        ]);

        // Order 2 - Processing
        $order2 = Order::create([
            'user_id' => 4, // customer2
            'order_date' => Carbon::now()->subDays(5),
            'status' => 'processing',
            'installment_plan' => '3_months',
            'payment_method' => 'credit_card',
            'total_price' => 4000000,
            'confirmation' => 'Pesanan sedang dalam proses packaging',
        ]);

        OrderDetail::create([
            'order_id' => $order2->order_id,
            'product_id' => 7, // Kursi Kantor
            'quantity' => 1,
            'price_at_purchase' => 1200000,
            'subtotal' => 1200000,
        ]);

        OrderDetail::create([
            'order_id' => $order2->order_id,
            'product_id' => 9, // Lemari Arsip
            'quantity' => 1,
            'price_at_purchase' => 1800000,
            'subtotal' => 1800000,
        ]);

        OrderDetail::create([
            'order_id' => $order2->order_id,
            'product_id' => 11, // Generator
            'quantity' => 1,
            'price_at_purchase' => 3500000,
            'subtotal' => 3500000,
        ]);

        // Order 3 - Pending
        $order3 = Order::create([
            'user_id' => 5, // customer3
            'order_date' => Carbon::now()->subDays(2),
            'status' => 'pending',
            'installment_plan' => 'full_payment',
            'payment_method' => 'bank_transfer',
            'total_price' => 560000,
        ]);

        OrderDetail::create([
            'order_id' => $order3->order_id,
            'product_id' => 1, // Kertas A4
            'quantity' => 10,
            'price_at_purchase' => 45000,
            'subtotal' => 450000,
        ]);

        OrderDetail::create([
            'order_id' => $order3->order_id,
            'product_id' => 2, // Ballpoint
            'quantity' => 20,
            'price_at_purchase' => 5000,
            'subtotal' => 100000,
        ]);

        OrderDetail::create([
            'order_id' => $order3->order_id,
            'product_id' => 3, // Folder L
            'quantity' => 5,
            'price_at_purchase' => 8000,
            'subtotal' => 40000,
        ]);

        // Order 4 - Shipped
        $order4 = Order::create([
            'user_id' => 3, // customer1
            'order_date' => Carbon::now()->subDays(3),
            'status' => 'shipped',
            'installment_plan' => 'full_payment',
            'payment_method' => 'cash',
            'total_price' => 1170000,
            'confirmation' => 'Barang sudah dikirim via ekspedisi',
        ]);

        OrderDetail::create([
            'order_id' => $order4->order_id,
            'product_id' => 6, // Keyboard Mechanical
            'quantity' => 1,
            'price_at_purchase' => 850000,
            'subtotal' => 850000,
        ]);

        OrderDetail::create([
            'order_id' => $order4->order_id,
            'product_id' => 13, // Sepatu Safety
            'quantity' => 1,
            'price_at_purchase' => 320000,
            'subtotal' => 320000,
        ]);
    }

    private function seedPayments()
    {
        // Payment for Order 1 - Full Payment (Approved)
        Payment::create([
            'order_id' => 1,
            'amount_paid' => 7750000,
            'payment_date' => Carbon::now()->subDays(9),
            'remaining_balance' => 0,
            'payment_proof' => null, // Admin added payment, no proof needed
            'status' => 'approved',
            'admin_notes' => 'Pembayaran lunas langsung ke admin'
        ]);

        // Payment for Order 2 - First Installment (Approved)
        Payment::create([
            'order_id' => 2,
            'amount_paid' => 1333333,
            'payment_date' => Carbon::now()->subDays(4),
            'remaining_balance' => 2666667,
            'payment_proof' => 'payment_proof_1.jpg', // Sample filename
            'status' => 'approved',
            'admin_notes' => 'Cicilan pertama dari paket 3 bulan'
        ]);

        // Payment for Order 2 - Second Installment (Pending)
        Payment::create([
            'order_id' => 2,
            'amount_paid' => 1333333,
            'payment_date' => Carbon::now()->subDays(1),
            'remaining_balance' => 1333334,
            'payment_proof' => 'payment_proof_2.jpg', // Sample filename
            'status' => 'pending',
            'admin_notes' => null
        ]);

        // Payment for Order 4 - Full Payment (Approved)
        Payment::create([
            'order_id' => 4,
            'amount_paid' => 1170000,
            'payment_date' => Carbon::now()->subDays(2),
            'remaining_balance' => 0,
            'payment_proof' => 'payment_proof_3.jpg', // Sample filename
            'status' => 'approved',
            'admin_notes' => 'Pembayaran lunas via transfer bank'
        ]);
    }

    private function seedReports()
    {
        // Daily Sales Reports for the last 30 days
        for ($i = 30; $i >= 1; $i--) {
            $date = Carbon::now()->subDays($i);
            $totalSales = rand(500000, 10000000); // Random sales between 500k - 10M

            Report::create([
                'user_id' => 1, // Admin
                'total_sales' => $totalSales,
                'report_date' => $date,
            ]);
        }

        // Weekly summary reports
        for ($week = 4; $week >= 1; $week--) {
            $date = Carbon::now()->subWeeks($week);
            $totalSales = rand(15000000, 50000000); // Random weekly sales

            Report::create([
                'user_id' => 2, // Super Admin
                'total_sales' => $totalSales,
                'report_date' => $date,
            ]);
        }
    }
}