<!-- resources/views/admin/contacts/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Kelola Informasi Kontak')

@section('content')
<!-- Page Header -->
<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="dashboard-title mb-2">Kelola Informasi Kontak</h1>
            <p class="dashboard-subtitle mb-0">Atur informasi kontak perusahaan yang ditampilkan di halaman kontak</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('contact') }}" target="_blank" class="btn btn-outline-primary">
                <i class="bi bi-eye me-2"></i>Lihat Halaman Kontak
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Contact Information Form -->
    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-building me-2"></i>Informasi Perusahaan
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.contacts.update') }}" id="contactForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Company Name -->
                    <div class="mb-4">
                        <label for="company_name" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="company_name" name="company_name" 
                               value="{{ $contact->company_name }}" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="address" name="address" rows="3" required>{{ $contact->address }}</textarea>
                    </div>

                    <div class="row">
                        <!-- Primary Phone -->
                        <div class="col-md-6 mb-4">
                            <label for="phone_primary" class="form-label">Telepon Utama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone_primary" name="phone_primary" 
                                   value="{{ $contact->phone_primary }}" required placeholder="0778123456">
                        </div>

                        <!-- Secondary Phone -->
                        <div class="col-md-6 mb-4">
                            <label for="phone_secondary" class="form-label">Telepon Kedua</label>
                            <input type="text" class="form-control" id="phone_secondary" name="phone_secondary" 
                                   value="{{ $contact->phone_secondary }}" placeholder="081234567890">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Primary Email -->
                        <div class="col-md-6 mb-4">
                            <label for="email_primary" class="form-label">Email Utama <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email_primary" name="email_primary" 
                                   value="{{ $contact->email_primary }}" required>
                        </div>

                        <!-- Secondary Email -->
                        <div class="col-md-6 mb-4">
                            <label for="email_secondary" class="form-label">Email Kedua</label>
                            <input type="email" class="form-control" id="email_secondary" name="email_secondary" 
                                   value="{{ $contact->email_secondary }}">
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="mb-4">
                        <label for="whatsapp" class="form-label">WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" 
                               value="{{ $contact->whatsapp }}" required placeholder="6281234567890">
                        <div class="form-text">Format: 6281234567890 (dimulai dengan kode negara)</div>
                    </div>

                    <!-- Operational Hours -->
                    <div class="mb-4">
                        <label class="form-label">Jam Operasional <span class="text-danger">*</span></label>
                        <div class="operational-hours-container">
                            @php
                                $days = [
                                    'monday' => 'Senin',
                                    'tuesday' => 'Selasa', 
                                    'wednesday' => 'Rabu',
                                    'thursday' => 'Kamis',
                                    'friday' => 'Jumat',
                                    'saturday' => 'Sabtu',
                                    'sunday' => 'Minggu'
                                ];
                            @endphp

                            @foreach($days as $day => $label)
                            <div class="day-row">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <label class="day-label">{{ $label }}</label>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input day-toggle" type="checkbox" 
                                                   name="operational_hours[{{ $day }}][is_open]" value="1"
                                                   id="is_open_{{ $day }}" 
                                                   {{ $contact->operational_hours[$day]['is_open'] ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_open_{{ $day }}">
                                                Buka
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="time" class="form-control time-input" 
                                               name="operational_hours[{{ $day }}][open]"
                                               value="{{ $contact->operational_hours[$day]['open'] }}"
                                               {{ !$contact->operational_hours[$day]['is_open'] ? 'disabled' : '' }}>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <span class="time-separator">-</span>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="time" class="form-control time-input" 
                                               name="operational_hours[{{ $day }}][close]"
                                               value="{{ $contact->operational_hours[$day]['close'] }}"
                                               {{ !$contact->operational_hours[$day]['is_open'] ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" onclick="resetForm()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Panel -->
    <div class="col-lg-4">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-eye me-2"></i>Preview
                </h5>
            </div>
            <div class="card-body">
                <div class="preview-section">
                    <h6><i class="bi bi-building text-primary"></i> Perusahaan</h6>
                    <p class="preview-company-name">{{ $contact->company_name }}</p>
                </div>

                <div class="preview-section">
                    <h6><i class="bi bi-geo-alt text-primary"></i> Alamat</h6>
                    <p class="preview-address">{{ $contact->address }}</p>
                </div>

                <div class="preview-section">
                    <h6><i class="bi bi-telephone text-primary"></i> Telepon</h6>
                    <p class="preview-phones">
                        {{ $contact->getFormattedPhonePrimary() }}<br>
                        @if($contact->phone_secondary)
                        {{ $contact->getFormattedPhoneSecondary() }}
                        @endif
                    </p>
                </div>

                <div class="preview-section">
                    <h6><i class="bi bi-envelope text-primary"></i> Email</h6>
                    <p class="preview-emails">
                        {{ $contact->email_primary }}<br>
                        @if($contact->email_secondary)
                        {{ $contact->email_secondary }}
                        @endif
                    </p>
                </div>

                <div class="preview-section">
                    <h6><i class="bi bi-whatsapp text-success"></i> WhatsApp</h6>
                    <p class="preview-whatsapp">{{ $contact->whatsapp }}</p>
                </div>

                <div class="preview-section">
                    <h6><i class="bi bi-clock text-primary"></i> Jam Operasional</h6>
                    <div class="preview-hours">
                        @foreach($contact->getFormattedOperationalHours() as $schedule)
                        <div class="hours-row">
                            <span class="day">{{ $schedule['day'] }}:</span>
                            <span class="hours">{{ $schedule['hours'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ $contact->getWhatsAppUrl('Halo, saya ingin bertanya tentang produk') }}" 
                       target="_blank" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-whatsapp me-2"></i>Test WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Contact-specific styles extending the admin layout */

.operational-hours-container {
    background: var(--gray-50);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid var(--gray-200);
}

.day-row {
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: white;
    border-radius: 8px;
    border: 1px solid var(--gray-100);
}

.day-row:last-child {
    margin-bottom: 0;
}

.day-label {
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    line-height: 2.5;
}

.time-input:disabled {
    background-color: var(--gray-100);
    opacity: 0.6;
}

.time-separator {
    color: var(--gray-400);
    font-weight: 500;
}

.form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}

/* Preview Styles */
.preview-section {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--gray-100);
}

.preview-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.preview-section h6 {
    color: var(--gray-600);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.preview-section p {
    color: var(--gray-700);
    margin: 0;
    font-size: 0.875rem;
    line-height: 1.5;
}

.preview-company-name {
    font-weight: 600;
    font-size: 1rem !important;
    color: var(--primary) !important;
}

.preview-hours .hours-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.25rem;
    font-size: 0.8rem;
}

.preview-hours .day {
    font-weight: 500;
    color: var(--gray-600);
}

.preview-hours .hours {
    color: var(--gray-700);
}

/* Form Validation Styles */
.is-invalid {
    border-color: var(--danger);
}

.invalid-feedback {
    display: block;
    color: var(--danger);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

/* Button Hover Effects */
.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-header .col-lg-4 {
        margin-top: 1rem;
        text-align: left !important;
    }
    
    .day-row .row {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .day-row .col-md-2,
    .day-row .col-md-4,
    .day-row .col-md-3,
    .day-row .col-md-1 {
        width: 100%;
        max-width: 100%;
    }
    
    .time-separator {
        display: none;
    }
}
</style>

<script>
// Handle day toggle functionality
document.querySelectorAll('.day-toggle').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        const dayRow = this.closest('.day-row');
        const timeInputs = dayRow.querySelectorAll('.time-input');
        
        timeInputs.forEach(function(input) {
            input.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                input.value = '00:00';
            }
        });
        
        updatePreview();
    });
});

