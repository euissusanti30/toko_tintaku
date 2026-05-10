@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-body">

        <h5>Edit Produk</h5>

        <form action="{{ route('backend.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Foto Produk</label>
                <input type="file" name="foto" class="form-control">
                @if ($produk->foto)
                    <div class="mt-2">
                        <small>Foto saat ini:</small>
                        <img src="{{ asset('produk/'.$produk->foto) }}" class="img-thumbnail d-block mt-2" width="140">
                    </div>
                @endif
            </div>

            <!-- NAMA PRODUK -->
            <input type="text" name="nama_produk"
                value="{{ $produk->nama_produk }}"
                class="form-control mb-2">

            <!-- KATEGORI -->
            <select name="kategori_id" class="form-control mb-2">

                @foreach ($kategori as $k)

                    <option value="{{ $k->id }}"
                        {{ $produk->kategori_id == $k->id ? 'selected' : '' }}>

                        {{ $k->nama_kategori }}

                    </option>

                @endforeach

            </select>

            <!-- HARGA -->
            <input type="number" name="harga"
                value="{{ $produk->harga }}"
                class="form-control mb-2">

            <!-- STOK -->
            <input type="number" name="stok"
                value="{{ $produk->stok }}"
                class="form-control mb-2">

            <!-- 🔥 TAMBAHAN WAJIB: BERAT -->
            <input type="number" name="berat"
                value="{{ $produk->berat }}"
                class="form-control mb-2"
                placeholder="Berat Produk">

            <!-- 🔥 TAMBAHAN WAJIB: DETAIL -->
            <textarea name="detail"
                class="form-control mb-2"
                placeholder="Detail Produk">{{ $produk->detail }}</textarea>

            <button class="btn btn-success">Update</button>

        </form>

    </div>
</div>

@endsection