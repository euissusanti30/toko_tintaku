@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-body">

        <h5>Tambah Produk</h5>

        <form action="{{ route('backend.produk.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label>Foto Produk</label>

                <input type="file"
                    name="foto"
                    class="form-control">

            </div>

            <div class="mb-3">
                <label>Nama Produk</label>

                <input type="text"
                    name="nama_produk"
                    class="form-control">

            </div>

            <div class="mb-3">
                <label>Kategori</label>

                <select name="kategori_id"
                    class="form-control">

                    @foreach ($kategori as $k)

                        <option value="{{ $k->id }}">
                            {{ $k->nama_kategori }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-3">
                <label>Harga</label>

                <input type="number"
                    name="harga"
                    class="form-control">

            </div>

            <div class="mb-3">
                <label>Stok</label>

                <input type="number"
                    name="stok"
                    class="form-control">

            </div>

            <div class="mb-3">
                <label>Berat</label>

                <input type="number"
                    name="berat"
                    class="form-control">

            </div>

            <div class="mb-3">
                <label>Detail Produk</label>

                <textarea name="detail"
                    rows="5"
                    class="form-control"></textarea>

            </div>

            <button class="btn btn-primary">

                Simpan

            </button>

        </form>

    </div>
</div>

@endsection