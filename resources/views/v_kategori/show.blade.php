@extends('v_layouts.app')

@section('content')

<div class="content-card">
    <h2 class="mb-4" style="color: var(--dark); font-weight:700;">
        {{ $kategori->nama_kategori }}
    </h2>

    <div class="row g-4">
        @forelse($produk as $item)
        <div class="col-lg-4 col-md-6">
            <div class="card product-card h-100">
                <img src="{{ asset('foto/' . $item->foto) }}" 
                     class="card-img-top" 
                     alt="{{ $item->nama_produk }}"
                     style="object-fit: contain; padding: 1rem;">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold" style="color: var(--dark);">
                        {{ $item->nama_produk }}
                    </h5>

                    <p class="price mb-2">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </p>

                    <div class="text-muted small mb-3">
                        <p class="mb-1">Berat: {{ $item->berat }} gram</p>
                        <p class="mb-0">Stok: {{ $item->stok }}</p>
                    </div>

                    <a href="#" class="btn btn-tintaku mt-auto w-100">
                        <i class="fa-solid fa-eye me-2"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        @empty
        <div class="col-12">
            <div class="alert alert-light text-center py-5">
                <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada produk di kategori ini</h5>
            </div>
        </div>
        @endforelse
    </div>
</div>

@endsection