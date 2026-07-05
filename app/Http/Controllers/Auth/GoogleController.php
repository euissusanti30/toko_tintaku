<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * CONTROLLER: LOGIN & REGISTER DENGAN GOOGLE OAUTH
 *
 * Menangani proses autentikasi menggunakan akun Google melalui
 * Laravel Socialite. Mendukung dua alur:
 * 1. Login Google  : hanya untuk akun yang sudah terdaftar
 * 2. Register Google : membuat akun baru dari data profil Google
 */
class GoogleController extends Controller
{
    // ==========================================================================
    // LOGIN DENGAN GOOGLE
    // ==========================================================================

    /**
     * REDIRECT KE HALAMAN LOGIN GOOGLE
     *
     * Mengarahkan pengguna ke halaman persetujuan (consent screen) Google
     * untuk memilih akun dan memberikan izin akses.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginGoogle()
    {
        // redirectUrl(): tentukan URL callback setelah Google selesai memproses
        // redirect(): arahkan browser ke Google OAuth
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect_login'))
            ->redirect();
    }

    /**
     * HANDLE CALLBACK LOGIN GOOGLE
     *
     * Dieksekusi setelah pengguna memilih akun Google dan memberikan izin.
     * Ambil data user dari Google, cek apakah email sudah terdaftar,
     * lalu login jika ada atau tampilkan error jika belum terdaftar.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleLogin()
    {
        try {
            // Ambil data profil user dari Google (nama, email, foto, dll)
            $googleUser = Socialite::driver('google')
                ->redirectUrl(config('services.google.redirect_login'))
                ->user();

            // Cari akun di database berdasarkan email Google
            $user = User::where('email', $googleUser->email)->first();

            // Jika email belum terdaftar di database -> tolak login
            if (!$user) {
                return redirect('/login')
                    ->with('google_error', 'Akun tidak terdaftar.');
            }

            // Login user ke dalam aplikasi
            // true = remember me (session bertahan lama)
            Auth::login($user, true);

            // Regenerate session ID untuk mencegah session fixation attack
            request()->session()->regenerate();

            // Redirect ke halaman utama setelah login berhasil
            return redirect('/');

        } catch (\Exception $e) {
            // Jika terjadi error saat komunikasi dengan Google
            return redirect('/login')
                ->with('google_error', 'Login Google gagal.');
        }
    }

    // ==========================================================================
    // REGISTER DENGAN GOOGLE
    // ==========================================================================

    /**
     * REDIRECT KE HALAMAN REGISTER GOOGLE
     *
     * Mengarahkan pengguna ke halaman persetujuan Google untuk
     * mendaftar akun baru menggunakan profil Google mereka.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect_register'))
            ->redirect();
    }

    /**
     * HANDLE CALLBACK REGISTER GOOGLE
     *
     * Dieksekusi setelah pengguna menyetujui akses dari Google.
     * Cek apakah email sudah ada di database:
     * - Jika sudah ada -> langsung login (tidak buat akun baru)
     * - Jika belum ada -> buat akun baru lalu login
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleRegister()
    {
        try {
            // Ambil data profil user dari Google
            $googleUser = Socialite::driver('google')
                ->redirectUrl(config('services.google.redirect_register'))
                ->user();

            // Cek apakah email Google sudah pernah terdaftar
            $user = User::where('email', $googleUser->email)->first();

            // Jika email sudah ada -> langsung login (tidak perlu buat akun baru)
            if ($user) {
                Auth::login($user, true);
                request()->session()->regenerate();
                return redirect('/');
            }

            // Buat akun baru menggunakan data dari profil Google
            // Password di-set 'google-login' (placeholder, tidak untuk login manual)
            $user = User::create([
                'name'     => $googleUser->name,
                'email'    => $googleUser->email,
                'password' => bcrypt('google-login') // Password placeholder, login hanya via Google
            ]);

            // Login dengan akun yang baru dibuat
            Auth::login($user, true);
            request()->session()->regenerate();

            return redirect('/');

        } catch (\Exception $e) {
            // Jika terjadi error saat komunikasi dengan Google
            return redirect('/register')
                ->with('google_error', 'Register Google gagal.');
        }
    }
}