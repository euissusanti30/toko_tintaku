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
<style>
    /* Autocomplete dropdown styles */
    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        float: left;
        min-width: 100%;
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        font-size: 0.875rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
    }
    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.5rem 1rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        cursor: pointer;
    }
    .dropdown-item:hover,
    .dropdown-item:focus {
        color: #1e2125;
        background-color: #e9ecef;
    }
    .dropdown-item:active {
        color: #fff;
        background-color: #0d6efd;
    }
    .position-relative {
        position: relative;
    }
</style>

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

                            {{-- CARI KOTA/KECAMATAN ASAL (TOKO) --}}
                            <div class="mb-3 position-relative">
                                <label for="origin_search" class="form-label">Kota/Kecamatan Asal (Toko)</label>
                                <input type="text"
                                       id="origin_search"
                                       name="origin_search"
                                       class="form-control"
                                       placeholder="Ketik 'matraman' atau 'jakarta pusat'..."
                                       autocomplete="off"
                                       required>
                                <input type="hidden" id="origin_id" name="origin_id">

                                {{-- Autocomplete dropdown for origin --}}
                                <div id="origin_autocomplete"
                                     class="dropdown-menu w-100"
                                     style="display: none; max-height: 300px; overflow-y: auto;">
                                </div>
                                <small class="text-muted">Ketik minimal 3 huruf untuk mencari lokasi toko</small>
                            </div>

                            {{-- CARI KOTA/KECAMATAN TUJUAN --}}
                            <div class="mb-3 position-relative">
                                <label for="destination_search" class="form-label">Kota/Kecamatan Tujuan</label>
                                <input type="text"
                                       id="destination_search"
                                       name="destination_search"
                                       class="form-control"
                                       placeholder="Ketik nama kota tujuan..."
                                       autocomplete="off"
                                       required>
                                <input type="hidden" id="city_id" name="city_id">

                                {{-- Autocomplete dropdown for destination --}}
                                <div id="destination_autocomplete"
                                     class="dropdown-menu w-100"
                                     style="display: none; max-height: 300px; overflow-y: auto;">
                                </div>
                                <small class="text-muted">Ketik minimal 3 huruf untuk mencari lokasi tujuan</small>
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
                            <button
    type="submit"
    id="pay-button"
    class="btn btn-info w-100"
    disabled>
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

    // -------------------------------------------------------------------------
    // AUTOCOMPLETE SEARCH FOR ORIGIN AND DESTINATION
    // -------------------------------------------------------------------------
    const originSearch = document.getElementById('origin_search');
    const originAutocomplete = document.getElementById('origin_autocomplete');
    const originIdInput = document.getElementById('origin_id');

    const destinationSearch = document.getElementById('destination_search');
    const destinationAutocomplete = document.getElementById('destination_autocomplete');
    const cityIdInput = document.getElementById('city_id');

    let originTimeout = null;
    let destinationTimeout = null;

    // Function: Setup autocomplete for an input
    function setupAutocomplete(input, autocomplete, hiddenInput, name) {
        input.addEventListener('input', function() {
            const query = this.value.trim();
            console.log(name + ' search input:', query);

            // Clear previous timeout
            if (name === 'Origin' && originTimeout) {
                clearTimeout(originTimeout);
            } else if (name === 'Destination' && destinationTimeout) {
                clearTimeout(destinationTimeout);
            }

            // Reset if empty
            if (query.length === 0) {
                hiddenInput.value = '';
                autocomplete.style.display = 'none';
                resetShipping();
                return;
            }

            // Minimum 3 characters
            if (query.length < 3) {
                autocomplete.style.display = 'none';
                return;
            }

            // Debounce 500ms
            const timeout = setTimeout(() => {
                searchCities(query, autocomplete, hiddenInput, input, name);
            }, 500);

            if (name === 'Origin') {
                originTimeout = timeout;
            } else {
                destinationTimeout = timeout;
            }
        });

        // Hide autocomplete on click outside
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !autocomplete.contains(e.target)) {
                autocomplete.style.display = 'none';
            }
        });
    }

    // Setup autocomplete for both inputs
    setupAutocomplete(originSearch, originAutocomplete, originIdInput, 'Origin');
    setupAutocomplete(destinationSearch, destinationAutocomplete, cityIdInput, 'Destination');

    // Function: Search cities
    function searchCities(query, autocomplete, hiddenInput, input, name) {
        console.log('Searching ' + name + ' for:', query);

        fetch(`{{ url('/api/cities-search') }}?search=${encodeURIComponent(query)}&limit=20`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    throw new Error('Non-JSON response');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log(name + ' search results:', data);
            displayAutocomplete(data || [], autocomplete, hiddenInput, input, name);
        })
        .catch(error => {
            console.error(name + ' search error:', error);
        });
    }

    // Function: Display autocomplete results
    function displayAutocomplete(cities, autocomplete, hiddenInput, input, name) {
        console.log('Displaying ' + cities.length + ' results for ' + name);
        console.log('First result:', cities[0]);

        if (!cities || cities.length === 0) {
            autocomplete.innerHTML = '<div class="dropdown-item text-muted">Tidak ditemukan</div>';
            autocomplete.style.display = 'block';
            console.log('No results to display');
            return;
        }

        let html = '';
        cities.forEach((city, index) => {
            const cityName = city.city_name || 'Unknown';
            const districtName = city.district_name || '-';
            const subdistrictName = city.subdistrict_name || '-';
            const provinceName = city.province_name || '-';
            const zipCode = city.zip_code || '';
            const label = city.label || `${subdistrictName}, ${districtName}, ${cityName}, ${provinceName}`;

            console.log('Result ' + index + ':', { id: city.id, cityName, label });

            html += `
                <a href="#" class="dropdown-item" data-city-id="${city.id}" data-label="${label.replace(/"/g, '&quot;')}">
                    <div><strong>${cityName}</strong> <small class="text-muted">${zipCode}</small></div>
                    <small class="text-muted">${label}</small>
                </a>
            `;
        });

        console.log('Setting HTML with ' + cities.length + ' items');
        autocomplete.innerHTML = html;
        autocomplete.style.display = 'block';
        console.log('Autocomplete displayed for ' + name);

        // Attach click events
        autocomplete.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const cityId = this.getAttribute('data-city-id');
                const label = this.getAttribute('data-label');

                input.value = label;
                hiddenInput.value = cityId;
                autocomplete.style.display = 'none';

                console.log(name + ' selected:', cityId, label);
                resetShipping();

                // Calculate shipping if both origin and destination are selected
                if (originIdInput.value && cityIdInput.value) {
                    loadShippingCost();
                }
            });
        });
    }
    
    // -------------------------------------------------------------------------
    // EVENT LISTENER: Courier Change Handler
    // -------------------------------------------------------------------------
    // Trigger: Saat user mengganti pilihan kurir (radio button)
    // Tujuan: Hitung ulang ongkir dengan kurir yang berbeda
    
    document.querySelectorAll('input[name="courier"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Hanya hitung jika destination sudah dipilih (origin selalu set)
            if (cityIdInput.value) {
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
        document.getElementById('pay-button').disabled = true;
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
        const originId = document.getElementById('origin_id').value;
        const cityId = document.getElementById('city_id').value;
        const courier = document.querySelector('input[name="courier"]:checked').value;
        const weight = totalWeight;

        console.log('Shipping cost params:', { originId, cityId, courier, weight });

        // Validasi: Pastikan semua parameter ada sebelum request
        if (!originId || !cityId || !courier || !weight) {
            console.log('Missing required params, skipping shipping cost');
            return;
        }
        
        // AJAX Request menggunakan Fetch API
        fetch('{{ url('/api/shipping-cost') }}', {
            method: 'POST',  // HTTP method
            headers: {
                'Content-Type': 'application/json',  // Format data JSON
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')  // Token CSRF Laravel
            },
            body: JSON.stringify({  // Data yang dikirim ke server
                origin: originId,
                destination: cityId,
                weight: weight,
                courier: courier
            })
        })
        .then(response => response.json())  // Parse response sebagai JSON
        .then(data => {
            console.log('Shipping cost response:', data);
            console.log('Data type:', typeof data);
            console.log('Is array:', Array.isArray(data));
            console.log('Data keys:', data ? Object.keys(data) : 'null');

            // Cek jika ada data hasil
            if (data && data.length > 0) {
                // API lama: array dengan courier object (memiliki costs array)
                console.log('Using old API structure (array with courier objects)');
                displayShippingServices(data[0]); // Ambil courier pertama
            } else if (data && data.code) {
                // Single courier response (API lama dengan satu kurir)
                console.log('Using single courier structure');
                displayShippingServices(data);
            } else if (data && data.results && Array.isArray(data.results)) {
                // Response wrapped in results key (from logging format)
                console.log('Using results-wrapped structure');
                if (data.results.length > 0) {
                    displayShippingServices(data.results[0]);
                }
            } else {
                console.error('No shipping data found:', data);
                document.getElementById('shipping-services').innerHTML = '<p class="text-danger">Gagal memuat ongkir. Data: ' + JSON.stringify(data) + '</p>';
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

        console.log('Courier data received:', courierData);
        console.log('Type of courierData:', typeof courierData);
        console.log('Is array?', Array.isArray(courierData));

        // Cek jika data kosong atau undefined
        if (!courierData) {
            console.error('Courier data is null or undefined');
            servicesDiv.innerHTML = '<p class="text-danger">Gagal memuat layanan pengiriman</p>';
            return;
        }

        let html = '';

        // Cek struktur data API baru vs lama
        // API baru: Array langsung dengan field cost, service, description, etd
        // API lama: Object dengan costs array
        let services = [];
        if (courierData.costs && Array.isArray(courierData.costs)) {
            // Struktur lama
            console.log('Using old API structure (costs array)');
            services = courierData.costs;
        } else if (Array.isArray(courierData)) {
            // Struktur baru: Array of services
            console.log('Using new API structure (flat array)');
            services = courierData;
        } else {
            console.error('Unknown courier data structure:', courierData);
            servicesDiv.innerHTML = '<p class="text-danger">Gagal memuat layanan pengiriman</p>';
            return;
        }

        console.log('Services to display:', services);

        // Loop setiap layanan yang tersedia
        services.forEach((service, index) => {
            console.log('Processing service:', service);

            // Buat ID unik untuk radio button
            const serviceId = `service-${index}`;

            // Extract cost dengan benar - support struktur lama dan baru
            let cost = 0;
            if (service.cost && Array.isArray(service.cost) && service.cost.length > 0) {
                // Struktur lama: cost adalah array dengan object {value, etd}
                cost = parseInt(service.cost[0].value) || 0;
            } else if (typeof service.cost === 'number') {
                // Struktur baru: cost langsung number
                cost = service.cost;
            } else if (service.cost) {
                // Fallback
                cost = parseInt(service.cost) || 0;
            }

            const serviceName = service.service || service.name || 'Layanan';
            const description = service.description || '';
            let etd = '-';
            if (service.cost && Array.isArray(service.cost) && service.cost.length > 0) {
                etd = service.cost[0].etd || '-';
            } else if (service.etd) {
                etd = service.etd;
            }
            const courierCode = service.code || 'courier';

            console.log('Service parsed:', { serviceId, cost, serviceName, description, etd });

            // Build HTML untuk satu pilihan layanan
            html += `
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="shipping_option"
                           id="${serviceId}" value="${cost}"
                           data-service="${serviceName}">
                    <label class="form-check-label" for="${serviceId}">
                        <strong>${serviceName}</strong> ${description ? '- ' + description : ''}
                        <span class="float-end">Rp ${cost.toLocaleString('id-ID')}</span>
                        <br><small class="text-muted">Estimasi: ${etd}</small>
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

        const radioButtons = servicesDiv.querySelectorAll('input[name="shipping_option"]');
        console.log('Radio buttons found:', radioButtons.length);

        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                // Update hidden field dengan nama layanan
                document.getElementById('shipping_service').value = this.dataset.service;

                // Parse cost dengan fallback ke 0 jika NaN
                const shippingCost = parseInt(this.value) || 0;

                // Update hidden field dengan biaya ongkir
                document.getElementById('shipping_cost').value = shippingCost;

                // Update tampilan ongkir dengan format Rupiah
                document.getElementById('shipping-display').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');

                // Hitung total keseluruhan (subtotal + ongkir)
                const total = subtotal + shippingCost;
                document.getElementById('total').innerHTML = '<strong>Rp ' + total.toLocaleString('id-ID') + '</strong>';

                // Enable tombol submit (semua data sudah lengkap)
                document.getElementById('pay-button').disabled = false;
            });
        });
    }
    
// Tutup DOMContentLoaded event listener
});
</script>

@endsection