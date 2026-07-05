@extends('backend.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Data Transaksi</h3>
    <a href="{{ route('backend.transaksi.export') }}" class="btn btn-success btn-sm">
        <i class="fa-solid fa-file-excel me-2"></i> Ekspor ke Excel
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>#{{ str_pad($row->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $row->nama_customer }}</td>
                        <td>{{ $row->email }}</td>
                        <td>Rp {{ number_format($row->total_harga) }}</td>
                        <td>
                            @switch($row->status)
                                @case('belum bayar')
                                    <span class="badge bg-danger">Belum Bayar</span>
                                    @break
                                @case('sudah bayar')
                                    <span class="badge bg-success">Sudah Bayar</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @break
                                @case('proses')
                                    <span class="badge bg-info">Diproses</span>
                                    @break
                                @case('selesai')
                                    <span class="badge bg-success">Selesai</span>
                                    @break
                                @case('batal')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $row->status }}</span>
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('backend.transaksi.show', $row->id) }}" class="btn btn-info btn-sm">
                                Detail
                            </a>
                            <form action="{{ route('backend.transaksi.destroy', $row->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus transaksi ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Belum ada transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
