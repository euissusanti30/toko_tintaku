<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tintaku - Toko Tinta</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- footer -->
    <link rel="stylesheet" href="{{ asset('footer.css') }}">
    <!-- header -->
    <link rel="stylesheet" href="{{ asset('header.css') }}">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #F5F5F5;
        }

        :root {
            --primary: #40C0CE;
            --dark: #2D2F39;
        }

        /* HEADER */
        .top-header {
            background: white;
            padding: 18px 0;
            border-bottom: 1px solid #e5e5e5;
        }

        .logo {
            height: 75px;
            width: auto;
            object-fit: contain;
        }

        .brand-name {
            font-size: 42px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        /* ICON AREA */
        .top-icons a {
            font-size: 22px;
            color: #2D2F39;
            margin-left: 18px;
            position: relative;
            transition: 0.3s;
        }

        .top-icons a:hover {
            color: var(--primary);
        }

        .badge-cart {
            font-size: 11px;
        }

        /* NAVBAR */
        .main-navbar {
            background: var(--dark);
            padding: 0;
        }

        .navbar-nav {
            align-items: center;
        }

        .main-navbar .nav-link {
            color: white !important;
            padding: 16px 18px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .main-navbar .nav-link:hover {
            background: var(--primary);
            color: white !important;
        }

        .kategori-btn {
            background: var(--primary);
            min-width: 220px;
            justify-content: center;
        }

        /* SIDEBAR */
        .sidebar-card {
            background: white;
            border-radius: 16px;
            padding: 22px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 10px;
        }

        .top-product-item {
            display: flex;
            gap: 12px;
            margin-bottom: 15px;
        }

        .top-product-item img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
        }

        .category-list a {
            display: block;
            text-decoration: none;
            color: #333;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .category-list a:hover {
            color: var(--primary);
            transform: translateX(5px);
        }

        /* PRODUCT */
        .product-card {
            background: white;
            border: none;
            border-radius: 18px;
            overflow: hidden;
            transition: 0.3s;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            height: 230px;
            object-fit: cover;
        }

        .price {
            color: var(--primary);
            font-size: 20px;
            font-weight: 700;
        }

        .btn-tintaku {
            background: var(--primary);
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 16px;
            border: none;
        }

        .btn-tintaku:hover {
            background: #2daab8;
            color: white;
        }
    </style>

</head>

<body>

    <!-- ================= TOP HEADER ================= -->

    <div class="top-header">

        <div class="top-header-pattern"></div>

        <div class="container d-flex justify-content-between align-items-center">

            <!-- LOGO -->

            <div class="header-brand d-flex align-items-center">

                <img src="{{ asset('img/logotintaku.png') }}" class="logo">

                <div>

                    <a href="/" class="brand-name">
                        Tintaku
                    </a>

                    <div class="brand-subtitle">
                        E-commerce Toko Tintaku Premium
                    </div>

                </div>

            </div>

            <!-- SEARCH -->

            <div class="header-search">

                <form action="/search" method="GET">

                    <input type="text" name="search" placeholder="Cari tinta printer...">

                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                </form>

            </div>

            <!-- ICON -->

            <div class="top-icons d-flex align-items-center">

                @php
                    $cart = session('cart');
                    $count = $cart ? count($cart) : 0;
                @endphp

                <!-- CART -->

                <a href="/cart" class="header-icon">

                    <i class="fa-solid fa-cart-shopping"></i>

                    @if($count > 0)

                        <span class="cart-badge">
                            {{ $count }}
                        </span>

                    @endif

                </a>

                <!-- ACCOUNT -->

                <div class="dropdown">

                    <button class="account-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <i class="fa-solid fa-user"></i>

                        <div class="account-text">

                            @auth

                                <small>Welcome</small>

                                <span>{{ Str::limit(Auth::user()->name, 20) }}</span>

                            @else

                                <small>Sign In</small>

                                <span>Account</span>

                            @endauth

                        </div>

                    </button>

                    <ul class="dropdown-menu dropdown-menu-end account-dropdown">

                        @guest

                            <li class="account-header">

                                <small>MY ACCOUNT</small>

                                <h6>LOGIN / REGISTER</h6>

                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>

                                <<a class="dropdown-item" href="{{ route('login') }}">

                                    <i class="fa-solid fa-right-to-bracket"></i>

                                    Login

                                    </a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ route('register') }}">

                                    <i class="fa-solid fa-user-plus"></i>

                                    Create Account

                                </a>

                            </li>

                        @endguest

                        @auth

                            @if(Auth::user()->role == 1)

                                <li>

                                    <a class="dropdown-item" href="/backend/beranda">

                                        <i class="fa-solid fa-table-columns"></i>

                                        Dashboard Admin

                                    </a>

                                </li>

                            @endif
                            <!-- LOGOUT -->

                            <li>

                                <form action="{{ route('backend.logout') }}" method="POST">

                                    @csrf

                                    <button type="submit" class="dropdown-item logout-item">

                                        <i class="fa-solid fa-right-from-bracket"></i>

                                        Logout

                                    </button>

                                </form>

                            </li>

                            <!-- DELETE ACCOUNT -->

                            <li>

                                <button type="button" class="delete-account-item" onclick="confirmDelete()">

                                    <i class="fa-solid fa-trash"></i>

                                    Delete Account

                                </button>

                            </li>

                            <!-- HIDDEN DELETE FORM -->

                            <form id="delete-form" action="{{ route('account.delete') }}" method="post"
                                style="display:none;">

                                @csrf

                                @method('DELETE')

                            </form>

                        @endauth

                    </ul>

                </div>

            </div>

        </div>

    </div>
    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg main-navbar">

        <div class="container">

            <button class="navbar-toggler bg-white" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="nav">

                <ul class="navbar-nav me-auto">

                    <li class="nav-item dropdown">
                        <a class="nav-link kategori-btn dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-bars me-2"></i>
                            KATEGORI
                        </a>

                        <ul class="dropdown-menu">
                            @if(isset($kategori))

                                @foreach($kategori as $item)

                                    <li>

                                        <a class="dropdown-item" href="/kategori/{{ $item->id }}">

                                            {{ $item->nama_kategori }}

                                        </a>

                                    </li>

                                @endforeach

                            @endif
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="/">BERANDA</a></li>
                    <li class="nav-item"><a class="nav-link" href="/shop">PRODUK</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">LOKASI</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">HUBUNGI KAMI</a></li>

                </ul>

            </div>

        </div>
    </nav>

    <!-- ================= CONTENT ================= -->
    <section class="py-4">
        <div class="container">
            <div class="row">

                <div class="col-lg-3">

                    <div class="sidebar-card">
                        <h5 class="sidebar-title">PRODUK TERBAIK</h5>
                        <div class="top-product-item">
                            <img src="{{ asset('banner/TintaEpson.jpg') }}">
                            <div>
                                <h6>Tinta Epson</h6>
                                <span class="price">Rp120.000</span>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-card">
                        <h5 class="sidebar-title">FILTER KATEGORI</h5>

                        <div class="category-list">
                            @foreach($kategori as $item)
                                <a href="/kategori/{{ $item->id }}">
                                    {{ $item->nama_kategori }}
                                </a>
                            @endforeach
                        </div>

                    </div>

                </div>

                <div class="col-lg-9">
                    @yield('content')
                </div>

            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->

    <footer class="premium-footer">

        <div class="container">

            <div class="footer-grid">

                <!-- BRAND -->

                <div class="footer-brand">

                    <div class="footer-logo-box">
                        <img src="{{ asset('img/logotintaku.png') }}" alt="Tintaku" class="footer-logo">
                    </div>

                    <div>

                        <h2 class="footer-title">Tintaku</h2>

                        <p class="footer-desc">
                            Toko tinta terpercaya dengan berbagai pilihan tinta printer
                            berkualitas dan harga terbaik.
                        </p>

                    </div>

                </div>

                <!-- MENU -->

                <div class="footer-menu">

                    <h4>Menu</h4>

                    <ul>

                        <li><a href="/">Beranda</a></li>
                        <li><a href="/produk">Produk</a></li>
                        <li><a href="/kategori">Kategori</a></li>
                        <li><a href="/kontak">Kontak</a></li>

                    </ul>

                </div>

                <!-- NEWSLETTER -->

                <div class="footer-newsletter">

                    <h4>Newsletter</h4>

                    <p>
                        Dapatkan promo dan info produk terbaru dari Tintaku.
                    </p>

                    <form>

                        <input type="email" placeholder="Masukkan Email">

                        <button type="submit">
                            Subscribe
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        function confirmDelete() {

            Swal.fire({

                title: 'Delete Account?',

                text: 'Akun yang dihapus tidak dapat dikembalikan.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Yes, Delete',

                cancelButtonText: 'Cancel',

                reverseButtons: true,

                background: '#ffffff',

                color: '#0f172a',

                confirmButtonColor: '#e63946',

                cancelButtonColor: '#64748b',

                width: '420px',

                padding: '2rem',

                borderRadius: '24px',

                backdrop: `
                rgba(15,23,42,0.65)
            `,

                customClass: {

                    popup: 'delete-popup',

                    title: 'delete-title',

                    htmlContainer: 'delete-text',

                    confirmButton: 'delete-confirm-btn',

                    cancelButton: 'delete-cancel-btn'

                }

            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({

                        icon: 'success',

                        title: 'Deleted!',

                        text: 'Account berhasil dihapus.',

                        confirmButtonColor: '#19b5d2',

                        background: '#ffffff',

                        color: '#0f172a',

                        timer: 2000,

                        showConfirmButton: false,

                        backdrop: `
                                rgba(15,23,42,0.78)
                            `,
                    });

                    setTimeout(() => {

                        document.getElementById('delete-form').submit();

                    }, 1800);

                }

            });

        }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />


    @if(session('error'))

        <script>

            Swal.fire({

                icon: 'error',

                title: 'Login Failed',

                text: "{{ session('error') }}",

                confirmButtonColor: '#19b5d2',

                background: '#fff',

                color: '#0f172a',

                backdrop: 'rgba(15,23,42,.7)',

                showClass: {

                    popup: 'animate__animated animate__fadeInDown'

                },

                hideClass: {

                    popup: 'animate__animated animate__fadeOutUp'

                }

            });

        </script>

    @endif
</body>

</html>