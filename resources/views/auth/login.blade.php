<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <i class="bi bi-building display-4 text-primary"></i>
                        <h3 class="fw-bold mt-3">Login ke Akun Anda</h3>
                        <p class="text-muted">PT. Batam General Supplier</p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username *</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username') }}" 
                                       required 
                                       autofocus
                                       placeholder="Masukkan username">
                            </div>
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password *</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="Masukkan password">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="password-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </form>

                    <!-- Demo Accounts -->
                    <div class="mt-4">
                        <hr>
                        <h6 class="text-center text-muted mb-3">Demo Akun untuk Testing</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="card bg-light">
                                    <div class="card-body p-2">
                                        <small class="fw-bold">Admin</small><br>
                                        <small class="text-muted">admin / admin123</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-light">
                                    <div class="card-body p-2">
                                        <small class="fw-bold">Customer</small><br>
                                        <small class="text-muted">customer1 / customer123</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Belum punya akun?</strong><br>
                            Silakan hubungi admin untuk mendapatkan akun baru:
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="https://wa.me/6281234567890?text=Halo, saya ingin mendaftar akun baru untuk e-katalog PT. Batam General Supplier" 
                               class="btn btn-success flex-fill btn-sm" target="_blank">
                                <i class="bi bi-whatsapp"></i> WhatsApp Admin
                            </a>
                            <a href="mailto:admin@batamgeneralsupplier.com?subject=Permintaan Akun Baru" 
                               class="btn btn-outline-primary flex-fill btn-sm">
                                <i class="bi bi-envelope"></i> Email Admin
                            </a>
                        </div>
                    </div>

                    <!-- Back to Home -->
                    <div class="text-center mt-3">
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordIcon.className = 'bi bi-eye-slash';
    } else {
        passwordField.type = 'password';
        passwordIcon.className = 'bi bi-eye';
    }
}

// Auto-fill demo accounts
function fillDemo(type) {
    if (type === 'admin') {
        document.getElementById('username').value = 'admin';
        document.getElementById('password').value = 'admin123';
    } else {
        document.getElementById('username').value = 'customer1';
        document.getElementById('password').value = 'customer123';
    }
}

// Add click handlers for demo cards
document.addEventListener('DOMContentLoaded', function() {
    const demoCards = document.querySelectorAll('.card.bg-light');
    demoCards[0].style.cursor = 'pointer';
    demoCards[1].style.cursor = 'pointer';
    
    demoCards[0].addEventListener('click', () => fillDemo('admin'));
    demoCards[1].addEventListener('click', () => fillDemo('customer'));
});
</script>
@endsection