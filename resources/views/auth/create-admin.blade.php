<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Admin - Tintaku</title>
    
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
        
        .register-container {
            width: 100%;
            max-width: 500px;
        }
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            border: none;
        }
        
        .register-header {
            background: white;
            color: var(--dark);
            padding: 40px 30px 25px 30px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .register-header h1 {
            font-size: 26px;
            margin-bottom: 5px;
            font-weight: 700;
            color: var(--dark);
        }

        .register-header h1 span {
            color: var(--primary);
            font-weight: 400;
        }
        
        .register-header p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }
        
        .register-header img {
            height: 65px;
            margin-bottom: 15px;
            object-fit: contain;
        }
        
        .register-body {
            padding: 30px 30px 40px 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
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

        .form-text {
            font-size: 11px;
            color: #64748b;
            margin-top: 6px;
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
        
        .register-btn {
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
        
        .register-btn:hover {
            background: var(--primary-hover);
            box-shadow: 0 4px 12px rgba(64, 192, 206, 0.3);
        }

        .back-login-btn {
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
        
        .back-login-btn:hover {
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
    </style>
</head>

<body>

    <div class="register-container">
        <div class="register-card">
            
            <!-- Header -->
            <div class="register-header">
                <img src="{{ asset('img/logotintaku.png') }}" alt="Tintaku Logo">
                <h1>Buat Akun <span>Admin</span></h1>
                <p>Silahkan lengkapi data untuk mendaftarkan akun admin baru</p>
            </div>
            
            <!-- Body -->
            <div class="register-body">
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        <strong>Registrasi Gagal!</strong> {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Register Form -->
                <form method="POST" action="{{ url('/admin/create') }}">
                    @csrf
                    
                    <!-- Nama -->
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <div class="input-group-icon">
                            <i class="fas fa-user"></i>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="nama"
                                name="nama" 
                                value="{{ old('nama') }}"
                                placeholder="Masukkan nama lengkap"
                                required
                                autofocus>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-group-icon">
                            <i class="fas fa-envelope"></i>
                            <input 
                                type="email" 
                                class="form-control" 
                                id="email"
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="Masukkan email aktif"
                                required>
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group-icon">
                            <i class="fas fa-lock"></i>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password"
                                name="password" 
                                placeholder="Masukkan password (min. 8 karakter)"
                                required 
                                minlength="8">
                        </div>
                    </div>
                    
                    <!-- Setup Key -->
                    <div class="form-group">
                        <label for="setup_key">Setup Key (Kunci Keamanan)</label>
                        <div class="input-group-icon">
                            <i class="fas fa-key"></i>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="setup_key"
                                name="setup_key" 
                                placeholder="Masukkan ADMIN_SETUP_KEY"
                                required>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Masukkan nilai kunci keamanan `ADMIN_SETUP_KEY` dari file konfigurasi .env
                        </div>
                    </div>
                    
                    <!-- Register Button -->
                    <button type="submit" class="register-btn">
                        <i class="fas fa-user-plus"></i> Daftarkan Admin
                    </button>

                    <!-- Back to Login Button -->
                    <a href="{{ url('/loginadmin') }}" class="back-login-btn">
                        <i class="fas fa-arrow-left"></i> Kembali ke Halaman Login
                    </a>
                </form>
                
            </div>
            
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
