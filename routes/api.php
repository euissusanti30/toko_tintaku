<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| file: routes/api.php
| deskripsi: File ini berisi daftar rute REST API Toko Tintaku.
| digunakan oleh: Aplikasi Flutter untuk autentikasi dan sinkronisasi data database.
|
*/

// Rute pendaftaran pengguna baru (Register)
Route::post('/register', [ApiController::class, 'register']);

// Rute masuk ke aplikasi (Login)
Route::post('/login', [ApiController::class, 'login']);

// Rute pengambilan daftar produk dari tabel produk
Route::get('/produk', [ApiController::class, 'getProducts']);

// Rute pengambilan daftar kategori untuk menu filter
Route::get('/kategori', [ApiController::class, 'getCategories']);

// Rute khusus untuk memuat gambar produk dengan menyertakan CORS header (solusi error gambar Flutter Web)
Route::get('/produk/gambar/{filename}', [ApiController::class, 'getProductImage']);

// Rute otentikasi data user (menggunakan middleware Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
