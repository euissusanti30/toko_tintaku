@extends('v_layouts.app')

@section('content')

<div class="content-card p-5">

    <!-- ICON & TITLE -->
    <div class="text-center mb-5">
        <div class="mb-3">
            <i class="fa-solid fa-map-location-dot"
               style="font-size:60px; color:#40C0CE;"></i>
        </div>
        <h2 class="fw-bold mb-2">Lokasi Toko Kami</h2>
        <p class="text-muted col-lg-6 mx-auto">
            Temukan lokasi fisik Toko Tintaku Premium untuk berbelanja secara langsung atau mengambil pesanan Anda.
        </p>
    </div>

    <!-- MAP CONTAINER -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="bg-white rounded-4 shadow-sm p-4 text-center">
                
                <!-- Google Maps Iframe -->
                <div class="rounded-4 overflow-hidden shadow-sm mb-4" style="border: 2px solid #eef2f7;">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15866.495719918556!2d106.82276538466185!3d-6.175392395640224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3d32ef39d89%3A0x6b9d62d2251147a4!2sMonumen%20Nasional!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                        width="100%" 
                        height="450" 
                        style="border:0; display:block;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <!-- ALAMAT & ACTION BUTTON -->
                <div class="p-4 bg-light rounded-4 d-md-flex align-items-center justify-content-between text-start">
                    <div class="mb-3 mb-md-0 d-flex align-items-center">
                        <div class="bg-white rounded-circle p-3 shadow-sm me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-location-dot text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Alamat Toko</h6>
                            <p class="mb-0 text-muted small">Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta</p>
                        </div>
                    </div>
                    <div>
                        <a href="https://maps.google.com/?q=Monumen+Nasional,+Jakarta+Pusat" 
                           target="_blank" 
                           class="btn shadow-sm text-white px-4 py-2 d-inline-flex align-items-center justify-content-center"
                           style="background: linear-gradient(135deg, #40c0ce, #2daab8); font-weight: 600; border-radius: 12px; height: 48px; min-width: 200px;">
                           <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
