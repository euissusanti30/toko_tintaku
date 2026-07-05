@extends('v_layouts.app')

@section('content')

<section class="py-5">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <img src="{{ asset }}"
                    class="img-fluid rounded-4 shadow">

            </div>

            <div class="col-lg-6">

                <h2 class="fw-bold mb-3">

                    {{ $detail->nama_produk }}

                </h2>

                <h3 class="price mb-4">

                    Rp {{ number_format($detail->harga) }}

                </h3>

                <p class="text-muted">

                    {{ $detail->detail }}

                </p>

                <div class="mb-3">

                    <strong>Stok :</strong>

                    {{ $detail->stok }}

                </div>

                <a href="/add-cart/{{ $detail->id }}"
    class="btn btn-tintaku">
    </a>
                    <i class="fa-solid fa-cart-shopping"></i>

                    Tambah ke Keranjang

                </button>

            </div>

        </div>

    </div>

</section>

@endsection