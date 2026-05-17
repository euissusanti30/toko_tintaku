@extends('v_layouts.app')

@section('content')

    <section class="py-5">

        <div class="container">

            <div class="row">

                <!-- SIDEBAR -->
                <div class="col-lg-3 mb-4">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body">

                            <h5 class="fw-bold mb-4">
                                Kategori
                            </h5>

                            @foreach($kategori as $row)

                                <a href="/kategori/{{ $row->id }}" class="btn btn-light w-100 text-start mb-2">

                                    {{ $row->nama_kategori }}

                                </a>

                            @endforeach

                        </div>

                    </div>

                </div>

                <!-- PRODUK -->
                <div class="col-lg-9">

                    <div class="row g-4">

                        @foreach($produk as $row)

                            <div class="col-md-4">

                                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                                    <!-- FOTO -->
                                    <div style="height:250px; overflow:hidden;">

                                        @if($row->foto)

                                            <img src="{{ asset('produk/' . $row->foto) }}" class="w-100 h-100"
                                                style="object-fit:cover;" alt="{{ $row->nama_produk }}">

                                        @else

                                            <img src="https://via.placeholder.com/300x250?text=No+Image" class="w-100 h-100"
                                                style="object-fit:cover;" alt="No Image">

                                        @endif

                                    </div>

                                    <!-- BODY -->
                                    <div class="card-body d-flex flex-column">

                                        <h5 class="fw-bold mb-2">

                                            {{ $row->nama_produk }}

                                        </h5>

                                        <p class="text-muted small flex-grow-1">

                                            {{ \Illuminate\Support\Str::limit(strip_tags($row->detail), 50) }}

                                        </p>

                                        <!-- PRICE + DETAIL -->
                                        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">

                                            <span class="fw-bold text-primary fs-5">

                                                Rp {{ number_format($row->harga, 0, ',', '.') }}

                                            </span>

                                            <a href="/detail/{{ $row->id }}" class="btn btn-primary rounded-pill px-3">

                                                Detail

                                            </a>

                                        </div>

                                        <!-- CART BUTTON -->
                                        @auth

                                            <a href="{{ url('/add-to-cart/' . $row->id) }}"
                                                class="btn btn-success rounded-pill w-100 py-2">

                                                <i class="fa-solid fa-cart-shopping me-2"></i>

                                                Tambah Keranjang

                                            </a>

                                        @else

                                            <a href="{{ route('login') }}" class="btn btn-success rounded-pill w-100 py-2">

                                                <i class="fa-solid fa-cart-shopping me-2"></i>

                                                Tambah Keranjang

                                            </a>

                                        @endauth

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-4">

                        {{ $produk->links() }}

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection