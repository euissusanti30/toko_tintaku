<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori; 

class BerandaController extends Controller
{
    public function index()
    {
        $produk = Produk::where('status', 1)
            ->latest()
            ->take(6)
            ->get();

        // TAMBAHKAN INI
        $kategori = Kategori::all();

        return view('v_beranda.index', [
            'produk' => $produk,
            'kategori' => $kategori
        ]);
    }
}