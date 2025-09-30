<!-- resources/views/contact.blade.php -->
@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">Hubungi Kami</h2>
        <p class="text-muted">Kami siap membantu Anda dengan pelayanan terbaik</p>
    </div>

    <div class="row">
        <!-- Contact Information -->
        <div class="col-lg-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title fw-bold mb-4">
                        <i class="bi bi-building text-primary"></i> {{ $contact->company_name }}
                    </h4>
                    
                    <div class="mb-4">
                        <h6><i class="bi bi-geo-alt text-primary"></i> Alamat</h6>
                        <p class="text-muted">{{ $contact->address }}</p>
                    </div>

                    <div class="mb-4">
                        <h6><i class="bi bi-telephone text-primary"></i> Telepon</h6>
                        <p class="text-muted">
                            <a href="tel:{{ $contact->phone_primary }}" class="text-decoration-none">{{ $contact->getFormattedPhonePrimary() }}</a>
                            @if($contact->phone_secondary)
                            <br><a href="tel:{{ $contact->phone_secondary }}" class="text-decoration-none">{{ $contact->getFormattedPhoneSecondary() }}</a>
                            @endif
                        </p>
                    </div>

                    <div class="mb-4">
                        <h6><i class="bi bi-envelope text-primary"></i> Email</h6>
                        <p class="text-muted">
                            <a href="mailto:{{ $contact->email_primary }}" class="text-decoration-none">{{ $contact->email_primary }}</a>
                            @if($contact->email_secondary)
                            <br><a href="mailto:{{ $contact->email_secondary }}" class="text-decoration-none">{{ $contact->email_secondary }}</a>
                            @endif
                        </p>
                    </div>

                    <div class="mb-4">
                        <h6><i class="bi bi-clock text-primary"></i> Jam Operasional</h6>
                        <div class="text-muted">
                            @foreach($contact->getFormattedOperationalHours() as $schedule)
                            <div class="d-flex justify-content-between">
                                <span>{{ $schedule['day'] }}:</span>
                                <span>{{ $schedule['hours'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Contact Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ $contact->getWhatsAppUrl('Halo, saya ingin bertanya tentang produk') }}" 
                           class="btn btn-success flex-fill" target="_blank">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                        <a href="mailto:{{ $contact->email_primary }}" class="btn btn-primary flex-fill">
                            <i class="bi bi-envelope"></i> Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-primary">
                <h5><i class="bi bi-lightbulb"></i> Informasi Penting</h5>
                <ul class="mb-0">
                    <li>Untuk akun baru, silakan hubungi admin melalui WhatsApp atau email</li>
                    <li>Semua pembayaran dilakukan melalui konfirmasi dengan admin</li>
                    <li>Kami melayani pembelian dalam jumlah kecil maupun besar</li>
                    <li>Konsultasi gratis untuk kebutuhan khusus perusahaan Anda</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    
    // Create WhatsApp message
    const whatsappMessage = `Halo, saya ingin menghubungi {{ $contact->company_name }}%0A%0A` +
        `Nama: ${name}%0A` +
        `Email: ${email}%0A` +
        `Telepon: ${phone}%0A` +
        `Subjek: ${subject}%0A` +
        `Pesan: ${message}`;
    
    // Open WhatsApp
    window.open(`{{ $contact->getWhatsAppUrl() }}&text=${whatsappMessage}`, '_blank');
    
    // Show success message
    showAlert('success', 'Terima kasih! Anda akan diarahkan ke WhatsApp untuk mengirim pesan.');
    
    // Reset form
    this.reset();
});
</script>
@endsection