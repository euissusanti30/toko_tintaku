@extends('v_layouts.app')

@section('content')

<section class="py-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-5">

                        <h2 class="fw-bold mb-4">
                            Checkout
                        </h2>

                        <form action="/checkout/store"
                            method="POST">

                            @csrf

                            <div class="mb-3">

                                <label>Nama Lengkap</label>

                                <input type="text"
                                    name="nama_customer"
                                    class="form-control">

                            </div>

                            <div class="mb-3">

                                <label>Email</label>

                                <input type="email"
                                    name="email"
                                    class="form-control">

                            </div>

                            <div class="mb-3">

                                <label>No Telepon</label>

                                <input type="text"
                                    name="telepon"
                                    class="form-control">

                            </div>

                            <div class="mb-3">

                                <label>Alamat Lengkap</label>

                                <textarea name="alamat"
                                    class="form-control"
                                    rows="5"></textarea>

                            </div>

                            <button class="btn btn-tintaku w-100">

                                Buat Pesanan

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection