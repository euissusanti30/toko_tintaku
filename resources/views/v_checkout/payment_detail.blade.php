@extends('v_layouts.app')

@section('content')
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
        <div class="rounded-circle bg-info bg-opacity-10 p-2 text-info me-3">
            <i class="fa-solid fa-file-invoice-dollar fa-lg"></i>
        </div>
        <h5 class="mb-0 fw-bold text-dark">Rincian Pembayaran</h5>
    </div>
    
    <div class="card-body p-4">
        <!-- Order Header info -->
        <div class="row g-4 mb-4 border-bottom pb-4">
            <div class="col-md-6">
                <span class="text-muted d-block mb-1 text-uppercase small fw-bold">Nomor Pesanan</span>
                <h4 class="fw-bold text-dark">#{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</h4>
                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mt-2">
                    <i class="fa-solid fa-clock me-1"></i> {{ ucfirst($transaksi->status) }}
                </span>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="text-muted d-block mb-1 text-uppercase small fw-bold">Tanggal Pemesanan</span>
                <p class="mb-0 fw-semibold text-dark">{{ $transaksi->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>

        <!-- Customer & Shipping info -->
        <div class="row g-4 mb-4 border-bottom pb-4">
            <div class="col-md-6">
                <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-user me-2 text-info"></i>Informasi Customer</h6>
                <div class="ps-4">
                    <p class="mb-1 fw-semibold text-dark">{{ $transaksi->nama_customer }}</p>
                    <p class="mb-1 text-muted"><i class="fa-solid fa-envelope me-2 small"></i>{{ $transaksi->email }}</p>
                    <p class="mb-0 text-muted"><i class="fa-solid fa-phone me-2 small"></i>{{ $transaksi->telepon }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-truck me-2 text-info"></i>Alamat Pengiriman</h6>
                <div class="ps-4">
                    <p class="mb-0 text-muted leading-relaxed">{{ $transaksi->alamat }}</p>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-basket-shopping me-2 text-info"></i>Item Pesanan</h6>
        <div class="table-responsive mb-4">
            <table class="table align-middle">
                <thead>
                    <tr class="table-light">
                        <th scope="col" class="border-0 rounded-start-3">Produk</th>
                        <th scope="col" class="border-0 text-center">Qty</th>
                        <th scope="col" class="border-0 text-end rounded-end-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotal = 0; @endphp
                    @foreach($transaksi->detailTransaksi as $row)
                    @php $subtotal += $row->subtotal; @endphp
                    <tr>
                        <td class="border-0 py-3">
                            <div class="d-flex align-items-center">
                                @if($row->produk && $row->produk->foto)
                                    <img src="{{ asset('storage/' . $row->produk->foto) }}" 
                                         alt="{{ $row->produk->nama_produk }}" 
                                         class="rounded-3 me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @elseif($row->produk && $row->produk->foto_url)
                                    <img src="{{ $row->produk->foto_url }}" 
                                         alt="{{ $row->produk->nama_produk }}" 
                                         class="rounded-3 me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="rounded-3 bg-secondary bg-opacity-10 me-3 d-flex align-items-center justify-content-center text-secondary" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-semibold text-dark">{{ $row->produk->nama_produk ?? 'Produk tidak ditemukan' }}</h6>
                                    <small class="text-muted">Rp {{ number_format($row->harga) }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="border-0 text-center py-3 fw-semibold text-dark">{{ $row->qty }}</td>
                        <td class="border-0 text-end py-3 fw-semibold text-dark">Rp {{ number_format($row->subtotal) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Price Details Summary -->
        <div class="bg-light p-4 rounded-4 mb-4">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Subtotal Produk</span>
                <span class="fw-semibold text-dark">Rp {{ number_format($subtotal) }}</span>
            </div>
            @if($transaksi->total_harga - $subtotal > 0)
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Ongkos Kirim</span>
                <span class="fw-semibold text-dark">Rp {{ number_format($transaksi->total_harga - $subtotal) }}</span>
            </div>
            @endif
            <hr class="my-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-dark fw-bold h5 mb-0">Total Pembayaran</span>
                <span class="text-info fw-bold h4 mb-0">Rp {{ number_format($transaksi->total_harga) }}</span>
            </div>
        </div>

        <!-- Payment Button -->
        <div class="text-center pt-2">
            <button id="pay-button" class="btn btn-info btn-lg w-100 text-white fw-bold py-3 rounded-3 shadow-sm transition">
                <i class="fa-solid fa-credit-card me-2"></i> Bayar Sekarang
            </button>
        </div>
    </div>
</div>

{{-- Midtrans Snap JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function (e) {
        e.preventDefault();
        
        // Open Midtrans Snap popup
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                console.log(result);
                // Redirect to payment-success route
                window.location.href = "{{ route('frontend.payment.success', $transaksi->id) }}";
            },
            onPending: function(result){
                console.log(result);
                alert("Menunggu pembayaran! Silakan selesaikan pembayaran Anda.");
                window.location.href = "{{ url('/invoice/' . $transaksi->id) }}";
            },
            onError: function(result){
                console.log(result);
                alert("Pembayaran gagal! Silakan coba lagi.");
            },
            onClose: function(){
                console.log('customer closed the popup without finishing the payment');
            }
        });
    };
</script>

<style>
    .transition {
        transition: all 0.25s ease-in-out;
    }
    .btn-info:hover {
        background-color: #2daab8 !important;
        border-color: #2daab8 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(45, 170, 184, 0.2) !important;
    }
    .leading-relaxed {
        line-height: 1.6;
    }
</style>
@endsection
