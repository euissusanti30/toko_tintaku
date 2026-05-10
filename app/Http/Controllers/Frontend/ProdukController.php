<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Produk;
use App\Models\Kategori;

class ProdukController extends Controller
{
    /*
    |--------------------------------
    | BERANDA
    |--------------------------------
    */

    public function frontend()
    {
        $produk = Produk::with('kategori')
                    ->latest()
                    ->paginate(8);

        $kategori = Kategori::all();

        return view('v_beranda.index', compact(
            'produk',
            'kategori'
        ));
    }

    /*
    |--------------------------------
    | SHOP
    |--------------------------------
    */

    public function shop()
    {
        $produk = Produk::with('kategori')
                    ->latest()
                    ->paginate(12);

        $kategori = Kategori::all();

        return view('v_shop.index', compact(
            'produk',
            'kategori'
        ));
    }

    /*
    |--------------------------------
    | DETAIL
    |--------------------------------
    */

    public function detail($id)
    {
        $produk = Produk::with('kategori')
                    ->findOrFail($id);

        $kategori = Kategori::all();

        return view('v_detail.index', compact(
            'produk',
            'kategori'
        ));
    }

    /*
    |--------------------------------
    | FILTER KATEGORI
    |--------------------------------
    */

    public function kategori($id)
    {
        $produk = Produk::where('kategori_id', $id)
                    ->latest()
                    ->paginate(8);

        $kategori = Kategori::all();

        return view('v_beranda.index', compact(
            'produk',
            'kategori'
        ));
    }

    /*
    |--------------------------------
    | CART
    |--------------------------------
    */

    public function cart()
    {
        $kategori = Kategori::all();

        return view('v_cart.index', compact(
            'kategori'
        ));
    }

    public function addCart($id)
    {
        $produk = Produk::findOrFail($id);

        $cart = session()->get('cart', []);

        if(isset($cart[$id]))
        {
            $cart[$id]['qty']++;
        }
        else
        {
            $cart[$id] = [
                "nama_produk" => $produk->nama_produk,
                "foto" => $produk->foto,
                "harga" => $produk->harga,
                "qty" => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect('/cart');
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart');

        $cart[$request->id]["qty"] = $request->qty;

        session()->put('cart', $cart);

        return redirect('/cart');
    }

    public function deleteCart($id)
    {
        $cart = session()->get('cart');

        unset($cart[$id]);

        session()->put('cart', $cart);

        return redirect('/cart');
    }
}