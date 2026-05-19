<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buat Admin - Tintaku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-3">Buat Akun Admin</h4>

            @if($errors->any())
              <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ url('/admin/create') }}">
              @csrf

              <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="8">
              </div>

              <div class="mb-3">
                <label class="form-label">Setup Key</label>
                <input type="password" name="setup_key" class="form-control" required>
                <div class="form-text">Masukkan nilai `ADMIN_SETUP_KEY` dari file .env</div>
              </div>

              <button class="btn btn-primary w-100" type="submit">Buat Admin</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
