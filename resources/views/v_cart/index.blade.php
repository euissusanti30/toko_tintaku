@extends('v_layouts.app')

@section('content')

<section class="py-5">

    <div class="container">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2 class="fw-bold mb-5">
            Keranjang Belanja
        </h2>

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $grandTotal = 0;
                        @endphp

                        @if(session('cart'))

                            @foreach(session('cart') as $id => $item)

                                @php
                                    $total = $item['harga'] * $item['qty'];

                                    $grandTotal += $total;
                                @endphp

                                <tr>

                                    <!-- PRODUK -->
                                    <td>

                                        <div class="d-flex align-items-center">

                                            <img src="{{ asset('produk/'.$item['foto']) }}"
                                                width="80"
                                                class="rounded-3 me-3">

                                            <div>

                                                <h6 class="mb-0 fw-bold">

                                                    {{ $item['nama_produk'] }}

                                                </h6>

                                            </div>

                                        </div>

                                    </td>

                                    <!-- HARGA -->
                                    <td>

                                        Rp {{ number_format($item['harga']) }}

                                    </td>

                                    <!-- QTY -->
                                    <td>

                                        <form action="/update-cart"
                                            method="POST">

                                            @csrf

                                            <input type="hidden"
                                                name="id"
                                                value="{{ $id }}">

                                            <input type="number"
                                                name="qty"
                                                value="{{ $item['qty'] }}"
                                                min="1"
                                                class="form-control"
                                                style="width:90px;">

                                            <button class="btn btn-sm btn-primary mt-2">

                                                Update

                                            </button>

                                        </form>

                                    </td>

                                    <!-- TOTAL -->
                                    <td>

                                        Rp {{ number_format($total) }}

                                    </td>

                                    <!-- HAPUS -->
                                    <td>

                                        <a href="/delete-cart/{{ $id }}"
                                            class="btn btn-danger btn-sm">

                                            Hapus

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        @else

                            <tr>

                                <td colspan="5"
                                    class="text-center py-5">

                                    Keranjang masih kosong

                                </td>

                            </tr>

                        @endif

                    </tbody>

                </table>

                <!-- TOTAL -->
                <div class="text-end mt-4">

                    <h3 class="fw-bold">

                        Total :
                        Rp {{ number_format($grandTotal) }}

                    </h3>

                    <a href="/checkout"
                        class="btn btn-tintaku mt-3">

                        Checkout

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection