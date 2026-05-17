<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LOGIN GOOGLE
    |--------------------------------------------------------------------------
    */

    public function loginGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl('http://127.0.0.1:8000/auth/google/callback/login')
            ->redirect();
    }

    public function handleLogin()
    {

        try {

            $googleUser = Socialite::driver('google')
                ->redirectUrl('http://127.0.0.1:8000/auth/google/callback/login')
                ->user();

            $user = User::where('email', $googleUser->email)->first();

            // KALAU AKUN TIDAK ADA
            if (!$user) {

                return redirect('/login')
                    ->with('google_error', 'Akun tidak terdaftar.');

            }

            // LOGIN
            Auth::login($user, true);

            request()->session()->regenerate();

            return redirect('/');

        } catch (\Exception $e) {

            return redirect('/login')
                ->with('google_error', 'Login Google gagal.');

        }

    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER GOOGLE
    |--------------------------------------------------------------------------
    */

    public function registerGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl('http://127.0.0.1:8000/auth/google/callback/register')
            ->redirect();
    }

    public function handleRegister()
    {

        try {

            $googleUser = Socialite::driver('google')
                ->redirectUrl('http://127.0.0.1:8000/auth/google/callback/register')
                ->user();

            // CEK APAKAH EMAIL SUDAH ADA
            $user = User::where('email', $googleUser->email)->first();

            // KALAU SUDAH ADA
            if ($user) {

                Auth::login($user, true);

                request()->session()->regenerate();

                return redirect('/');

            }

            // BUAT AKUN BARU
            $user = User::create([

                'name' => $googleUser->name,

                'email' => $googleUser->email,

                'password' => bcrypt('google-login')

            ]);

            // LOGIN
            Auth::login($user, true);

            request()->session()->regenerate();

            return redirect('/');

        } catch (\Exception $e) {

            return redirect('/register')
                ->with('google_error', 'Register Google gagal.');

        }

    }

}