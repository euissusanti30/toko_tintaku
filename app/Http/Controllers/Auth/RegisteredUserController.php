<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * CONTROLLER: REGISTRASI CUSTOMER BARU
 *
 * Menangani proses pendaftaran akun baru untuk customer.
 * Password di-hash dengan bcrypt sebelum disimpan ke database.
 */
class RegisteredUserController extends Controller
{
    /**
     * TAMPILKAN HALAMAN REGISTRASI
     *
     * Menampilkan form pendaftaran akun baru untuk customer.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * PROSES PENDAFTARAN AKUN BARU
     *
     * Memvalidasi data input, membuat akun customer baru,
     * lalu redirect ke halaman login dengan pesan selamat datang.
     *
     * Aturan validasi:
     * - name     : wajib, string, maks 255 karakter
     * - email    : wajib, format email valid, unik di tabel users
     * - password : wajib, dikonfirmasi (password_confirmation), sesuai aturan default Laravel
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi semua input dari form registrasi
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:'.User::class], // Email harus unik
            'password' => ['required', 'confirmed', Rules\Password::defaults()],             // Wajib dikonfirmasi
        ]);

        // Buat akun customer baru di database
        // - role = 0 : customer biasa (bukan admin)
        // - password di-hash dengan bcrypt menggunakan Hash::make()
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => 0,                          // 0 = customer, 1 = admin
            'password' => Hash::make($request->password), // Hash password sebelum disimpan
        ]);

        // Trigger event Registered (bisa digunakan untuk kirim email verifikasi)
        event(new Registered($user));

        // Redirect ke halaman login dengan pesan selamat datang
        return redirect('/login')
            ->with('success', 'Selamat datang di Tintaku!');
    }
}
