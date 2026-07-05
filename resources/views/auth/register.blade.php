<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="{{ asset('register.css') }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register Tintaku</title>

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

    <div class="register-wrapper">

        <div class="register-card">

            <!-- LEFT -->
            <div class="register-left">

                <div>

                    <img src="{{ asset('img/logotintaku.png') }}" class="register-logo">

                    <h1>Join Tintaku</h1>

                    <p>
                        Daftarkan akun anda dan nikmati pengalaman
                        belanja tinta printer premium dengan mudah,
                        cepat, dan terpercaya.
                    </p>

                    <div class="register-features">

                        <div>
                            <i class="fa-solid fa-circle-check"></i>
                            Produk original & berkualitas
                        </div>

                        <div>
                            <i class="fa-solid fa-circle-check"></i>
                            Pengiriman cepat ke seluruh Indonesia
                        </div>

                        <div>
                            <i class="fa-solid fa-circle-check"></i>
                            Harga terbaik & terpercaya
                        </div>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="register-right">

                <div class="form-box">

                    <h2>Create Account</h2>

                    <p class="subtitle">
                        Silahkan buat akun untuk mulai berbelanja di Tintaku
                    </p>

                    <!-- ERROR -->
                    @if ($errors->any())

                        <div class="alert alert-danger">

                            {{ $errors->first() }}

                        </div>

                    @endif

                    <form method="POST" action="{{ route('register') }}">

                        @csrf

                        <!-- NAME -->
                        <div class="mb-4">

                            <label>Full Name</label>

                            <div class="input-group-custom">

                                <i class="fa-solid fa-user"></i>

                                <input type="text" name="name" placeholder="Masukkan nama lengkap" required>

                            </div>

                        </div>

                        <!-- EMAIL -->
                        <div class="mb-4">

                            <label>Email</label>

                            <div class="input-group-custom">

                                <i class="fa-solid fa-envelope"></i>

                                <input type="email" name="email" placeholder="Masukkan email" required>

                            </div>

                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-4">

                            <label>Password</label>

                            <div class="input-group-custom">

                                <i class="fa-solid fa-lock"></i>

                                <input type="password" name="password" placeholder="Masukkan password" required>

                            </div>

                        </div>

                        <!-- CONFIRM -->
                        <div class="mb-4">

                            <label>Confirm Password</label>

                            <div class="input-group-custom">

                                <i class="fa-solid fa-shield-halved"></i>

                                <input type="password" name="password_confirmation" placeholder="Konfirmasi password"
                                    required>

                            </div>

                        </div>

                        <!-- BUTTON -->
                        <button class="register-btn">

                            CREATE ACCOUNT

                        </button>
                        <!-- GOOGLE -->
                        <div class="or-register">

                            <span>OR CONTINUE WITH</span>

                        </div>

                        <a href="{{ route('google.register') }}" class="google-login-btn">

                            <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Google">

                            Register with Google

                        </a>
                        </a>

                    </form>

                    <div class="login-link">

                        Already have an account?

                        <a href="{{ route('login') }}">
                            Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
    @if(session('success'))

        <script>

            Swal.fire({

                icon: 'success',

                title: 'Berhasil!',

                text: '{{ session('success') }}',

                confirmButtonColor: '#19b5d2',

                background: '#ffffff',

                color: '#0f172a',

            });

        </script>

    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>