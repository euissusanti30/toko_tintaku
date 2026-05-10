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

            <a href="/backend/dashboard" class="text-white text-decoration-none">
                Beranda
            </a>

            <a href="/backend/user" class="text-white text-decoration-none">
                User
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