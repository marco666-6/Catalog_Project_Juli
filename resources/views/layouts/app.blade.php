<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'E-Katalog') - PT. Batam General Supplier</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #ec4899;
            --primary-dark: #be185d;
            --primary-light: #f472b6;
            --secondary: #8b5cf6;
            --secondary-dark: #7c3aed;
            --accent: #a855f7;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --success: #10b981;
            --light: #f8fafc;
            --dark: #1f2937;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* .container {
            width: 110% !important;
        } */

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
            color: var(--gray-700);
            min-height: 100vh;
        }

        /* Navigation Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            box-shadow: var(--shadow-lg);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
        }

        .navbar-brand i {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2rem;
        }

        .navbar-nav .nav-link {
            color: var(--gray-600) !important;
            font-weight: 600;
            padding: 0.75rem 1.25rem !important;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin: 0 0.25rem;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary) !important;
            background: rgba(236, 72, 153, 0.1);
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link.active {
            color: var(--primary) !important;
            background: rgba(236, 72, 153, 0.15);
        }

        /* Search Form */
        .search-form {
            position: relative;
        }

        .search-form .input-group {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .search-form .form-control {
            border: none;
            padding: 0.875rem 1.25rem;
            font-weight: 500;
            background: white;
        }

        .search-form .form-control:focus {
            border: none;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15);
        }

        .search-form .btn {
            border: none;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 0.875rem 1.25rem;
        }

        .search-form .btn:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
            transform: scale(1.05);
        }

        /* Cart Badge */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            box-shadow: var(--shadow);
        }

        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            text-decoration: none;
            color: inherit;
        }

        .user-profile:hover {
            background: var(--gray-100);
            border-color: var(--primary);
            color: inherit;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .user-avatar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 100%);
        }

        .avatar-letter {
            color: white;
            font-weight: 700;
            font-size: 0.875rem;
            z-index: 1;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--gray-500);
            line-height: 1.2;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-xl);
            padding: 0.75rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dropdown-item:hover {
            background: var(--gray-100);
            transform: translateX(4px);
        }

        .dropdown-item.text-danger:hover {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            border-left: 4px solid var(--success);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
            border-left: 4px solid var(--danger);
            color: #7f1d1d;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            padding: 1.5rem;
            border-radius: 20px 20px 0 0;
        }

        .card-header h5 {
            color: var(--primary);
            font-weight: 700;
            margin: 0;
        }

        /* Button Styles */
        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info) 0%, #1d4ed8 100%);
            color: white;
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        /* Feature Icon */
        .feature-icon {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            box-shadow: var(--shadow-lg);
        }

        /* Product Card */
        .product-card {
            transition: all 0.4s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .product-image {
            height: 250px;
            object-fit: cover;
            border-radius: 16px 16px 0 0;
        }

        .price-text {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.25rem;
        }

        /* Status Badge */
        .status-badge {
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
        }

        /* Table Styles */
        .table {
            border-radius: 16px;
            overflow: hidden;
        }

        .table th {
            background: var(--gray-50);
            border: none;
            font-weight: 700;
            color: var(--gray-700);
            padding: 1rem;
        }

        .table td {
            border: none;
            padding: 1rem;
            border-bottom: 1px solid var(--gray-100);
        }

        /* Badge Styles */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        /* Footer */
        .footer {
            background: linear-gradient(180deg, var(--gray-900) 0%, var(--gray-800) 100%);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer h5, .footer h6 {
            color: white;
            font-weight: 700;
        }

        .footer .text-muted {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: var(--primary-light);
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }

        /* Form Controls */
        .form-control, .form-select {
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.15);
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }

            .user-info {
                display: none;
            }

            .search-form {
                margin: 0.5rem 0;
            }

            .hero-section {
                padding: 2rem 0;
            }

            .feature-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
        }

        /* Additional utility classes */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, var(--info) 0%, #1d4ed8 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-building"></i>
                <span>PT. Batam General Supplier</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('catalog') ? 'active' : '' }}" href="{{ route('catalog') }}">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a>
                    </li>
                </ul>

                <!-- Search Form -->
                <form class="d-flex me-3 search-form" method="GET" action="{{ route('search') }}">
                    <div class="input-group">
                        <input class="form-control" type="search" name="q" placeholder="Cari produk..." value="{{ request('q') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav">
                    @auth
                        @if(auth()->user()->isCustomer())
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="{{ route('customer.cart') }}">
                                    <i class="bi bi-cart3"></i> Keranjang
                                    <span class="cart-badge" id="cart-count">0</span>
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-profile" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    <span class="avatar-letter">{{ substr(auth()->user()->fullname, 0, 1) }}</span>
                                </div>
                                <div class="user-info">
                                    <div class="user-name">{{ auth()->user()->fullname }}</div>
                                    <div class="user-role">{{ auth()->user()->role }}</div>
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Dashboard Admin
                                    </a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('customer.orders') }}">
                                        <i class="bi bi-bag-check"></i> Pesanan Saya
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('customer.profile') }}">
                                        <i class="bi bi-person-gear"></i> Profile
                                    </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="container">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="bi bi-building me-2"></i>PT. Batam General Supplier</h5>
                    <p class="text-muted">Menyediakan berbagai kebutuhan industri, kantor, dan rumah tangga dengan kualitas terbaik dan pelayanan memuaskan.</p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-whatsapp"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-envelope"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6>Navigasi</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-muted">Beranda</a></li>
                        <li><a href="{{ route('catalog') }}" class="text-muted">Katalog</a></li>
                        <li><a href="{{ route('contact') }}" class="text-muted">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6>Kontak Kami</h6>
                    <ul class="list-unstyled text-muted">
                        <li><i class="bi bi-geo-alt me-2"></i>Jl. Industrial Estate, Batam</li>
                        <li><i class="bi bi-telephone me-2"></i>(0778) 123-4567</li>
                        <li><i class="bi bi-envelope me-2"></i>info@batamgeneralsupplier.com</li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6>Jam Operasional</h6>
                    <ul class="list-unstyled text-muted">
                        <li>Senin - Jumat: 08:00 - 17:00</li>
                        <li>Sabtu: 08:00 - 12:00</li>
                        <li>Minggu: Tutup</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; {{ date('Y') }} PT. Batam General Supplier. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">Developed with <i class="bi bi-heart-fill text-danger"></i> using Laravel & Bootstrap</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // CSRF Token Setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Update cart count
        function updateCartCount() {
            $.get('/api/cart-count', function(data) {
                $('#cart-count').text(data.count);
                if (data.count > 0) {
                    $('#cart-count').show();
                } else {
                    $('#cart-count').hide();
                }
            });
        }

        // Add to cart function
        function addToCart(productId, quantity = 1) {
            $.post('/customer/cart/add', {
                product_id: productId,
                quantity: quantity
            }, function(response) {
                if (response.success) {
                    updateCartCount();
                    showAlert('success', response.message);
                } else {
                    showAlert('danger', response.message);
                }
            }).fail(function() {
                showAlert('danger', 'Terjadi kesalahan. Silakan coba lagi.');
            });
        }

        // Show alert function
        function showAlert(type, message) {
            const alertHtml = `
                <div class="container">
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            `;
            $('main').prepend(alertHtml);
            
            // Auto dismiss after 3 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 3000);
        }

        // Initialize on page load
        $(document).ready(function() {
            @auth
                @if(auth()->user()->isCustomer())
                    updateCartCount();
                @endif
            @endauth

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });

        // Search functionality
        $('.search-form input[name="q"]').on('keyup', function() {
            const query = $(this).val();
            if (query.length > 2) {
                // Implement live search if needed
            }
        });

        // Quantity input validation
        $(document).on('change', 'input[type="number"]', function() {
            const min = parseInt($(this).attr('min'));
            const max = parseInt($(this).attr('max'));
            let val = parseInt($(this).val());

            if (val < min) {
                $(this).val(min);
            } else if (max && val > max) {
                $(this).val(max);
            }
        });

        // Confirm delete actions
        $(document).on('click', '.btn-delete', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                e.preventDefault();
            }
        });

        // Loading state for forms
        $(document).on('submit', 'form', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).addClass('loading');
        });
    </script>

    @stack('scripts')
</body>
</html>