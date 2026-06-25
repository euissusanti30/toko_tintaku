<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * 
 * Model Eloquent untuk merepresentasikan tabel `users` di MySQL.
 * Menggunakan HasApiTokens (Laravel Sanctum) untuk menghasilkan access token pada API Flutter.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     * 
     * Menambahkan kolom:
     * - `nama`: Nama lengkap kustomer.
     * - `phone`: Nomor telepon/WhatsApp.
     * - `address`: Alamat lengkap pengiriman kustomer.
     * - `role`: Level hak akses (0: Customer, 1: Admin).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nama',
        'email',
        'role',
        'password',
        'phone',
        'address',
    ];

    /**
     * Atribut yang disembunyikan saat data user dikonversi ke JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data kolom.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
