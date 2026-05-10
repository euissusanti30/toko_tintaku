<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;

class BerandaController extends Controller
{
    public function berandaBackend()
    {
        return view('backend.beranda.index', [
            'judul' => 'Beranda',
            'sub' => 'Halaman Beranda',
        ]);
    }

    public function index()
    {
        $produk = Produk::where('status', 1)->orderBy('updated_at', 'desc')->paginate(6);
        return view('backend.beranda.index', [
            'judul' => 'Halaman Beranda',
            'produk' => $produk,
        ]);
    }
}
