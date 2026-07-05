@extends('backend.layouts.app')

@section('content')

<form action="{{ route('backend.kategori.store') }}"
    method="POST">

    @csrf

    <div class="mb-3">

        <label>Nama Kategori</label>

        <input type="text"
            name="nama_kategori"
            value="{{ old('nama_kategori') }}"
            class="form-control @error('nama_kategori') is-invalid @enderror">

        @error('nama_kategori')
            <span class="invalid-feedback" role="alert">
                {{ $message }}
            </span>
        @enderror

    </div>

    <button class="btn btn-success">
        Simpan
    </button>

</form>

@endsection