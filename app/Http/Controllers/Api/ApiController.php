<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Produk;
use App\Models\Kategori;

/**
 * Class ApiController
 * 
 * Controller ini menangani seluruh request REST API yang masuk dari aplikasi Flutter
 * untuk proses otentikasi (register, login) dan retrieval data (produk, kategori).
 */
class ApiController extends Controller
{
    /**
     * getProducts
     * 
     * Mengambil daftar produk dari tabel `produk` di database.
     * Mendukung pemfilteran berdasarkan kategori dan pencarian nama produk.
     * 
     * @param Request $request request yang membawa parameter 'kategori_id' dan 'search' (opsional)
     * @return \Illuminate\Http\JsonResponse JSON response status sukses beserta data list produk
     */
    public function getProducts(Request $request)
    {
        try {
            $query = Produk::with('kategori');

            // Filter berdasarkan kategori jika dikirimkan oleh Flutter
            if ($request->has('kategori_id') && $request->kategori_id != '') {
                $query->where('kategori_id', $request->kategori_id);
            }

            // Cari produk berdasarkan nama produk jika dikirimkan oleh Flutter
            if ($request->has('search') && $request->search != '') {
                $query->where('nama_produk', 'like', '%' . $request->search . '%');
            }

            $products = $query->latest()->get();

            // Format URL foto agar menunjuk ke endpoint API khusus CORS (solusi agar gambar tampil di Flutter Web)
            $formattedProducts = $products->map(function ($product) {
                $product->foto_url = url('/api/produk/gambar/' . $product->foto);
                return $product;
            });

            return response()->json([
                'success' => true,
                'data' => $formattedProducts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * getCategories
     * 
     * Mengambil seluruh data kategori dari tabel `kategori` untuk menu filter di Flutter.
     * 
     * @return \Illuminate\Http\JsonResponse JSON response status sukses beserta data list kategori
     */
    public function getCategories()
    {
        try {
            $categories = Kategori::all();
            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * getProductImage
     * 
     * Mengambil file gambar produk dari public storage dan mengembalikannya
     * dengan header CORS (Access-Control-Allow-Origin: *) agar dapat dimuat
     * di Flutter Web / Chrome tanpa kendala kebijakan CORS.
     * 
     * @param string $filename nama file gambar
     * @return \Illuminate\Http\Response response stream file gambar beserta CORS header
     */
    public function getProductImage($filename)
    {
        $path = public_path('produk/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }

        $file = file_get_contents($path);
        $type = mime_content_type($path);

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
    }

    /**
     * register
     * 
     * Mendaftarkan pengguna (Customer) baru ke tabel `users` di MySQL.
     * 
     * @param Request $request data registrasi yang dikirim (name, email, password, phone, address)
     * @return \Illuminate\Http\JsonResponse JSON response status registrasi sukses beserta data user dan access token
     */
    public function register(Request $request)
    {
        try {
            // Validasi input masukan dari request
            $request->validate([
                'name' => 'required|string|max:255',
                'nama' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
            ]);

            // Buat record user baru di database
            $user = User::create([
                'name' => $request->name,
                'nama' => $request->nama ?? $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Password di-hash menggunakan bcrypt/argon
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => 0, // Role 0 diset default untuk level Customer
            ]);

            // Generate Token akses menggunakan Laravel Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * login
     * 
     * Memverifikasi data email dan password user dari tabel `users` untuk proses masuk aplikasi.
     * 
     * @param Request $request kredensial login (email, password)
     * @return \Illuminate\Http\JsonResponse JSON response status sukses login beserta data user dan access token
     */
    public function login(Request $request)
    {
        try {
            // Validasi input email dan password
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Cari user berdasarkan email
            $user = User::where('email', $request->email)->first();

            // Verifikasi kecocokan password menggunakan Hash::check
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah.'
                ], 401);
            }

            // Generate Token akses baru
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login: ' . $e->getMessage()
            ], 500);
        }
    }
}
