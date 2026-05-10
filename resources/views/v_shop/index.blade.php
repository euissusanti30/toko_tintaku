@extends('v_layouts.app')

@section('content')

<style>
/* =========================
   BUTTON FIXED & STABLE
========================= */

.btn-detail-custom{
    background-color:#2D2F39 !important;
    color:#fff !important;
    font-weight:600;
    border-radius:10px;
    padding:10px;
    border:0;
    transition:0.3s;
    text-decoration:none;
    display:flex;
    align-items:center;
    justify-content:center;
}

.btn-detail-custom:hover{
    background-color:#1f2129 !important;
    transform:translateY(-2px);
    color:#fff !important;
}

.btn-cart-custom{
    background-color:#40C0CE !important;
    color:#fff !important;
    font-weight:600;
    border-radius:10px;
    padding:10px;
    border:0;
    transition:0.3s;
    text-decoration:none;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
}

.btn-cart-custom:hover{
    background-color:#2daab8 !important;
    transform:translateY(-2px);
    color:#fff !important;
}
</style>

<div class="content-card">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3 class="fw-bold">
            Semua Produk
        </h3>

    </div>

    <div class="row g-4">

        @foreach($produk as $row)

        <div class="col-md-4">

            <div class="card product-card h-100">

                <!-- GAMBAR -->
                <img src="{{ asset('produk/'.$row->foto) }}"
                    class="card-img-top"
                    style="height:250px; object-fit:cover;">

                <div class="card-body d-flex flex-column">

                    <!-- NAMA PRODUK -->
                    <h5 class="fw-bold">
                        {{ $row->nama_produk }}
                    </h5>

                    <!-- DESKRIPSI -->
                    <p class="text-muted small">
                        {{ \Illuminate\Support\Str::limit($row->detail, 60) }}
                    </p>

                    <div class="mt-auto">

                        <!-- HARGA -->
                        <div class="price mb-3">
                            Rp {{ number_format($row->harga,0,',','.') }}
                        </div>

                        <!-- BUTTON -->
                        <div class="d-grid gap-2">

                            <!-- DETAIL -->
                            <a href="/detail/{{ $row->id }}"
                                class="btn btn-detail-custom">

                                Detail

                            </a>

                            <!-- KERANJANG -->
                            <a href="/add-cart/{{ $row->id }}"
                                class="btn btn-cart-custom">

                                <i class="fa-solid fa-cart-shopping"></i>
                                Tambah Keranjang

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        @endforeach

    </div>

    <div class="mt-5">
        {{ $produk->links() }}
    </div>

</div>

@endsection