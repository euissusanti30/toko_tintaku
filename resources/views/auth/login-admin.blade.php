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
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }
        
        .login-header img {
            height: 50px;
            margin-bottom: 15px;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-control::placeholder {
            color: #999;
        }
        
        .input-group-icon {
            position: relative;
        }
        
        .input-group-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 16px;
        }
        
        .input-group-icon input {
            padding-left: 45px;
        }
        
        .form-check {
            margin-bottom: 20px;
        }
        
        .form-check-input {
            border-color: #ddd;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-label {
            margin-left: 8px;
            font-size: 14px;
            color: #555;
            user-select: none;
        }
        
        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }

        .create-account-btn {
            width: 100%;
            padding: 12px;
            background: #f0f0f0;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 5px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            margin-top: 10px;
        }
        
        .create-account-btn:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .create-account-btn:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-card">
            
            <!-- Header -->
            <div class="login-header">
                <h1><i class="fas fa-lock"></i> Admin Tintaku</h1>
                <p>Silahkan login untuk mengakses dashboard admin</p>
            </div>
            
            <!-- Body -->
            <div class="login-body">
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Login Gagal!</strong>
                        <div>{{ $errors->first() }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Success Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
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
                            <i class="fas fa-envelope"></i> Email
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
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Password
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
                            <small class="text-danger">{{ $message }}</small>
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
                            Ingat saya
                        </label>
                    </div>
                    
                    <!-- Forgot Password Link intentionally removed for admin manual management -->
                    
                    <!-- Login Button -->
                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>

                    <!-- Create Account Button -->
                    <a href="{{ url('/admin/create') }}" class="create-account-btn">
                        <i class="fas fa-user-plus"></i> Buat Akun Admin
                    </a>
                </form>
                
            </div>
            
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
