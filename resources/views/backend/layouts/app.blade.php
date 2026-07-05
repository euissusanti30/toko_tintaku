<!DOCTYPE html>
<html lang="en" class="h-100">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Tintaku</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Font (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #40C0CE;
            --dark: #2D2F39;
            --primary-hover: #2daab8;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #F5F5F5;
            color: #333333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* TOP HEADER */
        .top-header {
            background: white;
            padding: 16px 0;
            border-bottom: 1px solid #e5e5e5;
        }

        .logo {
            height: 60px;
            width: auto;
            object-fit: contain;
            margin-right: 15px;
        }

        .brand-name {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        .brand-name span {
            color: var(--dark);
            font-weight: 400;
        }

        .brand-subtitle {
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
        }

        /* NAVBAR */
        .main-navbar {
            background: var(--dark);
            padding: 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .main-navbar .nav-link {
            color: #e2e8f0 !important;
            padding: 16px 20px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .main-navbar .nav-link:hover {
            background: var(--primary);
            color: white !important;
        }

        .main-navbar .active-menu {
            background: var(--primary);
            color: white !important;
        }

        /* CARDS */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            margin-bottom: 25px;
            overflow: hidden;
            background: white;
        }

        .card-body {
            padding: 28px;
        }

        .card-title {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 20px;
            font-size: 18px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 12px;
        }

        /* BUTTONS */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 9px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
            color: white !important;
        }

        .btn-success {
            background-color: #2ec4b6;
            border-color: #2ec4b6;
            color: white;
        }

        .btn-success:hover, .btn-success:focus {
            background-color: #25a195;
            border-color: #25a195;
            color: white;
        }

        .btn-warning {
            background-color: #ff9f1c;
            border-color: #ff9f1c;
            color: white;
        }

        .btn-warning:hover, .btn-warning:focus {
            background-color: #e08610;
            border-color: #e08610;
            color: white;
        }

        .btn-danger {
            background-color: #ff5a5f;
            border-color: #ff5a5f;
            color: white;
        }

        .btn-danger:hover, .btn-danger:focus {
            background-color: #e04a4e;
            border-color: #e04a4e;
            color: white;
        }

        .btn-secondary {
            background-color: #e2e8f0;
            border-color: #e2e8f0;
            color: #475569;
        }

        .btn-secondary:hover, .btn-secondary:focus {
            background-color: #cbd5e1;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 13px;
            border-radius: 8px;
        }

        /* TABLES */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: white;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--dark) !important;
            color: white !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            padding: 15px 16px;
            border: none;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 15px 16px;
            vertical-align: middle;
            color: #475569;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(64, 192, 206, 0.03);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table img {
            border: 1px solid #e2e8f0;
            padding: 2px;
            background: white;
            transition: all 0.3s ease;
        }

        .table img:hover {
            transform: scale(1.05);
        }

        /* FORMS */
        .form-control, .form-select, textarea {
            border-radius: 10px;
            padding: 11px 16px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            color: #334155;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(64, 192, 206, 0.15);
            color: #1e293b;
        }

        label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* ALERTS */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            margin-bottom: 25px;
        }

        .alert-success {
            background-color: rgba(46, 196, 182, 0.12);
            color: #127c71;
        }

        .alert-danger {
            background-color: rgba(255, 90, 95, 0.12);
            color: #b32d30;
        }

        .alert-heading {
            font-weight: 700;
        }

        /* BADGES */
        .badge {
            padding: 6px 12px;
            font-weight: 600;
            font-size: 12px;
            border-radius: 30px;
        }

        .bg-danger {
            background-color: rgba(255, 90, 95, 0.15) !important;
            color: #ff5a5f !important;
        }

        .bg-success {
            background-color: rgba(46, 196, 182, 0.15) !important;
            color: #2ec4b6 !important;
        }

        .bg-warning {
            background-color: rgba(255, 159, 28, 0.15) !important;
            color: #ff9f1c !important;
        }

        .bg-info {
            background-color: rgba(64, 192, 206, 0.15) !important;
            color: var(--primary) !important;
        }

        .bg-secondary {
            background-color: #cbd5e1 !important;
            color: #475569 !important;
        }

        /* FOOTER */
        .footer {
            background-color: var(--dark);
            color: #94a3b8;
            font-size: 14px;
            border-top: 1px solid #3e414f;
        }
    </style>

</head>

<body class="h-100">

    <!-- ================= TOP HEADER ================= -->
    <div class="top-header">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- LOGO & BRAND -->
            <div class="d-flex align-items-center">
                <img src="{{ asset('img/logotintaku.png') }}" class="logo" alt="Tintaku Logo">
                <div>
                    <a href="{{ route('backend.beranda') }}" class="brand-name">
                        Tintaku <span>Admin</span>
                    </a>
                    <div class="brand-subtitle">
                        Panel Kontrol Admin #yangadminadminaja
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="d-flex align-items-center gap-3">
                <a href="/" class="btn btn-secondary btn-sm" target="_blank">
                    <i class="fa-solid fa-store me-1"></i> Lihat Toko
                </a>
                <div class="text-end d-none d-md-block border-start ps-3">
                    <small class="text-muted d-block" style="font-size: 10px; letter-spacing: 0.5px; text-transform: uppercase;">Masuk Sebagai</small>
                    <span class="fw-semibold text-dark">{{ auth('admin')->user()->nama ?? 'Admin' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">
            <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('backend.beranda') ? 'active-menu' : '' }}" href="{{ route('backend.beranda') }}">
                            <i class="fa-solid fa-chart-pie me-2"></i>BERANDA
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('backend.kategori.*') ? 'active-menu' : '' }}" href="{{ route('backend.kategori.index') }}">
                            <i class="fa-solid fa-tags me-2"></i>KATEGORI
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('backend.produk.*') ? 'active-menu' : '' }}" href="{{ route('backend.produk.index') }}">
                            <i class="fa-solid fa-box-open me-2"></i>PRODUK
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('backend.transaksi.*') ? 'active-menu' : '' }}" href="{{ route('backend.transaksi.index') }}">
                            <i class="fa-solid fa-receipt me-2"></i>TRANSAKSI
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-danger-hover" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>KELUAR
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ================= CONTENT ================= -->
    <div class="container py-5 flex-grow-1">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="fa-solid fa-triangle-exclamation me-2"></i>Error!</h5>
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

    <!-- ================= FOOTER ================= -->
    <footer class="footer py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} <span style="color: var(--primary); font-weight: 700;">Tintaku</span> Admin. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- LOGOUT FORM -->
    <form id="logout-form" action="{{ route('backend.logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>