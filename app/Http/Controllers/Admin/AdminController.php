<?php

// app/Http/Controllers/Admin/AdminController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Contact;
use App\Models\Report;
use App\Models\SalesReportExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        
        $recentOrders = Order::with('user')->latest()->limit(10)->get();
        $lowStockProducts = Product::where('stock', '<=', 5)->get();
        
        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'totalCustomers', 'pendingOrders', 'completedOrders',
            'recentOrders', 'lowStockProducts'
        ));
    }

    // Product Management
    public function products(Request $request)
    {
        $query = Product::with('category');

        // Typing filter (search by name or description)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->status && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        // Stock filter
        if ($request->stock === 'low') {
            $query->where('stock', '<=', 5);
        }

        $products = $query->paginate(15)->appends($request->query());

        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'price', 'description', 'stock', 'category_id']);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = basename($imagePath);
        }

        Product::create($data);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'price', 'description', 'stock', 'category_id']);
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete('products/' . $product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = basename($imagePath);
        }

        $product->update($data);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diupdate');
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete image
        if ($product->image) {
            Storage::disk('public')->delete('products/' . $product->image);
        }
        
        $product->forceDelete();

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus');
    }

    // Category Management
    public function categories(Request $request)
    {
        $query = Category::withCount('products');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort filter
        $sort = $request->input('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'products_desc':
                $query->orderBy('products_count', 'desc');
                break;
            case 'products_asc':
                $query->orderBy('products_count', 'asc');
                break;
            case 'name_asc':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $categories = $query->paginate(15)->appends($request->query());

        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000'
        ]);

        Category::create($request->only(['name', 'description']));

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id . ',category_id',
            'description' => 'nullable|string|max:1000'
        ]);

        $category->update($request->only(['name', 'description']));

        return redirect()->back()->with('success', 'Kategori berhasil diupdate');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk');
        }
        
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }

    // Order Management
    public function orders(Request $request)
    {
        $query = Order::with('user', 'orderDetails');

        // Search filter (by order number or customer name)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('fullname', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Payment method filter
        if ($request->filled('payment_method') && $request->payment_method !== '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Date range filter
        if ($request->filled('date_range') && $request->date_range !== '') {
            $now = now();
            
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('order_date', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('order_date', [
                        $now->startOfWeek()->toDateTimeString(),
                        $now->endOfWeek()->toDateTimeString()
                    ]);
                    break;
                case 'month':
                    $query->whereBetween('order_date', [
                        $now->startOfMonth()->toDateTimeString(),
                        $now->endOfMonth()->toDateTimeString()
                    ]);
                    break;
            }
        }

        // Order by latest
        $orders = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = Order::with('user', 'orderDetails.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diupdate');
    }

    public function updateOrderConfirmation(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'confirmation' => 'nullable|string|max:1000'
        ]);

        $order->update(['confirmation' => $request->confirmation]);

        return redirect()->back()->with('success', 'Catatan pesanan berhasil diupdate');
    }

    // Payment Management Methods for AdminController
    public function updatePaymentStatus(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $payment->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        // Update order status if payment is approved and fully paid
        if ($request->status === 'approved') {
            $order = $payment->order;
            $totalApprovedPayments = $order->payments()->approved()->sum('amount_paid');
            
            if ($totalApprovedPayments >= $order->total_price) {
                $order->update(['status' => 'confirmed']);
            }
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diupdate');
    }

    public function addPayment(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $totalPaid = $order->payments()->approved()->sum('amount_paid');
        $remainingBalance = max(0, $order->total_price - $totalPaid - $request->amount_paid);

        // Handle file upload
        $fileName = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof')->store('payments', 'public');;
            $fileName = basename($file);
        }
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('products', 'public');
        //     $data['image'] = basename($imagePath);
        // }

        Payment::create([
            'order_id' => $order->order_id,
            'amount_paid' => $request->amount_paid,
            'payment_date' => now(),
            'remaining_balance' => $remainingBalance,
            'payment_proof' => $fileName,
            'status' => 'approved', // Admin adding payment directly
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan');
    }

    // Customer Management Methods for AdminController
    public function customers(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders');

        // Search filter (by name, username, or email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                ->orWhere('firstname', 'like', "%{$search}%")
                ->orWhere('lastname', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter (for future use if you add status field)
        if ($request->filled('status') && $request->status !== 'all') {
            // You can add status filtering logic here if needed
            // $query->where('status', $request->status);
        }

        // Orders filter
        if ($request->filled('orders')) {
            if ($request->orders === 'with_orders') {
                $query->has('orders');
            } elseif ($request->orders === 'no_orders') {
                $query->doesntHave('orders');
            }
        }

        // Sort filter
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('fullname', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('fullname', 'desc');
                    break;
                case 'orders_desc':
                    $query->orderBy('orders_count', 'desc');
                    break;
                case 'orders_asc':
                    $query->orderBy('orders_count', 'asc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $customers = $query->paginate(15)->appends($request->query());

        return view('admin.customers.index', compact('customers'));
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6',
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'fullname' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'shipaddress' => 'nullable|string|max:1000'
        ]);

        $data = $request->only([
            'username', 'email', 'password', 'firstname', 
            'lastname', 'fullname', 'phone', 'shipaddress'
        ]);
        
        $data['role'] = 'customer';
        $data['level'] = '1';

        User::create($data);

        return redirect()->route('admin.customers')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function showCustomer($id)
    {
        $customer = User::where('role', 'customer')
                    ->withCount('orders')
                    ->with(['orders' => function($query) {
                        $query->latest()->limit(5);
                    }])
                    ->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    public function editCustomer($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        
        $request->validate([
            'username' => 'required|string|unique:users,username,' . $id . ',user_id|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',user_id|max:255',
            'password' => 'nullable|string|min:6',
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'fullname' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'shipaddress' => 'nullable|string|max:1000'
        ]);

        $data = $request->only([
            'username', 'email', 'firstname', 
            'lastname', 'fullname', 'phone', 'shipaddress'
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $customer->update($data);

        return redirect()->route('admin.customers')->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function destroyCustomer($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        
        // Check if customer has orders
        if ($customer->orders()->count() > 0) {
            return redirect()->route('admin.customers')->with('error', 'Pelanggan tidak dapat dihapus karena memiliki riwayat order');
        }
        
        $customer->delete();

        return redirect()->route('admin.customers')->with('success', 'Pelanggan berhasil dihapus');
    }

    // Enhanced Reports Methods for AdminController
    public function reports()
    {
        // Basic statistics
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        
        // Sales data
        $totalSales = Order::where('status', 'delivered')->sum('total_price');
        
        // Monthly sales data for chart
        $monthlyData = Order::where('status', 'delivered')
            ->selectRaw('MONTH(order_date) as month, SUM(total_price) as total, COUNT(*) as orders_count')
            ->whereYear('order_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => $item->month,
                    'total' => $item->total,
                    'orders_count' => $item->orders_count
                ];
            });

        // Fill missing months with zero
        $monthlyDataComplete = collect(range(1, 12))->map(function($month) use ($monthlyData) {
            $existing = $monthlyData->firstWhere('month', $month);
            return [
                'month' => $month,
                'total' => $existing ? $existing['total'] : 0,
                'orders_count' => $existing ? $existing['orders_count'] : 0
            ];
        });
            
        // Top selling products
        $topProducts = Product::with('category')
            ->withSum('orderDetails', 'quantity')
            ->having('order_details_sum_quantity', '>', 0)
            ->orderByDesc('order_details_sum_quantity')
            ->limit(10)
            ->get();

        // Recent orders
        $recentOrders = Order::with('user')->latest()->limit(10)->get();

        // Order status distribution
        $orderStatusData = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Low stock products
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        // Customer statistics
        $newCustomersThisMonth = User::where('role', 'customer')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        // Revenue comparison
        $thisMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('order_date', date('m'))
            ->whereYear('order_date', date('Y'))
            ->sum('total_price');
            
        $lastMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('order_date', date('m', strtotime('-1 month')))
            ->whereYear('order_date', date('Y', strtotime('-1 month')))
            ->sum('total_price');

        // Weekly sales data for chart
        $weeklyData = Order::where('status', 'delivered')
            ->selectRaw('WEEK(order_date) as week, SUM(total_price) as total, COUNT(*) as orders_count')
            ->whereYear('order_date', date('Y'))
            ->groupBy('week')
            ->orderBy('week')
            ->get()
            ->map(function($item) {
                return [
                    'week' => $item->week,
                    'total' => $item->total,
                    'orders_count' => $item->orders_count
                ];
            });
        
        // Fill missing weeks with zero
        $currentWeek = date('W');
        $weeklyDataComplete = collect(range(1, $currentWeek))->map(function($week) use ($weeklyData) {
            $existing = $weeklyData->firstWhere('week', $week);
            return [
                'week' => $week,
                'total' => $existing ? $existing['total'] : 0,
                'orders_count' => $existing ? $existing['orders_count'] : 0
            ];
        });
        
        // Daily sales data for current month
        $daysInMonth = date('t');
        $dailyData = Order::where('status', 'delivered')
            ->selectRaw('DAY(order_date) as day, SUM(total_price) as total, COUNT(*) as orders_count')
            ->whereMonth('order_date', date('m'))
            ->whereYear('order_date', date('Y'))
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(function($item) {
                return [
                    'day' => $item->day,
                    'total' => $item->total,
                    'orders_count' => $item->orders_count
                ];
            });
        
        // Fill missing days with zero
        $dailyDataComplete = collect(range(1, $daysInMonth))->map(function($day) use ($dailyData) {
            $existing = $dailyData->firstWhere('day', $day);
            return [
                'day' => $day,
                'total' => $existing ? $existing['total'] : 0,
                'orders_count' => $existing ? $existing['orders_count'] : 0
            ];
        });

        $revenueGrowth = $lastMonthRevenue > 0 ? 
            (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        return view('admin.reports.index', compact(
            'totalProducts', 'totalOrders', 'totalCustomers', 'pendingOrders', 'completedOrders',
            'totalSales', 'monthlyData', 'monthlyDataComplete', 'topProducts', 'recentOrders',
            'orderStatusData', 'lowStockProducts', 'newCustomersThisMonth',
            'thisMonthRevenue', 'lastMonthRevenue', 'revenueGrowth', 'weeklyDataComplete', 'dailyDataComplete', 'daysInMonth', 'currentWeek'
        ));
    }

    public function exportSalesReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel',
            'status' => 'nullable|array'
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $format = $request->input('format', 'pdf');
        $statuses = $request->input('status', ['delivered']);

        $query = Order::with('user', 'orderDetails.product.category')
            ->whereBetween('order_date', [$startDate, $endDate]);

        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        $orders = $query->get();

        // Calculate summary data
        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_price'),
            'average_order_value' => $orders->count() > 0 ? $orders->avg('total_price') : 0,
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ];

        if ($format === 'excel') {
            return Excel::download(
                new SalesReportExport($orders, $summary, $startDate, $endDate), 
                'laporan-penjualan-' . date('Y-m-d') . '.xlsx'
            );
        } else {
            $pdf = Pdf::loadView('admin.reports.sales-pdf', compact('orders', 'summary', 'startDate', 'endDate'))
                ->setPaper('a4', 'portrait')
                ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
                
            return $pdf->download('laporan-penjualan-' . date('Y-m-d') . '.pdf');
        }
    }

    public function refreshReports()
    {
        try {
            // You can add any specific data refresh logic here
            // For now, we'll just return success
            
            return response()->json([
                'success' => true,
                'message' => 'Data refreshed successfully',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh data: ' . $e->getMessage()
            ], 500);
        }
    }

    // Additional utility methods for reports
    public function getDashboardStats()
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        
        return [
            'today_orders' => Order::whereDate('order_date', $today)->count(),
            'today_revenue' => Order::whereDate('order_date', $today)->where('status', 'delivered')->sum('total_price'),
            'month_orders' => Order::whereDate('order_date', '>=', $thisMonth)->count(),
            'month_revenue' => Order::whereDate('order_date', '>=', $thisMonth)->where('status', 'delivered')->sum('total_price'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::whereIn('status', ['confirmed', 'processing', 'shipped'])->count(),
        ];
    }

    // Contact Management Methods for AdminController
    // Add these methods to your existing AdminController class

    public function contacts()
    {
        $contact = Contact::getActive();
        
        // Create default contact if none exists
        if (!$contact) {
            $contact = Contact::create([
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
        
        return view('admin.contacts.index', compact('contact'));
    }

    public function updateContact(Request $request)
    {
        $contact = Contact::getActive();
        
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'phone_primary' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'email_primary' => 'required|email|max:255',
            'email_secondary' => 'nullable|email|max:255',
            'whatsapp' => 'required|string|max:20',
            'operational_hours' => 'required|array',
            'operational_hours.*.open' => 'required_if:operational_hours.*.is_open,true|string',
            'operational_hours.*.close' => 'required_if:operational_hours.*.is_open,true|string', 
            'operational_hours.*.is_open' => 'required|boolean'
        ]);

        $data = $request->only([
            'company_name', 'address', 'phone_primary', 'phone_secondary',
            'email_primary', 'email_secondary', 'whatsapp'
        ]);
        
        // Process operational hours
        $operationalHours = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        foreach ($days as $day) {
            $operationalHours[$day] = [
                'open' => $request->input("operational_hours.{$day}.open", '00:00'),
                'close' => $request->input("operational_hours.{$day}.close", '00:00'),
                'is_open' => (bool) $request->input("operational_hours.{$day}.is_open", false)
            ];
        }
        
        $data['operational_hours'] = $operationalHours;

        if ($contact) {
            $contact->update($data);
        } else {
            $data['is_active'] = true;
            Contact::create($data);
        }

        return redirect()->route('admin.contacts')->with('success', 'Informasi kontak berhasil diperbarui');
    }
}