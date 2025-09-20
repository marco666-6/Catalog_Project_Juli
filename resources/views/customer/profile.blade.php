<!-- resources/views/customer/profile.blade.php -->
@extends('layouts.app')

@section('title', 'Profile Customer')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold"><i class="bi bi-person-gear"></i> Profile Saya</h2>
            <p class="text-muted">Kelola informasi personal dan alamat pengiriman Anda</p>
        </div>
    </div>

    <div class="row">
        <!-- Profile Form -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Informasi Personal</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstname" class="form-label">Nama Depan *</label>
                                <input type="text" class="form-control @error('firstname') is-invalid @enderror" 
                                       id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" required>
                                @error('firstname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastname" class="form-label">Nama Belakang *</label>
                                <input type="text" class="form-control @error('lastname') is-invalid @enderror" 
                                       id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" required>
                                @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror" 
                                   id="fullname" name="fullname" value="{{ old('fullname', $user->fullname) }}" readonly>
                            @error('fullname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                       placeholder="+62 812-3456-7890">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="shipaddress" class="form-label">Alamat Pengiriman</label>
                            <textarea class="form-control @error('shipaddress') is-invalid @enderror" 
                                      id="shipaddress" name="shipaddress" rows="4" 
                                      placeholder="Masukkan alamat lengkap untuk pengiriman...">{{ old('shipaddress', $user->shipaddress) }}</textarea>
                            @error('shipaddress')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Alamat ini akan digunakan sebagai alamat pengiriman default untuk semua pesanan.</div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-shield-check"></i> Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Username:</strong><br>
                        <span class="text-muted">{{ $user->username }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Role:</strong><br>
                        <span class="badge bg-success">Customer</span>
                    </div>
                    <div class="mb-3">
                        <strong>Member Sejak:</strong><br>
                        <span class="text-muted">{{ $user->created_at->format('d F Y') }}</span>
                    </div>
                    <div class="mb-0">
                        <strong>Terakhir Update:</strong><br>
                        <span class="text-muted">{{ $user->updated_at->format('d F Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Security -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-lock"></i> Keamanan Akun</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Untuk mengubah password atau informasi keamanan lainnya, silakan hubungi admin.</p>
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/6281234567890?text=Halo, saya ingin mengubah password akun {{ $user->username }}" 
                           class="btn btn-success btn-sm" target="_blank">
                            <i class="bi bi-whatsapp"></i> WhatsApp Admin
                        </a>
                        <a href="mailto:admin@batamgeneralsupplier.com?subject=Permintaan Ubah Password - {{ $user->username }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-envelope"></i> Email Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-fill fullname based on firstname and lastname
document.getElementById('firstname').addEventListener('input', updateFullname);
document.getElementById('lastname').addEventListener('input', updateFullname);

function updateFullname() {
    const firstname = document.getElementById('firstname').value;
    const lastname = document.getElementById('lastname').value;
    document.getElementById('fullname').value = (firstname + ' ' + lastname).trim();
}

// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.startsWith('0')) {
        value = '62' + value.substring(1);
    }
    if (!value.startsWith('62')) {
        value = '62' + value;
    }
    
    // Format as +62 XXX-XXXX-XXXX
    if (value.length > 2) {
        value = '+' + value.substring(0, 2) + ' ' + value.substring(2);
    }
    if (value.length > 7) {
        value = value.substring(0, 7) + '-' + value.substring(7);
    }
    if (value.length > 12) {
        value = value.substring(0, 12) + '-' + value.substring(12);
    }
    
    e.target.value = value.substring(0, 17); // Limit length
});
</script>
@endsection