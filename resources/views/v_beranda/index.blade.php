@extends('v_layouts.app')

@section('content')

<!-- SLIDER / BANNER -->
<div id="carouselExample"
    class="carousel slide mb-4"
    data-bs-ride="carousel">

    <div class="carousel-inner rounded-4 overflow-hidden shadow-sm">

        <!-- SLIDE 1 - TINTA CANON -->
        <div class="carousel-item active">

            <div class="position-relative">

                <img src="{{ asset('banner/TintaCannon.jpg') }}"
                    class="d-block w-100"
                    style="height:450px; object-fit:cover;">

                <!-- OVERLAY -->
                <div class="position-absolute top-0 start-0 w-100 h-100"
                    style="background: rgba(0,0,0,0.45);">
                </div>

                <!-- TEXT -->
                <div class="position-absolute top-50 start-0 translate-middle-y text-white"
                    style="max-width:65%; padding-left:120px;">

                    <h1 class="fw-bold"
                        style="font-size:32px; text-shadow:2px 2px 10px rgba(0,0,0,0.6);">

                        Tinta Canon Original

                    </h1>

                    <p class="mt-2"
                        style="font-size:16px; line-height:1.5;">

                        Hasil cetak tajam, awet, dan warna lebih hidup untuk printer Canon kamu.

                    </p>

                    <a href="/shop"
                        class="btn btn-tintaku mt-3 px-3 py-2"
                        style="font-size:14px;">

                        Belanja Sekarang

                    </a>

                </div>

            </div>

        </div>

        <!-- SLIDE 2 - TINTA HP -->
        <div class="carousel-item">

            <div class="position-relative">

                <img src="{{ asset('banner/TintaHp.jpg') }}"
                    class="d-block w-100"
                    style="height:450px; object-fit:cover;">

                <div class="position-absolute top-0 start-0 w-100 h-100"
                    style="background: rgba(0,0,0,0.45);">
                </div>

                <div class="position-absolute top-50 start-0 translate-middle-y text-white"
                    style="max-width:65%; padding-left:120px;">

                    <h1 class="fw-bold"
                        style="font-size:32px; text-shadow:2px 2px 10px rgba(0,0,0,0.6);">

                        Tinta HP Berkualitas

                    </h1>

                    <p class="mt-2"
                        style="font-size:16px; line-height:1.5;">

                        Cocok untuk kebutuhan kantor & rumah dengan hasil cetak profesional.

                    </p>

                    <a href="/shop"
                        class="btn btn-tintaku mt-3 px-3 py-2"
                        style="font-size:14px;">

                        Lihat Produk

                    </a>

                </div>

            </div>

        </div>

        <!-- SLIDE 3 - TINTA EPSON -->
        <div class="carousel-item">

            <div class="position-relative">

                <img src="{{ asset('banner/TintaEpson.jpg') }}"
                    class="d-block w-100"
                    style="height:450px; object-fit:cover;">

                <div class="position-absolute top-0 start-0 w-100 h-100"
                    style="background: rgba(0,0,0,0.45);">
                </div>

                <div class="position-absolute top-50 start-0 translate-middle-y text-white"
                    style="max-width:65%; padding-left:120px;">

                    <h1 class="fw-bold"
                        style="font-size:32px; text-shadow:2px 2px 10px rgba(0,0,0,0.6);">

                        Tinta Epson Terbaik

                    </h1>

                    <p class="mt-2"
                        style="font-size:16px; line-height:1.5;">

                        Warna lebih stabil, hemat tinta, dan cocok untuk printer Epson.

                    </p>

                    <a href="/shop"
                        class="btn btn-tintaku mt-3 px-3 py-2"
                        style="font-size:14px;">

                        Belanja Sekarang

                    </a>

                </div>

            </div>

        </div>

    </div>

    <!-- NAV BUTTON -->
    <button class="carousel-control-prev"
        type="button"
        data-bs-target="#carouselExample"
        data-bs-slide="prev">

        <span class="carousel-control-prev-icon"></span>

    </button>

    <button class="carousel-control-next"
        type="button"
        data-bs-target="#carouselExample"
        data-bs-slide="next">

        <span class="carousel-control-next-icon"></span>

    </button>

</div>

<!-- PRODUK -->
<div class="content-card">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold">
            Produk Terbaru
        </h2>

        <a href="/shop" class="btn btn-tintaku">
            Semua Produk
        </a>

    </div>

    <div class="row g-4">

        @foreach($produk as $item)

        <div class="col-md-4">

            <div class="card product-card h-100 border-0 shadow-sm overflow-hidden">

                <div style="height:250px; overflow:hidden; background:#f5f5f5;">

                    @if($item->foto && file_exists(public_path('produk/'.$item->foto)))

                    <img src="{{ asset('produk/'.$item->foto) }}"
                        class="w-100 h-100"
                        style="object-fit:cover;">

                    @else

                    <img src="https://via.placeholder.com/300x250?text=No+Image"
                        class="w-100 h-100"
                        style="object-fit:cover;">

                    @endif

                </div>

                <div class="card-body d-flex flex-column">

                    <h5 class="fw-bold mb-2">
                        {{ $item->nama_produk }}
                    </h5>

                    <p class="text-muted small flex-grow-1">
                        {{ \Illuminate\Support\Str::limit(strip_tags($item->detail), 60) }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center mt-3">

                        <span style="color:#40C0CE; font-size:18px; font-weight:700;">
                            Rp {{ number_format($item->harga,0,',','.') }}
                        </span>

                        <a href="/detail/{{ $item->id }}"
                            class="btn btn-tintaku px-3 py-2">
                            Detail
                        </a>

                    </div>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endsection