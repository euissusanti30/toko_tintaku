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
     * Show the create-admin form.
     */
    public function create(): View
    {
        return view('auth.create-admin');
    }

    /**
     * Store a new admin (protected by ADMIN_SETUP_KEY in .env).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user_admins,email',
            'password' => 'required|string|min:8',
            'setup_key' => 'required|string',
        ]);

        $expected = env('ADMIN_SETUP_KEY');
        if (empty($expected) || $request->input('setup_key') !== $expected) {
            return back()->withErrors(['setup_key' => 'Setup key invalid.'])->withInput();
        }

        UserAdmin::create([
            'nama' => $request->input('nama'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => 1,
        ]);

        return redirect()->route('admin.login')->with('success', 'Akun admin berhasil dibuat.');
    }
}
