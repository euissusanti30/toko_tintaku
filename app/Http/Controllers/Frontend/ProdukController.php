<?php

// =============================================================================
// PRODUK CONTROLLER (FRONTEND)
// =============================================================================
// Controller ini menangani semua logika dan tampilan untuk halaman frontend
// toko online, termasuk: beranda, shop, detail produk, keranjang, checkout,
// dan invoice. Controller ini juga mengintegrasikan API RajaOngkir untuk
// menghitung ongkos kirim secara real-time.
// =============================================================================

namespace App\Http\Controllers\Frontend;

// Import class Controller dasar dari Laravel
use App\Http\Controllers\Controller;

// Import Request untuk menangani input dari form/http
use Illuminate\Http\Request;

// =============================================================================
// IMPORT MODELS
// =============================================================================
// Models digunakan untuk berinteraksi dengan database (CRUD operations)

use App\Models\Produk;           // Model untuk tabel produk (daftar barang)
use App\Models\Kategori;         // Model untuk tabel kategori produk
use App\Models\Transaksi;        // Model untuk tabel transaksi/pesanan
use App\Models\DetailTransaksi; // Model untuk tabel detail item transaksi

// Import Service untuk API RajaOngkir
use App\Services\RajaOngkirService;

// =============================================================================
// CLASS PRODUK CONTROLLER
// =============================================================================
class ProdukController extends Controller
{
    // =========================================================================
    // SECTION: HALAMAN UTAMA (BERANDA)
    // =========================================================================
    // Method-method untuk menampilkan halaman-halaman utama toko
    /*
    |--------------------------------
    | BERANDA
    |--------------------------------
    */

    // -------------------------------------------------------------------------
    // METHOD: frontend() - Halaman Beranda Utama
    // -------------------------------------------------------------------------
    // URL: /
    // Tujuan: Menampilkan halaman beranda toko dengan produk terbaru
    // 
    // Logic:
    // 1. Ambil 8 produk terbaru (latest) dengan relasi kategori
    // 2. Ambil semua kategori untuk menu navigasi
    // 3. Return view v_beranda.index dengan data produk dan kategori
    // 
    // Note: Menggunakan eager loading 'with' untuk optimasi query N+1
    
    public function frontend()
    {
        // Ambil produk dengan relasi kategori, urutkan dari yang terbaru (latest)
        // paginate(8): tampilkan 8 produk per halaman dengan pagination
        $produk = Produk::with('kategori')
                    ->latest()
                    ->paginate(8);

        // Ambil semua data kategori untuk menu dropdown/filter
        $kategori = Kategori::all();

        // Return view dengan mengirim data produk dan kategori
        // compact(): membuat array dari variabel dengan nama yang sama
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
    
    // -------------------------------------------------------------------------
    // METHOD: shop() - Halaman Daftar Semua Produk
    // -------------------------------------------------------------------------
    // URL: /shop
    // Tujuan: Menampilkan semua produk dengan pagination lebih besar
    //
    // Logic:
    // 1. Ambil 12 produk terbaru per halaman (lebih banyak dari beranda)
    // 2. Ambil semua kategori untuk filter/sidebar
    // 3. Return view v_shop.index
    
    public function shop()
    {
        // Paginate 12: menampilkan lebih banyak produk per halaman
        // Cocok untuk halaman katalog/shop
        $produk = Produk::with('kategori')
                    ->latest()
                    ->paginate(12);

        // Ambil semua kategori
        $kategori = Kategori::all();

        // Return view shop dengan data yang sama seperti beranda
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
    
    // -------------------------------------------------------------------------
    // METHOD: detail() - Halaman Detail Produk
    // -------------------------------------------------------------------------
    // URL: /detail/{id}
    // Parameter: $id - ID produk yang akan ditampilkan
    // Tujuan: Menampilkan detail lengkap 1 produk tertentu
    //
    // Logic:
    // 1. Cari produk berdasarkan ID dengan relasi kategori
    // 2. findOrFail(): jika tidak ketemu, otomatis return 404
    // 3. Ambil semua kategori untuk navigasi
    // 4. Return view v_detail.index
    
    public function detail($id)
    {
        // findOrFail($id): cari produk dengan ID tertentu
        // Jika tidak ditemukan, Laravel otomatis throw 404 error
        // with('kategori'): eager load relasi kategori untuk optimasi
        $produk = Produk::with('kategori')
                    ->findOrFail($id);

        // Ambil semua kategori untuk menu navigasi
        $kategori = Kategori::all();

        // Return view detail dengan data produk yang dipilih
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
    
    // -------------------------------------------------------------------------
    // METHOD: kategori() - Filter Produk per Kategori
    // -------------------------------------------------------------------------
    // URL: /kategori/{id}
    // Parameter: $id - ID kategori yang dipilih
    // Tujuan: Menampilkan produk yang termasuk dalam kategori tertentu
    //
    // Logic:
    // 1. Filter produk where kategori_id = id yang dipilih
    // 2. Urutkan dari terbaru, paginate 8
    // 3. Return view yang sama dengan beranda (reuse view)
    
    public function kategori($id)
    {
        // where('kategori_id', $id): filter produk berdasarkan kategori
        // Hanya ambil produk yang punya kategori_id sesuai parameter
        $produk = Produk::where('kategori_id', $id)
                    ->latest()
                    ->paginate(8);

        // Ambil semua kategori untuk navigasi
        $kategori = Kategori::all();

        // Reuse view v_beranda.index dengan data yang sudah difilter
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
    
    // =========================================================================
    // SECTION: KERANJANG BELANJA (CART)
    // =========================================================================
    // Method-method untuk mengelola keranjang belanja menggunakan Session
    
    // -------------------------------------------------------------------------
    // METHOD: cart() - Halaman Keranjang
    // -------------------------------------------------------------------------
    // URL: /cart
    // Tujuan: Menampilkan halaman keranjang belanja
    //
    // Logic:
    // 1. Ambil data kategori untuk navigasi
    // 2. Data keranjang diambil otomatis oleh view dari session
    // 3. Return view v_cart.index
    //
    // Note: Data cart disimpan di session (session()->get('cart')), 
    // bukan dikirim dari controller ke view secara eksplisit
    
    public function cart()
    {
        // Ambil kategori untuk navigasi
        $kategori = Kategori::all();

        // Return view cart
        // Data cart diakses langsung dari session di dalam view
        return view('v_cart.index', compact(
            'kategori'
        ));
    }

    // -------------------------------------------------------------------------
    // METHOD: addCart() - Tambah Produk ke Keranjang
    // -------------------------------------------------------------------------
    // URL: /add-cart/{id}
    // Parameter: $id - ID produk yang akan ditambahkan
    // Tujuan: Menambahkan produk ke session keranjang
    //
    // Logic:
    // 1. Cari produk berdasarkan ID
    // 2. Ambil data cart dari session (default: array kosong)
    // 3. CEK: Jika produk sudah ada di cart, tambah quantity (qty++)
    // 4. Jika belum ada, tambahkan ke array cart dengan qty = 1
    // 5. Simpan kembali cart ke session
    // 6. Redirect ke halaman cart
    
    public function addCart($id)
    {
        // Cari produk yang akan ditambahkan
        $produk = Produk::findOrFail($id);

        // Ambil data cart dari session
        // Default: array kosong [] jika belum ada
        $cart = session()->get('cart', []);

        // CEK: Apakah produk sudah ada di keranjang?
        if(isset($cart[$id]))
        {
            // Jika sudah ada, tambah quantity +1
            $cart[$id]['qty']++;
        }
        else
        {
            // Jika belum ada, tambahkan produk baru ke cart
            // Simpan data penting: nama, foto, harga, qty (awal = 1)
            $cart[$id] = [
                "nama_produk" => $produk->nama_produk,  // Nama produk
                "foto" => $produk->foto,                // URL/path foto
                "harga" => $produk->harga,              // Harga satuan
                "qty" => 1                               // Quantity awal = 1
            ];
        }

        // Simpan cart yang sudah diupdate ke session
        // Session akan bertahan sampai browser ditutup
        session()->put('cart', $cart);

        // Redirect ke halaman cart untuk melihat hasil
        return redirect('/cart');
    }

    // -------------------------------------------------------------------------
    // METHOD: updateCart() - Update Quantity Produk di Keranjang
    // -------------------------------------------------------------------------
    // URL: /update-cart (POST)
    // Parameter dari Request: id (produk), qty (jumlah baru)
    // Tujuan: Mengubah jumlah (quantity) produk di keranjang
    //
    // Logic:
    // 1. Ambil cart dari session
    // 2. Update qty produk dengan id tertentu
    // 3. Simpan kembali ke session
    // 4. Redirect ke cart
    
    public function updateCart(Request $request)
    {
        // Ambil cart dari session
        $cart = session()->get('cart');

        // Update quantity produk tertentu
        // $request->id = ID produk, $request->qty = quantity baru
        $cart[$request->id]["qty"] = $request->qty;

        // Simpan perubahan ke session
        session()->put('cart', $cart);

        // Redirect ke halaman cart
        return redirect('/cart');
    }

    // -------------------------------------------------------------------------
    // METHOD: deleteCart() - Hapus Produk dari Keranjang
    // -------------------------------------------------------------------------
    // URL: /delete-cart/{id}
    // Parameter: $id - ID produk yang akan dihapus
    // Tujuan: Menghapus produk tertentu dari session keranjang
    //
    // Logic:
    // 1. Ambil cart dari session
    // 2. Hapus item dengan id tertentu menggunakan unset()
    // 3. Simpan kembali cart yang sudah dikurangi ke session
    // 4. Redirect ke cart
    
    public function deleteCart($id)
    {
        // Ambil cart dari session
        $cart = session()->get('cart');

        // Hapus item dengan ID tertentu dari array cart
        // unset(): menghapus elemen array berdasarkan key
        unset($cart[$id]);

        // Simpan cart yang sudah dikurangi ke session
        session()->put('cart', $cart);

        // Redirect ke halaman cart
        return redirect('/cart');
    }

    /*
    |--------------------------------
    | CHECKOUT
    |--------------------------------
    */
    
    // =========================================================================
    // SECTION: CHECKOUT (Proses Pembayaran)
    // =========================================================================
    // Method-method untuk menangani proses checkout dan pembuatan pesanan
    
    // -------------------------------------------------------------------------
    // METHOD: checkout() - Halaman Form Checkout
    // -------------------------------------------------------------------------
    // URL: /checkout
    // Tujuan: Menampilkan form checkout dengan data provinsi dan kota
    //
    // Logic:
    // 1. Cek apakah cart kosong, jika ya redirect dengan error
    // 2. Ambil semua kategori untuk navigasi
    // 3. Inisialisasi RajaOngkirService untuk data lokasi
    // 4. Ambil semua provinsi dari API/service
    // 5. Ambil semua kota dari API/service
    // 6. Return view checkout dengan data lokasi
    //
    // Error Handling: Try-catch untuk menangani error API/service
    
    public function checkout()
    {
        // Ambil data cart dari session
        // Default: array kosong jika belum ada
        $cart = session()->get('cart', []);
        
        // Debug: Log isi cart ke file log untuk troubleshooting
        \Log::info('Checkout accessed. Cart contents: ' . json_encode($cart));
        
        // Validasi: Cek jika cart kosong, redirect ke cart dengan pesan error
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Keranjang belanja Anda kosong');
        }

        // Try-catch untuk menangani error dari service/API
        try {
            // Ambil kategori untuk navigasi
            $kategori = Kategori::all();
            
            // Buat instance RajaOngkirService
            // Service ini akan menangani data provinsi, kota, dan ongkir
            $rajaOngkir = new RajaOngkirService();
            
            // Ambil data provinsi dari service
            // Bisa dari API asli atau mock data (demo mode)
            $provinces = $rajaOngkir->getProvinces();
            
            // Ambil data semua kota dari service
           $cities = $rajaOngkir->getCities();

return view('v_checkout.index', compact(
    'kategori',
    'provinces',
    'cities'
));
        } catch (\Exception $e) {
            // Tangkap error, log ke file log, dan redirect dengan pesan error
            \Log::error('Checkout error: ' . $e->getMessage());
            return redirect('/cart')->with('error', 'Terjadi kesalahan saat memuat halaman checkout: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // METHOD: checkoutStore() - Proses Simpan Pesanan ke Database
    // -------------------------------------------------------------------------
    // URL: /checkout (POST)
    // Parameter dari Request: data form checkout lengkap
    // Tujuan: Validasi data, hitung total, simpan transaksi ke database
    //
    // Logic:
    // 1. Validasi semua input form dengan rules
    // 2. Cek cart tidak kosong
    // 3. Hitung subtotal dan total berat dari cart
    // 4. Hitung total harga = subtotal + ongkir
    // 5. Create record transaksi (header)
    // 6. Create records detail transaksi (items)
    // 7. Kosongkan session cart
    // 8. Redirect ke halaman invoice dengan pesan sukses
    //
    // Error Handling: Try-catch, redirect back dengan error dan input lama
    
    public function checkoutStore(Request $request)
    {
        // =========================================================================
        // STEP 1: VALIDASI INPUT FORM
        // =========================================================================
        // Validasi semua field yang required
        // required = wajib diisi, string = harus teks, email = format email valid
        // max:255 = maksimal 255 karakter, integer = harus angka bulat
        
        $request->validate([
            'nama_customer' => 'required|string|max:255',    // Nama lengkap customer
            'email' => 'required|email|max:255',               // Email valid
            'telepon' => 'required|string|max:15',            // No telepon
            'alamat' => 'required|string',                    // Alamat pengiriman
            'origin_id' => 'required|integer',                 // ID kota asal (hidden field)
            'city_id' => 'required|integer',                  // ID kota tujuan
            'courier' => 'required|string',                   // Kode kurir (jne/tiki/pos)
            'shipping_service' => 'required|string',          // Layanan kurir (OKE/REG/YES)
            'shipping_cost' => 'required|integer|min:0',       // Biaya ongkir (angka, min 0)
            'weight' => 'required|integer|min:1'              // Total berat dalam gram
        ]);

        // =========================================================================
        // STEP 2: AMBIL DAN CEK DATA CART
        // =========================================================================
        
        $cart = session()->get('cart', []);
        
        // Cek lagi jika cart kosong (antisipasi)
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Keranjang belanja Anda kosong');
        }

        // =========================================================================
        // STEP 3: PROSES HITUNG TOTAL
        // =========================================================================
        
        try {
            // Inisialisasi variabel untuk perhitungan
            $subtotal = 0;      // Total harga produk (tanpa ongkir)
            $totalWeight = 0;   // Total berat semua produk dalam gram
            
            // Loop semua item di cart untuk hitung subtotal dan berat
            foreach ($cart as $item) {
                // Subtotal item = harga satuan * quantity
                $subtotal += $item['harga'] * $item['qty'];
                
                // Asumsi: setiap item 1000 gram (1 kg)
                // Bisa dimodifikasi sesuai berat actual produk
                $totalWeight += $item['qty'] * 1000; // Assume 1kg per item, you can modify this
            }

            // Total harga = subtotal + ongkir
            $totalHarga = $subtotal + $request->shipping_cost;

            // =========================================================================
            // STEP 4: SIMPAN TRANSAKSI KE DATABASE (HEADER)
            // =========================================================================
            
            // Create record baru di tabel transaksi
            // Menggunakan Eloquent Model Transaksi dengan method create()
            $transaksi = Transaksi::create([
                'nama_customer' => $request->nama_customer,  // Nama pembeli
                'email' => $request->email,                  // Email pembeli
                'telepon' => $request->telepon,              // No telepon
                'alamat' => $request->alamat,                // Alamat lengkap
                'total_harga' => $totalHarga,                // Total bayar (produk + ongkir)
                'status' => 'belum bayar'                    // Status awal: belum bayar
            ]);

            // =========================================================================
            // STEP 5: SIMPAN DETAIL TRANSAKSI (ITEMS)
            // =========================================================================
            
            // Loop semua item di cart untuk disimpan ke tabel detail_transaksi
            foreach ($cart as $id => $item) {
                // Create record detail untuk setiap produk
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,          // ID transaksi header (foreign key)
                    'produk_id' => $id,                         // ID produk (foreign key)
                    'qty' => $item['qty'],                      // Jumlah dibeli
                    'harga' => $item['harga'],                  // Harga satuan saat itu
                    'subtotal' => $item['harga'] * $item['qty'] // Total harga item
                ]);
            }

            // =========================================================================
            // STEP 6: KOSONGKAN CART DAN REDIRECT
            // =========================================================================
            
            // Hapus session cart (sudah tidak diperlukan lagi)
            session()->forget('cart');

            // Redirect ke halaman rincian pembayaran (payment detail)
            return redirect()->route('frontend.payment.detail', $transaksi->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            // Jika terjadi error (database error, dll)
            // Redirect kembali ke form dengan pesan error
            // withInput(): agar data form yang sudah diisi tidak hilang
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // =========================================================================
    // SECTION: API ENDPOINTS (AJAX)
    // =========================================================================
    // Method-method ini digunakan untuk AJAX request dari JavaScript frontend
    // Return JSON untuk interaksi dinamis tanpa reload halaman
    
    /*
    |--------------------------------
    | API FOR SHIPPING COST
    |--------------------------------
    */
    
    // -------------------------------------------------------------------------
    // API: getCities() - Ambil Kota per Provinsi (JSON)
    // -------------------------------------------------------------------------
    // URL: /api/cities/{provinceId}
    // Parameter: $provinceId - ID provinsi
    // Return: JSON array data kota
    //
    // Digunakan oleh: JavaScript frontend saat user pilih provinsi
    // Hasil: Dropdown kota diupdate secara dinamis
    
    public function getCities($provinceId)
    {
        // Buat instance service RajaOngkir
        $rajaOngkir = new RajaOngkirService();

        // Ambil data kota berdasarkan provinsi dari service
        $cities = $rajaOngkir->getCities($provinceId);

        // Return response dalam format JSON
        // Format JSON cocok untuk AJAX/JavaScript consumption
        return response()->json($cities);
    }

    // -------------------------------------------------------------------------
    // API: searchCities() - Cari Kota (JSON)
    // -------------------------------------------------------------------------
    // URL: /api/cities-search?search={query}&limit={limit}
    // Parameter dari Request:
    //   search - Query pencarian nama kota/kecamatan (wajib)
    //   limit  - Jumlah hasil maksimal (opsional, default: 20)
    // Return: JSON array data kota/kecamatan dari API RajaOngkir
    //
    // Digunakan oleh: JavaScript frontend untuk autocomplete saat user ketik alamat
    // Hasil: Dropdown autocomplete muncul dengan data real dari API

    public function searchCities(Request $request)
    {
        // Validasi input
        $request->validate([
            'search' => 'required|string|min:2',  // Minimal 2 karakter untuk pencarian
            'limit' => 'integer|min:1|max:100'     // Limit antara 1-100 hasil
        ]);

        // Buat instance service RajaOngkir
        $rajaOngkir = new RajaOngkirService();

        // Ambil parameter dari request
        $search = $request->get('search');
        $limit = $request->get('limit', 20);

        // Log untuk debugging
        \Log::info('Search cities request', ['search' => $search, 'limit' => $limit]);

        // Panggil method searchCities dari service
        $results = $rajaOngkir->searchCities($search, $limit);

        // Log hasil
        \Log::info('Search cities response', ['count' => count($results)]);

        // Return dalam format JSON
        return response()->json($results);
    }

    // -------------------------------------------------------------------------
    // API: getShippingCost() - Hitung Ongkir (JSON)
    // -------------------------------------------------------------------------
    // URL: /api/shipping-cost (POST)
    // Parameter dari Request:
    //   origin       - ID kota asal
    //   destination  - ID kota tujuan
    //   weight       - Berat barang (gram)
    //   courier      - Kode kurir (jne/tiki/pos)
    // Return: JSON data ongkir dengan layanan dan harga
    //
    // Digunakan oleh: JavaScript frontend saat user pilih kurir/lokasi
    // Hasil: Ongkir dihitung dan ditampilkan real-time tanpa reload
    
    public function getShippingCost(Request $request)
    {
        // Validasi input yang diperlukan untuk hitung ongkir
        $request->validate([
            'origin' => 'required|integer',       // ID kota asal (wajib, angka)
            'destination' => 'required|integer',  // ID kota tujuan (wajib, angka)
            'weight' => 'required|integer|min:1', // Berat minimal 1 gram
            'courier' => 'required|string'        // Kode kurir (wajib, teks)
        ]);

        // Buat instance service RajaOngkir
        $rajaOngkir = new RajaOngkirService();

        // Log request parameters untuk debugging
        \Log::info('Shipping cost request', [
            'origin' => $request->origin,
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier
        ]);

        // Panggil method getShippingCost dari service
        // Kirim parameter dari request untuk hitung ongkir
        $results = $rajaOngkir->getShippingCost(
            $request->origin,      // ID kota asal
            $request->destination, // ID kota tujuan
            $request->weight,      // Berat dalam gram
            $request->courier      // Kode kurir
        );

        // Log response untuk debugging
        \Log::info('Shipping cost response', ['results' => $results]);

        // Return hasil perhitungan dalam format JSON
        return response()->json($results);
    }

    // =========================================================================
    // SECTION: INVOICE (Struk Pembelian)
    // =========================================================================
    
    /*
    |--------------------------------
    | INVOICE
    |--------------------------------
    */
    
    // -------------------------------------------------------------------------
    // METHOD: invoice() - Halaman Invoice/Struk Pesanan
    // -------------------------------------------------------------------------
    // URL: /invoice/{id}
    // Parameter: $id - ID transaksi yang akan ditampilkan
    // Tujuan: Menampilkan struk/invoice lengkap setelah checkout sukses
    //
    // Logic:
    // 1. Ambil data transaksi dengan relasi detail dan produk
    // 2. findOrFail(): jika tidak ketemu, return 404
    // 3. Ambil kategori untuk navigasi
    // 4. Return view invoice dengan data transaksi
    //
    // Note: Menggunakan eager loading nested: detailTransaksi.produk
    
    public function invoice($id)
    {
        // Ambil data transaksi dengan eager loading relasi
        // with('detailTransaksi.produk'): load detail_transaksi dan relasi produk
        // Ini menghindari N+1 query problem
        $transaksi = Transaksi::with('detailTransaksi.produk')
            ->findOrFail($id);

        // Ambil kategori untuk navigasi
        $kategori = Kategori::all();

        // Return view invoice dengan data transaksi lengkap
        // Data yang dikirim: transaksi (dengan detail dan produk), kategori
        return view('v_checkout.invoice', compact('transaksi', 'kategori'));
    }

    /**
     * TAMPILKAN RINCIAN PEMBAYARAN & INISIALISASI MIDTRANS SNAP TOKEN
     * 
     * Method ini menampilkan detail pesanan yang baru dibuat atau yang belum dibayar,
     * serta menginisialisasi parameter transaksi untuk membuat token pembayaran Midtrans Snap.
     * 
     * @param int $id ID Transaksi
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function paymentDetail($id)
    {
        // Mengambil data transaksi beserta relasi detail transaksi dan produk menggunakan Eager Loading
        $transaksi = Transaksi::with('detailTransaksi.produk')
            ->findOrFail($id);

        // Mengambil kategori produk untuk kebutuhan navigasi/sidebar frontend
        $kategori = Kategori::all();

        // 1. Konfigurasi kredensial Midtrans dari file config/services.php
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$clientKey = config('services.midtrans.clientKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // 2. Siapkan parameter yang dibutuhkan oleh Midtrans API
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $transaksi->id . '-' . time(), // Format order_id unik
                'gross_amount' => $transaksi->total_harga,             // Nominal total belanja (termasuk ongkir)
            ],
            'customer_details' => [
                'first_name' => $transaksi->nama_customer, // Nama customer
                'email' => $transaksi->email,               // Email customer
                'phone' => $transaksi->telepon,             // Telepon customer
            ]
        ];

        try {
            // 3. Minta token transaksi Snap dari Midtrans API
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            // Catat log error jika koneksi/integrasi ke Midtrans gagal
            \Log::error('Midtrans getSnapToken error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Gagal memuat rincian pembayaran Midtrans: ' . $e->getMessage());
        }

        // Tampilkan halaman rincian pembayaran dengan data transaksi dan token Snap
        return view('v_checkout.payment_detail', compact('transaksi', 'kategori', 'snapToken'));
    }

    /**
     * HANDLE PEMBAYARAN BERHASIL (REDIRECT CLIENT-SIDE)
     * 
     * Method ini dipanggil ketika pelanggan berhasil membayar di popup Midtrans Snap.
     * Status transaksi akan diperbarui di database menjadi 'sudah bayar'.
     * 
     * @param int $id ID Transaksi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentSuccess($id)
    {
        // Cari data transaksi berdasarkan ID
        $transaksi = Transaksi::findOrFail($id);
        
        // Ubah status pembayaran di database menjadi 'sudah bayar'
        $transaksi->status = 'sudah bayar';
        $transaksi->save();

        // Redirect pengguna ke halaman Invoice dengan pesan sukses
        return redirect('/invoice/' . $transaksi->id)
            ->with('success', 'Pembayaran berhasil dilakukan!');
    }

    /**
     * DAFTAR PESANAN CUSTOMER (ONGOING ORDERS)
     * 
     * Menampilkan riwayat transaksi yang pernah dilakukan oleh customer yang sedang login.
     * Diidentifikasi berdasarkan kesamaan email akun customer dengan email pada transaksi.
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function pesanan()
    {
        // 1. Validasi otentikasi: pastikan pengguna sudah melakukan login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melihat daftar pesanan Anda.');
        }

        // 2. Ambil email dari user yang saat ini sedang login
        $userEmail = auth()->user()->email;

        // 3. Query semua transaksi milik user berdasarkan email tersebut
        // Memakai with() untuk eager loading detail transaksi dan produk agar efisien (menghindari N+1 query)
        $transaksi = Transaksi::with('detailTransaksi.produk')
            ->where('email', $userEmail)
            ->latest() // Diurutkan dari yang paling baru
            ->get();

        // 4. Mengambil data kategori untuk menu navigasi frontend
        $kategori = Kategori::all();

        // Tampilkan view pesanan dengan menyertakan data transaksi dan kategori
        return view('v_checkout.pesanan', compact('transaksi', 'kategori'));
    }

    /**
     * BATALKAN & HAPUS PESANAN OLEH CUSTOMER
     * 
     * Mengizinkan pelanggan membatalkan pesanan yang belum dibayar.
     * Pesanan beserta detail produknya akan dihapus permanen dari database.
     * 
     * @param int $id ID Transaksi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batalkanPesanan($id)
    {
        // 1. Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Cari transaksi berdasarkan ID
        $transaksi = Transaksi::findOrFail($id);

        // 3. Validasi Keamanan: Pastikan transaksi ini milik akun yang sedang login
        if ($transaksi->email !== auth()->user()->email) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk membatalkan pesanan ini.');
        }

        // 4. Validasi Status: Hanya pesanan dengan status 'belum bayar' yang boleh dibatalkan
        if ($transaksi->status !== 'belum bayar') {
            return redirect()->back()->with('error', 'Pesanan yang sudah dibayar atau diproses tidak dapat dibatalkan.');
        }

        // 5. Hapus detail transaksi terlebih dahulu untuk menjaga integritas relasi foreign key database
        $transaksi->detailTransaksi()->delete();
        
        // 6. Hapus header transaksi utama
        $transaksi->delete();

        // Kembali ke halaman sebelumnya dengan pemberitahuan sukses
        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan dan dihapus.');
    }

    public function search(Request $request)
{
    $search = $request->search;

    $produk = Produk::where('nama_produk', 'LIKE', "%$search%")
        ->latest()
        ->paginate(12);

    $kategori = Kategori::all();

    return view('v_shop.index', compact(
        'produk',
        'kategori'
    ));
}
    
    // =========================================================================
    // END OF CLASS PRODUK CONTROLLER
    // =========================================================================
}