// Handle form input changes for live preview
document.querySelectorAll('#contactForm input, #contactForm textarea').forEach(function(input) {
    input.addEventListener('input', updatePreview);
});

function updatePreview() {
    // Update company name
    const companyName = document.getElementById('company_name').value;
    document.querySelector('.preview-company-name').textContent = companyName || 'PT. Batam General Supplier';
    
    // Update address
    const address = document.getElementById('address').value;
    document.querySelector('.preview-address').textContent = address || 'Alamat belum diisi';
    
    // Update phones
    const phonePrimary = document.getElementById('phone_primary').value;
    const phoneSecondary = document.getElementById('phone_secondary').value;
    const phoneText = phonePrimary + (phoneSecondary ? '<br>' + phoneSecondary : '');
    document.querySelector('.preview-phones').innerHTML = phoneText || 'Telepon belum diisi';
    
    // Update emails
    const emailPrimary = document.getElementById('email_primary').value;
    const emailSecondary = document.getElementById('email_secondary').value;
    const emailText = emailPrimary + (emailSecondary ? '<br>' + emailSecondary : '');
    document.querySelector('.preview-emails').innerHTML = emailText || 'Email belum diisi';
    
    // Update WhatsApp
    const whatsapp = document.getElementById('whatsapp').value;
    document.querySelector('.preview-whatsapp').textContent = whatsapp || 'WhatsApp belum diisi';
}

function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form? Semua perubahan yang belum disimpan akan hilang.')) {
        document.getElementById('contactForm').reset();
        location.reload();
    }
}

// Form validation
document.getElementById('contactForm').addEventListener('submit', function(e) {
    let isValid = true;
    const requiredFields = ['company_name', 'address', 'phone_primary', 'email_primary', 'whatsapp'];
    
    requiredFields.forEach(function(fieldName) {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi.');
    }
});

// Phone number formatting
document.getElementById('whatsapp').addEventListener('input', function() {
    let value = this.value.replace(/[^\d]/g, '');
    if (value.length > 0 && !value.startsWith('62')) {
        if (value.startsWith('0')) {
            value = '62' + value.substring(1);
        }
    }
    this.value = value;
});
</script>
@endsection