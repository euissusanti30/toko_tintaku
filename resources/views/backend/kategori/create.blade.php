@extends('backend.layouts.app')

@section('content')

<form action="{{ route('backend.kategori.store') }}"
    method="POST">

    @csrf

    <div class="mb-3">

        <label>Nama Kategori</label>

        <input type="text"
            name="nama_kategori"
            class="form-control">

    </div>

    <button class="btn btn-success">
        Simpan
    </button>

</form>

@endsection