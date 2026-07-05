@extends('backend.layouts.app')

@section('content')

<h3 class="mb-4">Data Produk</h3>

<a href="{{ route('backend.produk.create') }}"
    class="btn btn-primary mb-3">

    Tambah Produk

</a>

<table class="table table-bordered align-middle">

    <thead class="table-dark">

        <tr>

            <th>No</th>
            <th>Foto</th>
            <th>Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>

        </tr>

    </thead>

    <tbody>

        @foreach($index as $row)

        <tr>

            <td>{{ $loop->iteration }}</td>

            <td>
                <img src="{{ asset('produk/'.$row->foto) }}"
                    width="70"
                    class="rounded">
            </td>

            <td>{{ $row->nama_produk }}</td>

            <td>
                {{ $row->kategori->nama_kategori ?? '-' }}
            </td>

            <td>
                Rp {{ number_format($row->harga) }}
            </td>

            <td>
                {{ $row->stok }}
            </td>

            <!-- 🔥 CRUD ACTION -->
            <td>

                <!-- EDIT -->
                <a href="{{ route('backend.produk.edit', $row->id) }}"
                    class="btn btn-warning btn-sm">

                    Edit

                </a>

                <!-- DELETE -->
                <form action="{{ route('backend.produk.destroy', $row->id) }}"
                    method="POST"
                    class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin ingin hapus produk ini?')">

                        Hapus

                    </button>

                </form>

            </td>

        </tr>

        @endforeach

    </tbody>

</table>

@endsection