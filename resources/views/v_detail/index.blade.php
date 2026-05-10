@extends('v_layouts.app')

@section('content')

<div class="content-card">

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

            <div class="mt-4">

                <a href="/add-cart/{{ $produk->id }}"
                    class="btn btn-tintaku btn-lg">

                    <i class="fa-solid fa-cart-shopping"></i>

                    Tambah ke Keranjang

                </a>

            </div>

        </div>

    </div>

</div>

@endsection