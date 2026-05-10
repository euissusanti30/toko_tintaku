<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tintaku - Toko Tinta</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        *{
            font-family:'Poppins',sans-serif;
        }

        body{
            background:#F5F5F5;
        }

        :root{
            --primary:#40C0CE;
            --dark:#2D2F39;
        }

        /* HEADER */
        .top-header{
            background:white;
            padding:18px 0;
            border-bottom:1px solid #e5e5e5;
        }

        .logo{
            height:75px;
            width:auto;
            object-fit:contain;
        }

        .brand-name{
            font-size:42px;
            font-weight:700;
            color:var(--primary);
            text-decoration:none;
        }

        /* ICON AREA */
        .top-icons a{
            font-size:22px;
            color:#2D2F39;
            margin-left:18px;
            position:relative;
            transition:0.3s;
        }

        .top-icons a:hover{
            color:var(--primary);
        }

        .badge-cart{
            font-size:11px;
        }

        /* NAVBAR */
        .main-navbar{
            background:var(--dark);
            padding:0;
        }

        .navbar-nav{
            align-items:center;
        }

        .main-navbar .nav-link{
            color:white !important;
            padding:16px 18px;
            font-size:14px;
            font-weight:600;
            transition:0.3s;
        }

        .main-navbar .nav-link:hover{
            background:var(--primary);
            color:white !important;
        }

        .kategori-btn{
            background:var(--primary);
            min-width:220px;
            justify-content:center;
        }

        /* SIDEBAR */
        .sidebar-card{
            background:white;
            border-radius:16px;
            padding:22px;
            margin-bottom:25px;
            box-shadow:0 3px 15px rgba(0,0,0,0.05);
        }

        .sidebar-title{
            font-size:18px;
            font-weight:700;
            margin-bottom:15px;
            border-bottom:3px solid var(--primary);
            padding-bottom:10px;
        }

        .top-product-item{
            display:flex;
            gap:12px;
            margin-bottom:15px;
        }

        .top-product-item img{
            width:70px;
            height:70px;
            object-fit:cover;
            border-radius:12px;
        }

        .category-list a{
            display:block;
            text-decoration:none;
            color:#333;
            margin-bottom:10px;
            transition:0.3s;
        }

        .category-list a:hover{
            color:var(--primary);
            transform:translateX(5px);
        }

        /* PRODUCT */
        .product-card{
            background:white;
            border:none;
            border-radius:18px;
            overflow:hidden;
            transition:0.3s;
            box-shadow:0 3px 15px rgba(0,0,0,0.05);
        }

        .product-card:hover{
            transform:translateY(-5px);
        }

        .product-card img{
            height:230px;
            object-fit:cover;
        }

        .price{
            color:var(--primary);
            font-size:20px;
            font-weight:700;
        }

        .btn-tintaku{
            background:var(--primary);
            color:white;
            font-weight:600;
            border-radius:10px;
            padding:10px 16px;
            border:none;
        }

        .btn-tintaku:hover{
            background:#2daab8;
            color:white;
        }

        footer{
            background:var(--dark);
            color:white;
            padding:40px;
            margin-top:60px;
        }

    </style>

</head>

<body>

<!-- ================= TOP HEADER ================= -->
<div class="top-header">
    <div class="container d-flex justify-content-between align-items-center">

        <!-- LOGO -->
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logotintaku.png') }}" class="logo me-3">
            <a href="/" class="brand-name">Tintaku</a>
        </div>

        <!-- CART + AKUN -->
        <div class="top-icons d-flex align-items-center">

            @php
                $cart = session('cart');
                $count = $cart ? count($cart) : 0;
            @endphp

            <!-- CART -->
            <a href="/cart">
                <i class="fa-solid fa-cart-shopping"></i>

                @if($count > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge bg-danger badge-cart">
                    {{ $count }}
                </span>
                @endif
            </a>

            <!-- AKUN DROPDOWN -->
            <div class="dropdown ms-3">

                <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                   href="#"
                   data-bs-toggle="dropdown"
                   style="font-weight:600; font-size:18px;">

                    <i class="fa-solid fa-user me-2"></i>
                    AKUN

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow">

                    @guest
                        <li>
                            <a class="dropdown-item" href="/login">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>
                                Login
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="/register">
                                <i class="fa-solid fa-user-plus me-2"></i>
                                Register
                            </a>
                        </li>
                    @endguest

                    @auth
                        <li>
                            <a class="dropdown-item" href="/dashboard">
                                Dashboard
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item text-danger" href="/logout">
                                Logout
                            </a>
                        </li>
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
                        @foreach($kategori as $item)
                        <li>
                            <a class="dropdown-item" href="/kategori/{{ $item->id }}">
                                {{ $item->nama_kategori }}
                            </a>
                        </li>
                        @endforeach
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
<footer class="text-center">
    <h5>Tintaku</h5>
    <p>Ecommerce Toko Tinta</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>