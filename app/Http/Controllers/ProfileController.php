<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * CONTROLLER: PROFIL PENGGUNA
 *
 * Mengelola penampilan, pembaruan, dan penghapusan akun profil customer.
 * Jika email diubah, status verifikasi email akan direset.
 */
class ProfileController extends Controller
{
    /**
     * TAMPILKAN FORM EDIT PROFIL
     *
     * Menampilkan halaman form edit profil user yang sedang login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        // Kirim data user yang sedang login ke view untuk mengisi form
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * PERBARUI DATA PROFIL
     *
     * Memvalidasi dan menyimpan perubahan data profil (nama, email).
     * Jika email diubah, kolom email_verified_at direset ke null
     * agar user harus verifikasi email baru.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // fill(): isi model dengan data yang sudah divalidasi oleh ProfileUpdateRequest
        $request->user()->fill($request->validated());

        // Jika email berubah, reset status verifikasi email
        // isDirty('email'): cek apakah nilai email berbeda dari yang tersimpan di database
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null; // Paksa verifikasi ulang
        }

        // Simpan perubahan ke database
        $request->user()->save();

        // Redirect kembali ke halaman edit profil dengan status berhasil
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * HAPUS AKUN PENGGUNA
     *
     * Memvalidasi password lalu menghapus akun user secara permanen.
     * Proses: logout -> hapus akun -> invalidate session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi password saat ini sebelum mengizinkan penghapusan akun
        // 'userDeletion': nama error bag khusus untuk form hapus akun
        // 'current-password': rule Laravel untuk memvalidasi password saat ini
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        // Logout user sebelum akun dihapus
        Auth::logout();

        // Hapus akun user dari database secara permanen
        $user->delete();

        // Hancurkan session agar tidak ada data lama yang tersisa
        $request->session()->invalidate();

        // Reset CSRF token untuk keamanan
        $request->session()->regenerateToken();

        // Redirect ke halaman utama setelah akun terhapus
        return Redirect::to('/');
    }
}
