@extends('backend.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <h4>{{ $judul }}</h4>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label>Kategori</label>

                                <input type="text"
                                    class="form-control"
                                    value="{{ $show->kategori->nama_kategori }}"
                                    disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label>Nama Produk</label>

                                <input type="text"
                                    class="form-control"
                                    value="{{ $show->nama_produk }}"
                                    disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label>Harga</label>

                                <input type="text"
                                    class="form-control"
                                    value="Rp {{ number_format($show->harga,0,',','.') }}"
                                    disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label>Stok</label>

                                <input type="text"
                                    class="form-control"
                                    value="{{ $show->stok }}"
                                    disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label>Berat</label>

                                <input type="text"
                                    class="form-control"
                                    value="{{ $show->berat }}"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label>Detail</label>

                                <textarea class="form-control"
                                    rows="5"
                                    disabled>{{ $show->detail }}</textarea>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <label>Foto Produk</label>

                            <br>

                            <img src="{{ asset('produk/'.$show->foto) }}"
                                width="100%"
                                class="img-fluid rounded border">

                        </div>

                    </div>

                </div>

                <div class="card-footer">

                    <a href="{{ route('backend.produk.index') }}"
                        class="btn btn-secondary">

                        Kembali

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection