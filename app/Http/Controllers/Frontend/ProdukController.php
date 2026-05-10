<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Services\RajaOngkirService;

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

    /*
    |--------------------------------
    | CHECKOUT
    |--------------------------------
    */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        // Debug: Log cart contents
        \Log::info('Checkout accessed. Cart contents: ' . json_encode($cart));
        
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Keranjang belanja Anda kosong');
        }

        try {
            $kategori = Kategori::all();
            $rajaOngkir = new RajaOngkirService();
            $provinces = $rajaOngkir->getProvinces();
            $cities = $rajaOngkir->getCities(); // Get all cities
            
            return view('v_checkout.index', compact(
                'kategori',
                'provinces',
                'cities'
            ));
        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return redirect('/cart')->with('error', 'Terjadi kesalahan saat memuat halaman checkout: ' . $e->getMessage());
        }
    }

    public function checkoutStore(Request $request)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'province_id' => 'required|integer',
            'city_id' => 'required|integer',
            'courier' => 'required|string',
            'shipping_service' => 'required|string',
            'shipping_cost' => 'required|integer|min:0'
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Keranjang belanja Anda kosong');
        }

        try {
            // Calculate total
            $subtotal = 0;
            $totalWeight = 0;
            
            foreach ($cart as $item) {
                $subtotal += $item['harga'] * $item['qty'];
                $totalWeight += $item['qty'] * 1000; // Assume 1kg per item, you can modify this
            }

            $totalHarga = $subtotal + $request->shipping_cost;

            // Create transaction
            $transaksi = Transaksi::create([
                'nama_customer' => $request->nama_customer,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'total_harga' => $totalHarga,
                'status' => 'pending'
            ]);

            // Create transaction details
            foreach ($cart as $id => $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $id,
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['harga'] * $item['qty']
                ]);
            }

            // Clear cart
            session()->forget('cart');

            return redirect('/invoice/' . $transaksi->id)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /*
    |--------------------------------
    | API FOR SHIPPING COST
    |--------------------------------
    */
    public function getCities($provinceId)
    {
        $rajaOngkir = new RajaOngkirService();
        $cities = $rajaOngkir->getCities($provinceId);
        
        return response()->json($cities);
    }

    public function getShippingCost(Request $request)
    {
        $request->validate([
            'origin' => 'required|integer',
            'destination' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string'
        ]);

        $rajaOngkir = new RajaOngkirService();
        $results = $rajaOngkir->getShippingCost(
            $request->origin,
            $request->destination,
            $request->weight,
            $request->courier
        );

        return response()->json($results);
    }

    /*
    |--------------------------------
    | INVOICE
    |--------------------------------
    */
    public function invoice($id)
    {
        $transaksi = Transaksi::with('detailTransaksi.produk')
            ->findOrFail($id);

        $kategori = Kategori::all();

        return view('v_checkout.invoice', compact('transaksi', 'kategori'));
    }
}