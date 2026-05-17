<!DOCTYPE html>
<html lang="en">
<!-- animasi untuk tidak terdaftar akun -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Tintaku</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('login.css') }}">

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

    <div class="login-wrapper">

        <div class="login-card">

            <!-- LEFT -->
            <div class="login-left">

                <div class="login-left-content">

                    <img src="{{ asset('img/logotintaku.png') }}" class="login-logo">

                    <h1>Tintaku</h1>

                    <p>
                        Solusi tinta printer premium dengan kualitas terbaik,
                        harga terjangkau, dan pengiriman cepat ke seluruh Indonesia.
                    </p>

                    <div class="login-features">

                        <div>
                            <i class="fa-solid fa-circle-check"></i>
                            Produk original & berkualitas
                        </div>

                        <div>
                            <i class="fa-solid fa-circle-check"></i>
                            Pengiriman cepat & aman
                        </div>

                        <div>
                            <i class="fa-solid fa-circle-check"></i>
                            Harga terbaik untuk printer
                        </div>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="login-right">

                <div class="login-box">

                    <h2>Login Customer</h2>

                    <p class="login-subtitle">
                        Silahkan login untuk melanjutkan belanja di Tintaku
                    </p>

                    <!-- ERROR -->
                    @if ($errors->any())

                        <div class="alert alert-danger">

                            {{ $errors->first() }}

                        </div>

                    @endif

                    <form method="POST" action="{{ route('login') }}">

                        @csrf

                        <!-- EMAIL -->
                        <div class="mb-3">

                            <label>Email</label>

                            <div class="input-group-custom">

                                <i class="fa-solid fa-envelope"></i>

                                <input type="email" name="email" placeholder="Masukkan email anda" required>

                            </div>

                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">

                            <label>Password</label>

                            <div class="input-group-custom">

                                <i class="fa-solid fa-lock"></i>

                                <input type="password" name="password" placeholder="Masukkan password" required>

                            </div>

                        </div>

                        <!-- REMEMBER -->
                        <div class="remember-me">

                            <input type="checkbox" name="remember">

                            <span>Remember me</span>

                        </div>

                        <!-- BUTTON LOGIN -->
                        <button type="submit" class="login-btn">

                            LOGIN SEKARANG

                        </button>

                        <!-- GOOGLE -->
                        <div class="or-login">

                            <span>OR CONTINUE WITH</span>

                        </div>

                        <a href="{{ route('google.login') }}" class="google-login-btn">

                            <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png">

                            Login with Google

                        </a>

                    </form>

                    <!-- REGISTER -->
                    <div class="register-link">

                        Belum punya akun?

                        <a href="{{ route('register') }}">

                            Daftar Sekarang

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- Pop Up Login Failed -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('google_error'))

        <script>

            Swal.fire({

                icon: 'error',

                title: 'Login Failed',

                text: '{{ session('google_error') }}',

                confirmButtonColor: '#19b5d2',

                background: '#ffffff',

                color: '#0f172a',

                width: '420px',

                padding: '2rem',

                customClass: {

                    popup: 'rounded-4 shadow-lg',

                    confirmButton: 'rounded-pill px-4'

                }

            });

        </script>

    @endif
</body>

</html>