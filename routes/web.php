<?php

// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [HomeController::class, 'catalog'])->name('catalog');
Route::get('/product/{id}', [HomeController::class, 'product'])->name('product.detail');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Routes (Protected)
Route::middleware(['auth', 'customer'])->prefix('customer')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    
    // Cart Management
    Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('customer.cart.add');
    Route::get('/cart', [CustomerController::class, 'showCart'])->name('customer.cart');
    Route::delete('/cart/{productId}', [CustomerController::class, 'removeFromCart'])->name('customer.cart.remove');
    
    // Checkout & Orders
    Route::post('/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
    Route::get('/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/orders/{id}', [CustomerController::class, 'showOrder'])->name('customer.order.show');
    // Customer Routes - Add to existing customer middleware group
    Route::post('/orders/{id}/payment', [CustomerController::class, 'submitPayment'])->name('customer.payment.submit');

    
    // Profile
    Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
});

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Product Management
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    
    // Category Management
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
    
    // Order Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    Route::put('/orders/{id}/confirmation', [AdminController::class, 'updateOrderConfirmation'])->name('admin.orders.confirmation');
    
    // Customer Management
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::post('/customers', [AdminController::class, 'storeCustomer'])->name('admin.customers.store');
    Route::get('/customers/{id}', [AdminController::class, 'showCustomer'])->name('admin.customers.show');
    Route::get('/customers/{id}/edit', [AdminController::class, 'editCustomer'])->name('admin.customers.edit');
    Route::put('/customers/{id}', [AdminController::class, 'updateCustomer'])->name('admin.customers.update');
    Route::delete('/customers/{id}', [AdminController::class, 'destroyCustomer'])->name('admin.customers.destroy');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/sales/export', [AdminController::class, 'exportSalesReport'])->name('admin.reports.export');
    Route::get('/admin/reports/refresh', [AdminController::class, 'refreshReports'])
    ->name('admin.reports.refresh');

    // Add these routes to your existing admin middleware group in routes/web.php
    
    // Contact Management
    Route::get('/contacts', [AdminController::class, 'contacts'])->name('admin.contacts');
    Route::put('/contacts', [AdminController::class, 'updateContact'])->name('admin.contacts.update');

    // Admin Routes - Add to existing admin middleware group  
    Route::put('/payments/{id}/status', [AdminController::class, 'updatePaymentStatus'])->name('admin.payments.status');
    Route::post('/orders/{id}/payment', [AdminController::class, 'addPayment'])->name('admin.payments.add');
    Route::put('/orders/{id}/confirmation', [AdminController::class, 'updateOrderConfirmation'])->name('admin.orders.confirmation');

    // Additional API Routes for AJAX calls
    Route::get('/api/customer/{id}', function($id) {
        $customer = \App\Models\User::where('role', 'customer')
                                    ->withCount('orders')
                                    ->with(['orders' => function($query) {
                                        $query->latest()->limit(5);
                                    }])
                                    ->findOrFail($id);
        return response()->json($customer);
    });

    Route::get('/api/dashboard-stats', [AdminController::class, 'getDashboardStats']);
});

// API Routes for AJAX calls
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/products/{id}', function($id) {
        return response()->json(\App\Models\Product::findOrFail($id));
    });
    
    Route::get('/cart-count', function() {
        $cart = session()->get('cart', []);
        return response()->json(['count' => count($cart)]);
    });
});