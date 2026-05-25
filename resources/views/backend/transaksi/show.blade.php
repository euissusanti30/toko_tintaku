@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Detail Transaksi #{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</h5>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Informasi Customer</h6>
                        <p><strong>Nama:</strong> {{ $transaksi->nama_customer }}</p>
                        <p><strong>Email:</strong> {{ $transaksi->email }}</p>
                        <p><strong>Telepon:</strong> {{ $transaksi->telepon }}</p>
                        <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Transaksi</h6>
                        <p><strong>Total Harga:</strong> Rp {{ number_format($transaksi->total_harga) }}</p>
                        <p><strong>Status:</strong> 
                            @switch($transaksi->status)
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
                                    <span class="badge bg-secondary">{{ $transaksi->status }}</span>
                            @endswitch
                        </p>
                        
                        <!-- Update Status Form -->
                        <form action="{{ route('backend.transaksi.update', $transaksi->id) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-8">
                                    <select name="status" class="form-control form-control-sm">
                                        <option value="belum bayar" {{ $transaksi->status == 'belum bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                        <option value="sudah bayar" {{ $transaksi->status == 'sudah bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                        <option value="pending" {{ $transaksi->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="proses" {{ $transaksi->status == 'proses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="selesai" {{ $transaksi->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="batal" {{ $transaksi->status == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <h6>Detail Produk</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->detailTransaksi as $detail)
                            <tr>
                                <td>{{ $detail->produk->nama_produk ?? 'Produk tidak ditemukan' }}</td>
                                <td>Rp {{ number_format($detail->harga) }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>Rp {{ number_format($detail->subtotal) }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-info">
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>Rp {{ number_format($transaksi->total_harga) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <a href="{{ route('backend.transaksi.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
