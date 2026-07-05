<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Tintaku</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Font (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #40C0CE;
            --dark: #2D2F39;
            --primary-hover: #2daab8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--dark) 0%, #1a1b21 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            border: none;
        }
        
        .login-header {
            background: white;
            color: var(--dark);
            padding: 40px 30px 25px 30px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .login-header h1 {
            font-size: 26px;
            margin-bottom: 5px;
            font-weight: 700;
            color: var(--dark);
        }

        .login-header h1 span {
            color: var(--primary);
            font-weight: 400;
        }
        
        .login-header p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }
        
        .login-header img {
            height: 65px;
            margin-bottom: 15px;
            object-fit: contain;
        }
        
        .login-body {
            padding: 35px 30px 40px 30px;
        }
        
        .form-group {
            margin-bottom: 22px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #475569;
            font-size: 13px;
        }
        
        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            color: #334155;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(64, 192, 206, 0.15);
        }
        
        .form-control::placeholder {
            color: #94a3b8;
        }
        
        .input-group-icon {
            position: relative;
        }
        
        .input-group-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 16px;
        }
        
        .input-group-icon input {
            padding-left: 48px;
        }
        
        .form-check {
            margin-bottom: 22px;
            display: flex;
            align-items: center;
        }
        
        .form-check-input {
            border-color: #cbd5e1;
            width: 17px;
            height: 17px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-label {
            margin-left: 8px;
            font-size: 13px;
            color: #475569;
            user-select: none;
            cursor: pointer;
            font-weight: 500;
        }
        
        .login-btn {
            width: 100%;
            padding: 13px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        
        .login-btn:hover {
            background: var(--primary-hover);
            box-shadow: 0 4px 12px rgba(64, 192, 206, 0.3);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }

        .create-account-btn {
            width: 100%;
            padding: 12px;
            background: #f8fafc;
            color: var(--dark);
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }
        
        .create-account-btn:hover {
            background: #e2e8f0;
            color: var(--dark);
            border-color: #94a3b8;
        }
        
        .alert {
            border-radius: 12px;
            margin-bottom: 22px;
            font-size: 13px;
            border: none;
            padding: 12px 16px;
        }

        .alert-danger {
            background-color: rgba(255, 90, 95, 0.12);
            color: #b32d30;
        }

        .alert-success {
            background-color: rgba(46, 196, 182, 0.12);
            color: #127c71;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-card">
            
            <!-- Header -->
            <div class="login-header">
                <img src="{{ asset('img/logotintaku.png') }}" alt="Tintaku Logo">
                <h1>Tintaku <span>Admin</span></h1>
                <p>Silahkan login untuk mengakses dashboard admin</p>
            </div>
            
            <!-- Body -->
            <div class="login-body">
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        <strong>Login Gagal!</strong> {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Success Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Login Form -->
                <form method="POST" action="{{ url('/loginadmin') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">
                            Email Address
                        </label>
                        <div class="input-group-icon">
                            <i class="fas fa-envelope"></i>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email"
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="Masukkan email anda"
                                required
                                autofocus>
                        </div>
                        @error('email')
                            <small class="text-danger mt-1 d-block" style="font-size: 11px;">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">
                            Password
                        </label>
                        <div class="input-group-icon">
                            <i class="fas fa-lock"></i>
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password"
                                name="password" 
                                placeholder="Masukkan password anda"
                                required>
                        </div>
                        @error('password')
                            <small class="text-danger mt-1 d-block" style="font-size: 11px;">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="remember" 
                            name="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya di perangkat ini
                        </label>
                    </div>
                    
                    <!-- Login Button -->
                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> Masuk Ke Dashboard
                    </button>

                    <!-- Create Account Button -->
                    <a href="{{ url('/admin/create') }}" class="create-account-btn">
                        <i class="fas fa-user-plus"></i> Buat Akun Admin Baru
                    </a>
                </form>
                
            </div>
            
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
