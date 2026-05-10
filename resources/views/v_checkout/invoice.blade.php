@extends('v_layouts.app')

@section('content')

<section class="py-5">

    <div class="container">

        <div class="card border-0 shadow rounded-4">

            <div class="card-body p-5">

                <h2 class="fw-bold mb-4">
                    Invoice Pesanan
                </h2>

                <hr>

                <h5>
                    Nama :
                    {{ $transaksi->nama_customer }}
                </h5>

                <p>
                    Email :
                    {{ $transaksi->email }}
                </p>

                <p>
                    Telepon :
                    {{ $transaksi->telepon }}
                </p>

                <p>
                    Alamat :
                    {{ $transaksi->alamat }}
                </p>

                <hr>

                <table class="table">

                    <tr>

                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Subtotal</th>

                    </tr>

                    @foreach($detail as $row)

                    <tr>

                        <td>

                            {{ $row->produk_id }}

                        </td>

                        <td>

                            {{ $row->qty }}

                        </td>

                        <td>

                            Rp {{ number_format($row->subtotal) }}

                        </td>

                    </tr>

                    @endforeach

                </table>

                <hr>

                <h3 class="fw-bold">

                    Total :
                    Rp {{ number_format($transaksi->total_harga) }}

                </h3>

                <div class="alert alert-warning mt-4">

                    Silakan lakukan pembayaran terlebih dahulu.

                </div>

            </div>

        </div>

    </div>

</section>

@endsection