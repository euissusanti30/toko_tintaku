@extends('v_layouts.app')

@section('content')

<div class="content-card text-center p-5">

    <!-- ICON -->
    <div class="mb-3">
        <i class="fa-solid fa-headset"
           style="font-size:60px; color:#40C0CE;"></i>
    </div>

    <h2 class="fw-bold mb-2">Hubungi Kami</h2>

    <p class="text-muted mb-4">
        Kami siap membantu Anda. Silakan hubungi melalui WhatsApp atau Email di bawah ini.
    </p>

    <!-- CARD KONTAK -->
    <div class="row justify-content-center g-4">

        <!-- WHATSAPP -->
        <div class="col-md-5">

            <div class="p-4 bg-white rounded-4 shadow-sm h-100">

                <i class="fa-brands fa-whatsapp mb-3"
                   style="font-size:40px; color:#25D366;"></i>

                <h5 class="fw-bold">WhatsApp</h5>

                <p class="text-muted small mb-3">
                    Klik tombol di bawah untuk chat langsung
                </p>

                <a href="https://wa.me/6285813634451?text=Halo%20saya%20ingin%20bertanya%20tentang%20produk%20Tintaku"
                   target="_blank"
                   class="btn w-100"
                   style="background:#25D366; color:white; font-weight:600;">

                    Chat Sekarang

                </a>

            </div>

        </div>

        <!-- EMAIL -->
        <div class="col-md-5">

            <div class="p-4 bg-white rounded-4 shadow-sm h-100">

                <i class="fa-solid fa-envelope mb-3"
                   style="font-size:40px; color:#40C0CE;"></i>

                <h5 class="fw-bold">Email</h5>

                <p class="text-muted small mb-3">
                    Kirim pertanyaan melalui email
                </p>

                <a href="mailto:support@tintaku.com"
                   class="text-decoration-none text-dark fw-semibold">

                    support@tintaku.com

                </a>

            </div>

        </div>

    </div>

    <!-- ALAMAT -->
    <div class="mt-5 p-4 bg-white rounded-4 shadow-sm">

        <i class="fa-solid fa-location-dot me-2"
           style="color:#ff4d4d;"></i>

        <strong>Alamat:</strong> Indonesia

    </div>

</div>

@endsection