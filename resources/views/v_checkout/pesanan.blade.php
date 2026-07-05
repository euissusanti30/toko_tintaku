@extends('v_layouts.app')

@section('content')
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
        <div class="rounded-circle bg-info bg-opacity-10 p-2 text-info me-3">
            <i class="fa-solid fa-box-open fa-lg"></i>
        </div>
        <h5 class="mb-0 fw-bold text-dark">Daftar Pesanan Anda</h5>
    </div>

    <div class="card-body p-4">
        @if($transaksi->isEmpty())
            <div class="text-center py-5">
                <div class="rounded-circle bg-light d-inline-flex p-4 text-muted mb-4 shadow-sm">
                    <i class="fa-solid fa-basket-shopping fa-3x"></i>
                </div>
                <h5 class="fw-bold text-dark">Belum Ada Pesanan</h5>
                <p class="text-muted mb-4">Anda belum memiliki riwayat pesanan aktif. Silakan pilih produk terbaik kami.</p>
                <a href="/shop" class="btn btn-info text-white fw-bold px-4 py-2 rounded-pill shadow-sm transition-hover">
                    <i class="fa-solid fa-store me-2"></i> Belanja Sekarang
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="table-light text-uppercase small text-muted">
                            <th scope="col" class="border-0 rounded-start-3 py-3">ID Pesanan</th>
                            <th scope="col" class="border-0 py-3">Tanggal</th>
                            <th scope="col" class="border-0 py-3">Item Produk</th>
                            <th scope="col" class="border-0 py-3 text-end">Total Bayar</th>
                            <th scope="col" class="border-0 py-3 text-center">Status</th>
                            <th scope="col" class="border-0 rounded-end-3 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $row)
                            <tr class="border-bottom">
                                <td class="py-4 fw-bold text-dark">
                                    #{{ str_pad($row->id, 6, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-4 text-muted" style="font-size: 0.9rem;">
                                    {{ $row->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-4" style="max-width: 250px;">
                                    @foreach($row->detailTransaksi as $detail)
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="badge bg-secondary bg-opacity-10 text-dark me-2 small">{{ $detail->qty }}x</span>
                                            <span class="text-truncate text-dark small" style="max-width: 180px;">
                                                {{ $detail->produk->nama_produk ?? 'Produk tidak ditemukan' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="py-4 text-end fw-semibold text-info">
                                    Rp {{ number_format($row->total_harga) }}
                                </td>
                                <td class="py-4 text-center">
                                    @switch($row->status)
                                        @case('belum bayar')
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill small fw-bold">
                                                Belum Bayar
                                            </span>
                                            @break
                                        @case('sudah bayar')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small fw-bold">
                                                Sudah Bayar
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill small fw-bold">
                                                Pending
                                            </span>
                                            @break
                                        @case('proses')
                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill small fw-bold">
                                                Diproses
                                            </span>
                                            @break
                                        @case('selesai')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small fw-bold">
                                                Selesai
                                            </span>
                                            @break
                                        @case('batal')
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill small fw-bold">
                                                Dibatalkan
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill small fw-bold">
                                                {{ ucfirst($row->status) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="py-4 text-center">
                                    @if($row->status === 'belum bayar')
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('frontend.payment.detail', $row->id) }}" class="btn btn-danger btn-sm text-white px-3 py-2 rounded-3 fw-bold shadow-sm transition-hover">
                                                <i class="fa-solid fa-credit-card me-1"></i> Bayar
                                            </a>
                                            <form action="{{ route('frontend.pesanan.batal', $row->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 fw-bold btn-cancel-order transition-hover">
                                                    <i class="fa-solid fa-xmark me-1"></i> Batalkan
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <a href="{{ url('/invoice/' . $row->id) }}" class="btn btn-outline-info btn-sm px-3 py-2 rounded-3 fw-bold transition-hover">
                                            <i class="fa-solid fa-file-invoice me-1"></i> Invoice
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
    .transition-hover {
        transition: all 0.2s ease-in-out;
    }
    .transition-hover:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08) !important;
    }
    .btn-info:hover {
        background-color: #2daab8 !important;
        border-color: #2daab8 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cancelButtons = document.querySelectorAll('.btn-cancel-order');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Batalkan Pesanan?',
                    text: "Apakah Anda yakin ingin membatalkan pesanan ini? Pesanan akan dihapus dari database secara permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Kembali',
                    background: '#fff',
                    color: '#0f172a',
                    backdrop: 'rgba(15,23,42,.7)'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
