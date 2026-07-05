@extends('backend.layouts.app')

@section('content')

<a href="{{ route('backend.kategori.create') }}"
    class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Tambah
</a>

<div class="card">

    <div class="card-body">

        <h5 class="card-title">{{ $judul ?? 'Data Kategori' }}</h5>

        <div class="table-responsive">

            <table class="table table-striped table-bordered">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($kategori as $row)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $row->nama_kategori }}</td>

                        <td>

                            <a href="{{ route('backend.kategori.edit', $row->id) }}"
                                class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('backend.kategori.destroy', $row->id) }}"
                                method="POST"
                                style="display:inline-block;">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm">
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            Data kosong
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection