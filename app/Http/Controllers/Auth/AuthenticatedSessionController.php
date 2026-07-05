<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CONTROLLER: AUTENTIKASI SESSION (LOGIN & LOGOUT CUSTOMER)
 *
 * Mengelola proses login dan logout untuk pengguna/customer.
 * Setelah login, admin diarahkan ke dashboard backend,
 * sedangkan customer biasa diarahkan ke halaman utama.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * TAMPILKAN HALAMAN LOGIN
     *
     * Menampilkan form login untuk customer.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * PROSES LOGIN (AUTENTIKASI USER)
     *
     * Memvalidasi kredensial email & password menggunakan LoginRequest.
     * Setelah berhasil, cek role user:
     * - role = 1 (admin)  -> redirect ke dashboard admin (/backend/beranda)
     * - role = 0 (customer) -> redirect ke halaman utama (/)
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // authenticate(): validasi email & password, throw exception jika salah
        $request->authenticate();

        // regenerate(): buat session ID baru untuk mencegah session fixation attack
        $request->session()->regenerate();

        // Cek role user yang berhasil login
        if(auth()->user()->role == 1){
            // Admin -> arahkan ke dashboard backend
            return redirect('/backend/beranda');
        }

        // Customer biasa -> arahkan ke halaman utama toko
        return redirect('/');
    }

    /**
     * PROSES LOGOUT (AKHIRI SESSION)
     *
     * Menghapus session login user secara aman:
     * 1. Logout dari guard 'web'
     * 2. Invalidate session agar tidak bisa dipakai lagi
     * 3. Regenerate CSRF token untuk keamanan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout dari guard 'web' (guard default untuk customer)
        Auth::guard('web')->logout();

        // Hancurkan semua data session yang ada
        $request->session()->invalidate();

        // Buat ulang CSRF token untuk mencegah CSRF attack setelah logout
        $request->session()->regenerateToken();

        // Arahkan ke halaman utama setelah logout
        return redirect('/');
    }
}
