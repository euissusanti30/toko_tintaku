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
                <label for="foto" class="form-label">Foto Produk</label>
                <input type="file"
                    id="foto"
                    name="foto"
                    class="form-control @error('foto') is-invalid @enderror"
                    accept="image/*">
                @error('foto')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text"
                    id="nama_produk"
                    name="nama_produk"
                    value="{{ old('nama_produk') }}"
                    class="form-control @error('nama_produk') is-invalid @enderror"
                    placeholder="Masukkan nama produk">
                @error('nama_produk')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="kategori_id" class="form-label">Kategori</label>
                <select id="kategori_id"
                    name="kategori_id"
                    class="form-control @error('kategori_id') is-invalid @enderror">

                    <option value="">Pilih Kategori</option>

                    @foreach ($kategori as $k)

                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>

                    @endforeach

                </select>
                @error('kategori_id')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number"
                    id="harga"
                    name="harga"
                    value="{{ old('harga') }}"
                    class="form-control @error('harga') is-invalid @enderror"
                    placeholder="0"
                    min="0">
                @error('harga')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number"
                    id="stok"
                    name="stok"
                    value="{{ old('stok') }}"
                    class="form-control @error('stok') is-invalid @enderror"
                    placeholder="0"
                    min="0">
                @error('stok')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="berat" class="form-label">Berat (gram)</label>
                <input type="number"
                    id="berat"
                    name="berat"
                    value="{{ old('berat') }}"
                    class="form-control @error('berat') is-invalid @enderror"
                    placeholder="0"
                    min="0"
                    step="0.1">
                @error('berat')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="detail" class="form-label">Detail Produk</label>
                <textarea id="detail"
                    name="detail"
                    rows="5"
                    class="form-control @error('detail') is-invalid @enderror"
                    placeholder="Masukkan detail produk">{{ old('detail') }}</textarea>
                @error('detail')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('backend.produk.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

        </form>

    </div>
</div>

@endsection