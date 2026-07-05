@extends('v_layouts.app')

@section('content')

<div class="content-card">

    <!-- TITLE DETAIL -->

    <div class="mb-4">

        <h4 class="fw-bold text-dark">


            Detail Produk

        </h4>

    </div>

    <div class="row">

    <div class="row">

        <div class="col-md-5">
            <img src="{{ asset('produk/'.$produk->foto) }}" class="img-fluid rounded-4 shadow-sm">               
        </div>

        <div class="col-md-7">

            <h2 class="fw-bold">

                {{ $produk->nama_produk }}

            </h2>

            <div class="price my-3">

                Rp {{ number_format($produk->harga,0,',','.') }}

            </div>

            <p class="text-muted">

                {{ $produk->detail }}

            </p>

            <hr>

            <p>

                <strong>Stok:</strong>
                {{ $produk->stok }}

            </p>

            <p>

                <strong>Berat:</strong>
                {{ $produk->berat }} gram

            </p>

            <!-- BUTTON -->
<div class="mt-4 d-flex gap-3 flex-wrap align-items-center">

    <!-- BELI SEKARANG -->
    <a href="/checkout"
        class="btn text-white px-4 py-3 fw-semibold shadow-sm rounded-3"
        style="background-color: #47C7D9; border: none;">

        <i class="fa-solid fa-bag-shopping me-2"></i>

        Checkout

</a>

    <!-- TAMBAH KERANJANG -->
    <a href="/add-cart/{{ $produk->id }}"
        class="btn btn-outline-info px-3 py-2 rounded-3">

        <i class="fa-solid fa-cart-shopping"></i>

    </a>

</div>

        </div>

    </div>

</div>

@endsection