@extends('v_layouts.app')

@section('content')

<section class="py-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-5">

                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Invoice</h2>
                            <p class="text-muted">Terima kasih atas pesanan Anda</p>
                        </div>

                        <!-- Order Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Informasi Pesanan</h5>
                                <p><strong>No. Invoice:</strong> #{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</p>
                                <p><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d M Y H:i') }}</p>
                                <p><strong>Status:</strong> 
                                    @switch($transaksi->status)
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
                            </div>
                            <div class="col-md-6">
                                <h5>Informasi Customer</h5>
                                <p><strong>Nama:</strong> {{ $transaksi->nama_customer }}</p>
                                <p><strong>Email:</strong> {{ $transaksi->email }}</p>
                                <p><strong>Telepon:</strong> {{ $transaksi->telepon }}</p>
                                <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <h5 class="mb-3">Detail Pesanan</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
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

                        <!-- Payment Information -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">Informasi Pembayaran</h6>
                            <p class="mb-2">Silakan melakukan pembayaran ke:</p>
                            <p class="mb-1"><strong>Bank:</strong> BCA</p>
                            <p class="mb-1"><strong>No. Rekening:</strong> 1234567890</p>
                            <p class="mb-1"><strong>Atas Nama:</strong> PT. Tintaku Store</p>
                            <p class="mb-0"><strong>Jumlah:</strong> Rp {{ number_format($transaksi->total_harga) }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center">
                            <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
                            <button onclick="window.print()" class="btn btn-secondary">Cetak Invoice</button>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection

@push('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #000 !important;
    }
    
    .btn {
        display: none !important;
    }
}
</style>
@endpush
