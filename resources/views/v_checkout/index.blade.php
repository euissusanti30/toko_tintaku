{{-- 
=================================================================================
FILE: checkout.blade.php
=================================================================================
View ini menampilkan halaman checkout lengkap dengan form input customer,
pilihan alamat pengiriman (provinsi & kota), pilihan kurir, dan perhitungan
ongkir real-time menggunakan JavaScript.

STRUKTUR FILE:
1. Extends layout utama (v_layouts.app)
2. Section content dengan form checkout
3. JavaScript untuk interaksi dinamis (ongkir, filtering kota)
=================================================================================
--}}

{{-- Extend layout utama aplikasi --}}
@extends('v_layouts.app')

{{-- Mulai section content yang akan dimasukkan ke layout --}}
@section('content')

{{-- 
SECTION UTAMA: Form Checkout
- Container Bootstrap dengan padding vertikal (py-5)
- Card dengan shadow dan rounded corners
- Layout 2 kolom: main content (lg-8) dan bisa ditambahkan sidebar
--}}
<section class="py-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-5">

                        {{-- JUDUL HALAMAN --}}
                        <h2 class="fw-bold mb-4">
                            Checkout
                        </h2>

                        {{-- 
                        =================================================================
                        RINGKASAN PESANAN (CART SUMMARY)
                        =================================================================
                        - Alert box dengan background biru (alert-info)
                        - JavaScript akan mengisi #cart-summary dengan item dari session
                        - Menampilkan produk, qty, dan subtotal otomatis
                        --}}
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading">Ringkasan Pesanan</h6>
                            <div id="cart-summary">
                                {{-- JavaScript akan mengisi ini dengan data dari session cart --}}
                            </div>
                        </div>

                        {{-- 
                        =================================================================
                        FORM CHECKOUT UTAMA
                        =================================================================
                        - Action: route frontend.checkout.store (POST ke ProdukController@checkoutStore)
                        - Method: POST
                        - ID: checkout-form (untuk reference JavaScript)
                        --}}
                        <form id="checkout-form" action="{{ route('frontend.checkout.store') }}"
                            method="POST">

                            {{-- CSRF Token: Wajib untuk form POST di Laravel (keamanan) --}}
                            @csrf

                            {{-- 
                            =============================================================
                            BAGIAN 1: INFORMASI CUSTOMER
                            =============================================================
                            Field yang diperlukan:
                            - nama_customer (text, required)
                            - email (email, required)
                            - telepon (text, required)
                            - alamat (textarea, required)
                            --}}
                            <h5 class="mb-3">Informasi Customer</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_customer" class="form-label">Nama Lengkap</label>
                                        <input type="text"
                                            id="nama_customer"
                                            name="nama_customer"
                                            class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email"
                                            id="email"
                                            name="email"
                                            class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telepon" class="form-label">No Telepon</label>
                                        <input type="text"
                                            id="telepon"
                                            name="telepon"
                                            class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea id="alamat"
                                    name="alamat"
                                    class="form-control"
                                    rows="3"
                                    required></textarea>
                            </div>

                            {{-- 
                            =============================================================
                            BAGIAN 2: INFORMASI PENGIRIMAN
                            =============================================================
                            Dropdown dinamis untuk pilih provinsi dan kota
                            Logika:
                            - Provinsi: dropdown penuh dari database/API
                            - Kota: awalnya hidden, muncul setelah provinsi dipilih
                            --}}
                            <h5 class="mb-3 mt-4">Informasi Pengiriman</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="province_id" class="form-label">Provinsi</label>
                                        <select id="province_id" name="province_id" class="form-control" required>
                                            <option value="">Pilih Provinsi</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province['province_id'] }}">
                                                    {{ $province['province'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- 
                                KOLOM KOTA/KABUPATEN
                                - Awalnya disabled (user harus pilih provinsi dulu)
                                - Semua kota di-render tapi hidden dengan CSS
                                - JavaScript akan menampilkan kota yang sesuai provinsi
                                --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="city_id" class="form-label">Kota/Kabupaten</label>
                                        {{-- Disabled: akan di-enable via JavaScript setelah provinsi dipilih --}}
                                        <select id="city_id" name="city_id" class="form-control" required disabled>
                                            <option value="">Pilih Kota</option>
                                            {{-- Loop semua kota, simpan province_id di data-province untuk filtering JS --}}
                                            @foreach($cities as $city)
                                                <option value="{{ $city['city_id'] }}" 
                                                        data-province="{{ $city['province_id'] }}"
                                                        style="display: none;"> {{-- Hidden by default --}}
                                                    {{ $city['city_name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 
                            =============================================================
                            BAGIAN 3: PILIHAN KURIR
                            =============================================================
                            Radio button untuk pilih jasa pengiriman
                            - JNE (checked by default)
                            - TIKI
                            - POS Indonesia
                            JavaScript akan hitung ongkir ulang saat kurir diganti
                            --}}
                            <div class="mb-3">
                                <label class="form-label">Pilih Kurir</label>
                                <div class="row">
                                    {{-- JNE: Default selected (checked) --}}
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="courier" id="courier_jne" value="jne" checked>
                                            <label class="form-check-label" for="courier_jne">JNE</label>
                                        </div>
                                    </div>
                                    {{-- TIKI --}}
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="courier" id="courier_tiki" value="tiki">
                                            <label class="form-check-label" for="courier_tiki">TIKI</label>
                                        </div>
                                    </div>
                                    {{-- POS Indonesia --}}
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="courier" id="courier_pos" value="pos">
                                            <label class="form-check-label" for="courier_pos">POS Indonesia</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 
                            =============================================================
                            BAGIAN 4: LAYANAN PENGIRIMAN (DINAMIS)
                            =============================================================
                            Div ini akan diisi oleh JavaScript dengan pilihan layanan
                            dari API RajaOngkir (OKE, REG, YES untuk JNE, dll)
                            - Diisi setelah user pilih kota dan kurir
                            - Radio button untuk pilih layanan
                            --}}
                            <div class="mb-3">
                                <label class="form-label">Layanan Pengiriman</label>
                                <div id="shipping-services" class="border rounded p-3 bg-light">
                                    <p class="text-muted mb-0">Pilih alamat dan kurir untuk melihat layanan yang tersedia</p>
                                </div>
                            </div>

                            {{-- 
                            =============================================================
                            HIDDEN FIELDS (Data tambahan untuk backend)
                            =============================================================
                            Field-field ini disembunyikan dari user tapi tetap dikirim ke server
                            - shipping_service: nama layanan yang dipilih (OKE/REG/YES)
                            - shipping_cost: biaya ongkir dalam angka (untuk kalkulasi total)
                            - weight: total berat pesanan dalam gram
                            --}}
                            <input type="hidden" name="shipping_service" id="shipping_service">
                            <input type="hidden" name="shipping_cost" id="shipping_cost">
                            <input type="hidden" name="weight" id="total_weight">

                            {{-- 
                            =============================================================
                            BAGIAN 5: RINGKASAN PEMBAYARAN
                            =============================================================
                            Card yang menampilkan perhitungan total:
                            - Subtotal produk (dari cart)
                            - Biaya pengiriman (dari API ongkir)
                            - Total keseluruhan
                            
                            Nilai di-update real-time oleh JavaScript saat:
                            - Halaman load (hitung subtotal)
                            - User pilih layanan pengiriman (update ongkir dan total)
                            --}}
                            <div class="card mt-4">
                                <div class="card-body">
                                    <h5 class="card-title">Ringkasan Pembayaran</h5>
                                    {{-- Subtotal produk (tanpa ongkir) --}}
                                    <div class="row">
                                        <div class="col-8">Subtotal Produk:</div>
                                        <div class="col-4 text-end" id="subtotal">Rp 0</div>
                                    </div>
                                    {{-- Ongkir yang dipilih --}}
                                    <div class="row">
                                        <div class="col-8">Biaya Pengiriman:</div>
                                        <div class="col-4 text-end" id="shipping-display">Rp 0</div>
                                    </div>
                                    <hr>
                                    {{-- Total keseluruhan (subtotal + ongkir) --}}
                                    <div class="row">
                                        <div class="col-8"><strong>Total:</strong></div>
                                        <div class="col-4 text-end" id="total"><strong>Rp 0</strong></div>
                                    </div>
                                </div>
                            </div>

                            {{-- 
                            =============================================================
                            TOMBOL SUBMIT
                            =============================================================
                            - Disabled by default (user harus pilih layanan pengiriman dulu)
                            - JavaScript akan enable tombol setelah user pilih layanan
                            - Full width (w-100) dengan margin top (mt-3)
                            --}}
                            <button type="submit" class="btn btn-tintaku w-100 mt-3" id="submit-btn" disabled>
                                Buat Pesanan
                            </button>

                        {{-- Tutup form checkout --}}
                        </form>

                    {{-- Tutup card-body --}}
                    </div>

                {{-- Tutup card --}}
                </div>

            {{-- Tutup col-lg-8 --}}
            </div>

        {{-- Tutup row --}}
        </div>

    {{-- Tutup container --}}
    </div>

{{-- Tutup section --}}
</section>

{{-- 
=================================================================================
JAVASCRIPT SECTION
=================================================================================
- Event Listeners: province change, city change, courier change
- AJAX Requests: untuk hitung ongkir real-time
- DOM Manipulation: update ringkasan, ongkir, total
=================================================================================
--}}
<script>
    {{-- Debug console log (bisa dihapus di production) --}}
    console.log('asdasd')

{{-- 
EVENT: DOMContentLoaded
Dijalankan setelah DOM selesai dimuat
Inisialisasi variabel dan setup event listeners
--}}
document.addEventListener('DOMContentLoaded', function() {
    
    // -------------------------------------------------------------------------
    // INISIALISASI VARIABEL GLOBAL
    // -------------------------------------------------------------------------
    // Ambil data cart dari session (passing dari PHP ke JS via @json directive)
    const cart = @json(session()->get('cart', []));
    
    // Variabel untuk kalkulasi
    let subtotal = 0;      // Total harga produk tanpa ongkir
    let totalWeight = 0;   // Total berat dalam gram
    
    // -------------------------------------------------------------------------
    // FUNGSI: loadCartSummary()
    // -------------------------------------------------------------------------
    // Tujuan: Menampilkan ringkasan produk di cart dan hitung subtotal
    // Output:
    //   - Isi div #cart-summary dengan list produk
    //   - Update #subtotal dengan total harga
    //   - Set #total_weight dengan berat total
    // -------------------------------------------------------------------------
    function loadCartSummary() {
        const summaryDiv = document.getElementById('cart-summary');
        let html = '';
        
        // Loop setiap item dalam cart
        // Object.entries() mengubah object menjadi array [key, value]
        for (const [id, item] of Object.entries(cart)) {
            // Hitung subtotal per item (harga * quantity)
            const itemSubtotal = item.harga * item.qty;
            
            // Akumulasi ke total subtotal
            subtotal += itemSubtotal;
            
            // Hitung berat (asumsi 1000g = 1kg per item)
            totalWeight += item.qty * 1000; // 1kg per item
            
            // Build HTML untuk item ini
            // toLocaleString('id-ID'): format angka dengan pemisah ribuan (Indonesia)
            html += `
                <div class="d-flex justify-content-between">
                    <span>${item.nama_produk} (${item.qty}x)</span>
                    <span>Rp ${itemSubtotal.toLocaleString('id-ID')}</span>
                </div>
            `;
        }
        
        // Tambahkan baris subtotal di akhir list
        html += `<hr><div class="d-flex justify-content-between"><strong>Subtotal:</strong><strong>Rp ${subtotal.toLocaleString('id-ID')}</strong></div>`;
        
        // Render HTML ke div
        summaryDiv.innerHTML = html;
        
        // Update elemen subtotal di ringkasan pembayaran
        document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        
        // Set value hidden field weight untuk dikirim ke server
        document.getElementById('total_weight').value = totalWeight;
    }
    
    loadCartSummary();
    
    // -------------------------------------------------------------------------
    // EVENT LISTENER: Province Change Handler
    // -------------------------------------------------------------------------
    // Trigger: Saat user memilih provinsi dari dropdown
    // Tujuan: Filter kota yang ditampilkan berdasarkan provinsi yang dipilih
    //
    // Logic:
    // 1. Ambil value provinsi yang dipilih
    // 2. Loop semua option kota
    // 3. Bandingkan data-province dengan provinceId yang dipilih
    // 4. Show/hide option berdasarkan kecocokan
    // 5. Enable/disable dropdown kota
    // 6. Reset shipping calculation
    
    {{-- Debug: log element reference --}}
    console.log('Checkout form loaded');
    console.log('Province select:', document.getElementById('province_id'));
    console.log('City select:', document.getElementById('city_id'));
    console.log('City select initial disabled state:', document.getElementById('city_id').disabled);
    
    {{-- Attach change event listener ke dropdown provinsi --}}
    document.getElementById('province_id').addEventListener('change', function() {
        const provinceId = this.value;  // ID provinsi yang dipilih
        const citySelect = document.getElementById('city_id');  // Reference dropdown kota
        
        {{-- Debug: log perubahan provinsi --}}
        console.log('Province changed to:', provinceId);
        
        // CEK: Jika user sudah memilih provinsi (bukan empty value)
        if (provinceId) {
            // Show cities for selected province
            const cityOptions = citySelect.querySelectorAll('option');
            cityOptions.forEach(option => {
                // Ambil data-province attribute dari option
                const optionProvinceId = option.getAttribute('data-province');
                
                // Bandingkan provinsi option dengan provinsi yang dipilih
                // String() conversion untuk memastikan perbandingan string
                if (optionProvinceId && String(optionProvinceId) === String(provinceId)) {
                    option.style.display = 'block';  // Tampilkan option
                    console.log('Showing city option:', option.textContent);
                } else if (optionProvinceId) {
                    option.style.display = 'none';   // Sembunyikan option
                }
            });
            
            // Enable city dropdown dan reset selection ke empty
            citySelect.disabled = false;  // Hapus attribute disabled
            citySelect.value = '';        // Reset ke "Pilih Kota"
            console.log('City select enabled for province:', provinceId);
        } else {
            // Jika user reset provinsi ke empty
            // Hide all cities and disable dropdown
            const cityOptions = citySelect.querySelectorAll('option[data-province]');
            cityOptions.forEach(option => {
                option.style.display = 'none';  // Sembunyikan semua kota
            });
            
            citySelect.disabled = true;   // Disable dropdown
            citySelect.value = '';      // Reset value
            console.log('City select disabled');
        }
        
        // Reset shipping calculation setiap kali provinsi berubah
        resetShipping();
    });
    
    // -------------------------------------------------------------------------
    // EVENT LISTENER: City Change Handler
    // -------------------------------------------------------------------------
    // Trigger: Saat user memilih kota dari dropdown
    // Tujuan: Hitung ulang ongkir berdasarkan kota tujuan yang baru
    
    document.getElementById('city_id').addEventListener('change', function() {
        resetShipping();  // Reset dulu sebelum hitung baru
        
        // Jika user sudah memilih kota (bukan empty), hitung ongkir
        if (this.value) {
            loadShippingCost();
        }
    });
    
    // -------------------------------------------------------------------------
    // EVENT LISTENER: Courier Change Handler
    // -------------------------------------------------------------------------
    // Trigger: Saat user mengganti pilihan kurir (radio button)
    // Tujuan: Hitung ulang ongkir dengan kurir yang berbeda
    
    document.querySelectorAll('input[name="courier"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Hanya hitung jika kota sudah dipilih
            if (document.getElementById('city_id').value) {
                loadShippingCost();
            }
        });
    });
    
    // -------------------------------------------------------------------------
    // FUNGSI: resetShipping()
    // -------------------------------------------------------------------------
    // Tujuan: Reset semua field terkait pengiriman ke state awal
    // Digunakan: Saat user mengganti provinsi/kota/kurir (sebelum hitung ulang)
    //
    // Reset yang dilakukan:
    // - Kosongkan div layanan pengiriman
    // - Reset hidden fields (service, cost)
    // - Reset display ongkir ke Rp 0
    // - Reset total ke subtotal saja (tanpa ongkir)
    // - Disable tombol submit (user harus pilih layanan lagi)
    
    function resetShipping() {
        // Reset tampilan layanan pengiriman ke pesan default
        document.getElementById('shipping-services').innerHTML = '<p class="text-muted mb-0">Pilih alamat dan kurir untuk melihat layanan yang tersedia</p>';
        
        // Reset hidden fields
        document.getElementById('shipping_service').value = '';
        document.getElementById('shipping_cost').value = '0';
        
        // Reset display ongkir dan total
        document.getElementById('shipping-display').textContent = 'Rp 0';
        document.getElementById('total').innerHTML = '<strong>Rp ' + subtotal.toLocaleString('id-ID') + '</strong>';
        
        // Disable tombol submit (user harus pilih layanan dulu)
        document.getElementById('submit-btn').disabled = true;
    }
    
    // -------------------------------------------------------------------------
    // FUNGSI: loadShippingCost()
    // -------------------------------------------------------------------------
    // Tujuan: Mengambil data ongkir dari API backend via AJAX/fetch
    // Trigger: Dipanggil saat user pilih kota atau ganti kurir
    //
    // Parameter yang dikirim ke API:
    // - origin: 152 (ID Jakarta Pusat - default asal pengiriman)
    // - destination: ID kota tujuan (dari dropdown city_id)
    // - weight: total berat dalam gram (dari variabel totalWeight)
    // - courier: kode kurir (jne/tiki/pos)
    //
    // Response: JSON array data ongkir dengan layanan yang tersedia
    
    function loadShippingCost() {
        // Ambil value dari form
        const cityId = document.getElementById('city_id').value;
        const courier = document.querySelector('input[name="courier"]:checked').value;
        const weight = totalWeight;
        
        // Default origin: Jakarta Pusat (ID: 152)
        // Asumsi toko/pengirim berada di Jakarta
        const originCity = 152;
        
        // Validasi: Pastikan semua parameter ada sebelum request
        if (!cityId || !courier || !weight) return;
        
        // AJAX Request menggunakan Fetch API
        fetch('{{ url('/api/shipping-cost') }}', {
            method: 'POST',  // HTTP method
            headers: {
                'Content-Type': 'application/json',  // Format data JSON
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')  // Token CSRF Laravel
            },
            body: JSON.stringify({  // Data yang dikirim ke server
                origin: originCity,
                destination: cityId,
                weight: weight,
                courier: courier
            })
        })
        .then(response => response.json())  // Parse response sebagai JSON
        .then(data => {
            // Cek jika ada data hasil
            if (data && data.length > 0) {
                // Tampilkan layanan yang tersedia (ambil data pertama)
                displayShippingServices(data[0]);
            }
        })
        .catch(error => {
            // Tangkap error jika terjadi (network error, dll)
            console.error('Error:', error);
        });
    }
    
    // -------------------------------------------------------------------------
    // FUNGSI: displayShippingServices(courierData)
    // -------------------------------------------------------------------------
    // Tujuan: Menampilkan pilihan layanan pengiriman di halaman
    // Parameter: courierData - Object data dari API ongkir
    //
    // Output: Generate radio button untuk setiap layanan (OKE, REG, YES, dll)
    //         dengan informasi harga dan estimasi waktu
    //
    // Event: Tambahkan listener untuk setiap radio button
    //        Saat dipilih: update hidden fields dan total harga
    
    function displayShippingServices(courierData) {
        const servicesDiv = document.getElementById('shipping-services');
        let html = '';
        
        // Loop setiap layanan yang tersedia dari kurir
        // Contoh: OKE, REG, YES untuk JNE
        courierData.costs.forEach((service, index) => {
            // Buat ID unik untuk radio button (kurir-layanan, contoh: jne-REG)
            const serviceId = `${courierData.code}-${service.service}`;
            
            // Build HTML untuk satu pilihan layanan
            html += `
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="shipping_option" 
                           id="${serviceId}" value="${service.cost[0].value}" 
                           data-service="${service.service}">
                    <label class="form-check-label" for="${serviceId}">
                        <strong>${service.service}</strong> - ${service.description} 
                        <span class="float-end">Rp ${service.cost[0].value.toLocaleString('id-ID')}</span>
                        <br><small class="text-muted">Estimasi: ${service.cost[0].etd} hari</small>
                    </label>
                </div>
            `;
        });
        
        // Render HTML ke div
        servicesDiv.innerHTML = html;
        
        // -------------------------------------------------------------------------
        // EVENT LISTENER: Shipping Option Selection
        // -------------------------------------------------------------------------
        // Trigger: Saat user memilih salah satu layanan pengiriman
        // Tujuan: Update form dengan data layanan yang dipilih dan hitung total
        
        servicesDiv.querySelectorAll('input[name="shipping_option"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Update hidden field dengan nama layanan (OKE/REG/YES)
                document.getElementById('shipping_service').value = this.dataset.service;
                
                // Update hidden field dengan biaya ongkir
                document.getElementById('shipping_cost').value = this.value;
                
                // Update tampilan ongkir dengan format Rupiah
                document.getElementById('shipping-display').textContent = 'Rp ' + parseInt(this.value).toLocaleString('id-ID');
                
                // Hitung total keseluruhan (subtotal + ongkir)
                const total = subtotal + parseInt(this.value);
                document.getElementById('total').innerHTML = '<strong>Rp ' + total.toLocaleString('id-ID') + '</strong>';
                
                // Enable tombol submit (semua data sudah lengkap)
                document.getElementById('submit-btn').disabled = false;
            });
        });
    }
    
// Tutup DOMContentLoaded event listener
});
</script>

{{-- Tutup section content --}}
@endsection