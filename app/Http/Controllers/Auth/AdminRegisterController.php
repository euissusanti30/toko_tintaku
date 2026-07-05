<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\UserAdmin;

class AdminRegisterController extends Controller
{
    /**
     * MENAMPILKAN FORM REGISTRASI ADMIN
     * 
     * Method ini digunakan untuk memuat tampilan halaman pembuatan akun administrator baru.
     * Dapat diakses melalui route: GET /admin/create
     */
    public function create(): View
    {
        return view('auth.create-admin');
    }

    /**
     * MENYIMPAN AKUN ADMIN BARU (DILINDUNGI OLEH SETUP KEY)
     * 
     * Method ini memproses data pendaftaran administrator baru.
     * Untuk mencegah registrasi liar dari publik, pendaftaran ini dilindungi oleh
     * parameter 'setup_key' yang harus cocok dengan nilai 'ADMIN_SETUP_KEY' di file .env.
     * 
     * Dapat diakses melalui route: POST /admin/create
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi input form pendaftaran admin
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user_admins,email',
            'password' => 'required|string|min:8',
            'setup_key' => 'required|string', // Kunci setup wajib diisi
        ]);

        // 2. Mengambil kunci pengaman admin (ADMIN_SETUP_KEY) dari file konfigurasi lingkungan (.env)
        $expected = env('ADMIN_SETUP_KEY');
        
        // 3. KEAMANAN CRITICAL: Cek kesamaan kunci setup yang dikirim form dengan nilai rahasia di .env.
        // Jika kunci kosong atau tidak cocok, proses langsung dihentikan demi keamanan.
        if (empty($expected) || $request->input('setup_key') !== $expected) {
            return back()->withErrors(['setup_key' => 'Setup key tidak valid (tidak cocok dengan ADMIN_SETUP_KEY di .env).'])->withInput();
        }

        // 4. Jika valid, buat akun admin baru di database
        UserAdmin::create([
            'nama' => $request->input('nama'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')), // Password disimpan secara terenkripsi (Bcrypt)
            'role' => 1, // Memberikan role admin default (1)
        ]);

        // 5. Dialihkan ke halaman login admin dengan pesan sukses
        return redirect()->route('admin.login')->with('success', 'Akun admin berhasil dibuat.');
    }
}
