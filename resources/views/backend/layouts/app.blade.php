<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Tintaku</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body style="background:#F8FAFC;">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a href="#" class="navbar-brand">
            Admin Tintaku
        </a>

        <!-- MENU -->
        <div class="d-flex gap-3 text-white">

            <a href="{{ route('backend.beranda') }}" class="text-white text-decoration-none">
                Beranda
            </a>

            <a href="{{ route('backend.kategori.index') }}" class="text-white text-decoration-none">
                Kategori
            </a>

            <a href="{{ route('backend.produk.index') }}" class="text-white text-decoration-none">
                Produk
            </a>

            <a href="{{ route('backend.transaksi.index') }}" class="text-white text-decoration-none">
                Transaksi
            </a>

            <a href="{{ route('backend.user-customer.index') }}" class="text-white text-decoration-none">
                Customer
            </a>

            <!-- LOGOUT -->
            <a href="#"
                class="text-white text-decoration-none"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                Keluar

            </a>

        </div>

    </div>

</nav>

<!-- CONTENT -->
<div class="container py-5">

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Please fix the following issues:<br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @yield('content')

</div>

<!-- LOGOUT FORM -->
<form id="logout-form"
    action="{{ route('backend.logout') }}"
    method="POST"
    class="d-none">

    @csrf

</form>

</body>

</html>