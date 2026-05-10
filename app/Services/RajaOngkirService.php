<?php

// =============================================================================
// RAJA ONGKIR SERVICE
// =============================================================================
// File ini berisi service untuk menghubungkan aplikasi dengan API RajaOngkir
// RajaOngkir adalah layanan yang menyediakan data provinsi, kota, dan ongkir
// di seluruh Indonesia
// =============================================================================

namespace App\Services;

// Import facade yang diperlukan
use Illuminate\Support\Facades\Http;    // Untuk melakukan HTTP request ke API
use Illuminate\Support\Facades\Cache;   // Untuk caching data agar lebih cepat

class RajaOngkirService
{
    // -------------------------------------------------------------------------
    // PROPERTY / ATRIBUT
    // -------------------------------------------------------------------------
    // Variabel-variabel ini menyimpan konfigurasi dan status service
    
    protected $apiKey;      // Menyimpan API key dari RajaOngkir
    protected $baseUrl;     // Menyimpan URL dasar API RajaOngkir
    protected $demoMode;    // Status demo mode (true = pakai data dummy)

    // ==========================================================================
    // CONSTRUCTOR / KONSTRUKTOR
    // ==========================================================================
    // Fungsi ini dijalankan otomatis saat class RajaOngkirService dibuat (new)
    // Tujuannya untuk mengatur konfigurasi awal service
    
    public function __construct()
    {
        // Ambil API key dari file konfigurasi (config/services.php atau .env)
        // Jika tidak ada API key, maka akan kosong (empty)
        $this->apiKey = config('services.rajaongkir.api_key');
        
        // Ambil base URL API RajaOngkir dari konfigurasi
        // Default: https://api.rajaongkir.com/starter (versi starter/gratis)
        $this->baseUrl = config('services.rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        
        // Demo mode - aktif jika:
        // 1. Tidak ada API key (empty), atau
        // 2. Demo mode diaktifkan di konfigurasi
        // Mode demo akan menggunakan data dummy/mock data
        $this->demoMode = empty($this->apiKey) || config('services.rajaongkir.demo_mode', false);
    }

    // ==========================================================================
    // FUNGSI: GET PROVINCES (Ambil Data Provinsi)
    // ==========================================================================
    // Fungsi ini digunakan untuk mengambil semua data provinsi di Indonesia
    // dari API RajaOngkir atau data dummy jika dalam demo mode
    // 
    // RETURN: Array berisi data provinsi
    //         Contoh: [['province_id' => 1, 'province' => 'Bali'], ...]
    
    public function getProvinces()
    {
        // CEK: Jika dalam demo mode, langsung return data dummy/mock
        // Ini berguna untuk testing tanpa perlu API key
        if ($this->demoMode) {
            return $this->getMockProvinces();
        }

        // Gunakan Cache untuk menyimpan data selama 3600 detik (1 jam)
        // Tujuannya: mengurangi request ke API, mempercepat loading
        // Cache key: 'rajaongkir_provinces'
        return Cache::remember('rajaongkir_provinces', 3600, function () {
            try {
                // Buat HTTP GET request ke endpoint /province
                // Header 'key' berisi API key untuk autentikasi
                // Timeout 30 detik (increased untuk API yang lambat)
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->timeout(30)->get($this->baseUrl . '/destination/province');

                // Cek apakah response berhasil (status 200)
                if ($response->successful()) {
                    // Ambil data dari response JSON
                    // Struktur baru: data -> [data provinsi]
                    return $response->json()['data'];
                }
            } catch (\Exception $e) {
                // Jika terjadi error (misal: timeout, API down), 
                // log error dan fallback ke mock data
                \Log::warning('RajaOngkir API failed, using mock data: ' . $e->getMessage());
                return $this->getMockProvinces();
            }

            // Jika response tidak berhasil, fallback ke mock data
            return $this->getMockProvinces();
        });
    }

    // ==========================================================================
    // FUNGSI: GET CITIES (Ambil Data Kota/Kabupaten)
    // ==========================================================================
    // Fungsi ini digunakan untuk mengambil data kota/kabupaten
    // Bisa filter berdasarkan provinsi atau ambil semua kota
    //
    // PARAMETER: $provinceId (opsional) - ID provinsi untuk filter
    //            Jika null, ambil semua kota dari semua provinsi
    //
    // RETURN: Array berisi data kota
    //         Contoh: [['city_id' => 101, 'province_id' => 1, 'city_name' => 'Denpasar'], ...]
    
    public function getCities($provinceId = null)
    {
        // CEK: Jika dalam demo mode, langsung return data dummy/mock
        if ($this->demoMode) {
            return $this->getMockCities($provinceId);
        }

        // API baru tidak support get all cities tanpa search parameter
        // Return empty array - cities akan diambil via searchCities() method
        \Log::info('getCities() called but API requires search parameter. Use searchCities() instead.');
        return [];
    }

    // ==========================================================================
    // FUNGSI: SEARCH CITIES (Cari Kota/Kecamatan)
    // ==========================================================================
    // Parameter: $search - Query pencarian (nama kota/kecamatan)
    //            $limit - Jumlah hasil maksimal (default: 20)
    // Return: Array data kota/kecamatan dari API
    //
    // Digunakan untuk AJAX search di frontend saat user ketik nama kota

    public function searchCities($search, $limit = 20)
    {
        // CEK: Jika dalam demo mode, return data dummy yang difilter
        if ($this->demoMode) {
            return $this->searchMockCities($search, $limit);
        }

        return Cache::remember('rajaongkir_search_' . md5($search), 3600, function () use ($search, $limit) {
            try {
                // API baru menggunakan endpoint /destination/domestic-destination dengan parameter search
                $url = $this->baseUrl . '/destination/domestic-destination?search=' . urlencode($search) . '&limit=' . $limit;

                \Log::info('Searching cities', ['search' => $search, 'url' => $url]);

                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->timeout(30)->get($url);

                if ($response->successful()) {
                    $data = $response->json()['data'] ?? [];
                    \Log::info('Search cities result', ['count' => count($data)]);
                    return $data;
                }

                \Log::warning('Search cities failed with status', ['status' => $response->status()]);
                return $this->searchMockCities($search, $limit);
            } catch (\Exception $e) {
                \Log::warning('RajaOngkir search cities failed, using mock: ' . $e->getMessage());
                return $this->searchMockCities($search, $limit);
            }
        });
    }

    // ==========================================================================
    // FUNGSI: GET SHIPPING COST (Hitung Ongkos Kirim)
    // ==========================================================================
    // Fungsi ini digunakan untuk menghitung biaya pengiriman dari kota asal ke kota tujuan
    // menggunakan jasa kurir tertentu (JNE, TIKI, POS, dll)
    //
    // PARAMETER:
    //   $origin       - ID kota asal pengiriman (contoh: 152 untuk Jakarta Pusat)
    //   $destination  - ID kota tujuan pengiriman (contoh: 101 untuk Denpasar)
    //   $weight       - Berat barang dalam gram (contoh: 1000 = 1 kg)
    //   $courier      - Kode kurir (default: 'jne', bisa 'tiki', 'pos', dll)
    //
    // RETURN: Array berisi data ongkir dengan berbagai layanan
    //         Contoh: [{'code': 'jne', 'name': 'JNE', 'costs': [...]}]
    
    public function getShippingCost($origin, $destination, $weight, $courier = 'jne')
    {
        // CEK: Jika dalam demo mode, return ongkir dummy/mock
        if ($this->demoMode) {
            return $this->getMockShippingCost($courier, $weight);
        }

        try {
            // API baru menggunakan endpoint /calculate/district/domestic-cost
            // Parameter: origin (district ID), destination (district ID), weight (gram), courier
            $url = $this->baseUrl . '/calculate/district/domestic-cost';

            \Log::info('Calculating shipping cost', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ]);

            $response = Http::withHeaders([
                'key' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->timeout(30)->asForm()->post($url, [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier,
                'price' => 'lowest'
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                \Log::info('Shipping cost result', ['count' => count($data)]);
                return $data;
            }

            \Log::warning('Shipping cost failed, using mock', ['status' => $response->status()]);
            return $this->getMockShippingCost($courier, $weight);
        } catch (\Exception $e) {
            \Log::warning('RajaOngkir shipping cost failed, using mock: ' . $e->getMessage());
            return $this->getMockShippingCost($courier, $weight);
        }
    }

    // ==========================================================================
    // MOCK DATA METHODS (Data Dummy untuk Demo Mode)
    // ==========================================================================
    // Method-method ini menyediakan data palsu untuk testing
    // Digunakan ketika tidak ada API key atau demo mode aktif
    
    // --------------------------------------------------------------------------
    // FUNGSI: GET MOCK PROVINCES (Data Dummy Provinsi)
    // --------------------------------------------------------------------------
    // Return: Array data provinsi palsu untuk demo
    // Note: Data ini statis, tidak mengambil dari API
    
    private function getMockProvinces()
    {
        return [
            ['id' => 1, 'name' => 'Bali'],
            ['id' => 2, 'name' => 'Jakarta'],
            ['id' => 3, 'name' => 'Jawa Barat'],
            ['id' => 4, 'name' => 'Jawa Tengah'],
            ['id' => 5, 'name' => 'Jawa Timur'],
            ['id' => 6, 'name' => 'Yogyakarta'],
            ['id' => 7, 'name' => 'Sumatera Utara'],
            ['id' => 8, 'name' => 'Sumatera Barat'],
            ['id' => 9, 'name' => 'Sumatera Selatan'],
            ['id' => 10, 'name' => 'Kalimantan'],
        ];
    }

    // --------------------------------------------------------------------------
    // FUNGSI: GET MOCK CITIES (Data Dummy Kota)
    // --------------------------------------------------------------------------
    // Parameter: $provinceId - ID provinsi untuk filter (null = semua kota)
    // Return: Array data kota palsu yang difilter berdasarkan provinsi
    // Note: Menggunakan array_filter untuk menyaring kota sesuai provinsi
    
    private function getMockCities($provinceId)
    {
        // Data kota dummy dengan relasi ke provinsi
        // Struktur: id, province_id, city_name, label, province_name
        // Province_id sesuai dengan API asli (DKI Jakarta=10, Bali=15, dll)
        $allCities = [
            // Bali (ID: 15)
            ['id' => 101, 'province_id' => 15, 'city_name' => 'Denpasar', 'label' => 'Denpasar, Bali', 'province_name' => 'BALI'],
            ['id' => 102, 'province_id' => 15, 'city_name' => 'Badung', 'label' => 'Badung, Bali', 'province_name' => 'BALI'],
            ['id' => 103, 'province_id' => 15, 'city_name' => 'Gianyar', 'label' => 'Gianyar, Bali', 'province_name' => 'BALI'],
            // DKI Jakarta (ID: 10)
            ['id' => 152, 'province_id' => 10, 'city_name' => 'Jakarta Pusat', 'label' => 'Jakarta Pusat, DKI Jakarta', 'province_name' => 'DKI JAKARTA'],
            ['id' => 153, 'province_id' => 10, 'city_name' => 'Jakarta Utara', 'label' => 'Jakarta Utara, DKI Jakarta', 'province_name' => 'DKI JAKARTA'],
            ['id' => 154, 'province_id' => 10, 'city_name' => 'Jakarta Selatan', 'label' => 'Jakarta Selatan, DKI Jakarta', 'province_name' => 'DKI JAKARTA'],
            ['id' => 155, 'province_id' => 10, 'city_name' => 'Jakarta Timur', 'label' => 'Jakarta Timur, DKI Jakarta', 'province_name' => 'DKI JAKARTA'],
            ['id' => 156, 'province_id' => 10, 'city_name' => 'Jakarta Barat', 'label' => 'Jakarta Barat, DKI Jakarta', 'province_name' => 'DKI JAKARTA'],
            // Jawa Barat (ID: 5)
            ['id' => 201, 'province_id' => 5, 'city_name' => 'Bandung', 'label' => 'Bandung, Jawa Barat', 'province_name' => 'JAWA BARAT'],
            ['id' => 202, 'province_id' => 5, 'city_name' => 'Bogor', 'label' => 'Bogor, Jawa Barat', 'province_name' => 'JAWA BARAT'],
            ['id' => 203, 'province_id' => 5, 'city_name' => 'Bekasi', 'label' => 'Bekasi, Jawa Barat', 'province_name' => 'JAWA BARAT'],
            ['id' => 204, 'province_id' => 5, 'city_name' => 'Depok', 'label' => 'Depok, Jawa Barat', 'province_name' => 'JAWA BARAT'],
            // Jawa Tengah (ID: 12)
            ['id' => 301, 'province_id' => 12, 'city_name' => 'Semarang', 'label' => 'Semarang, Jawa Tengah', 'province_name' => 'JAWA TENGAH'],
            ['id' => 302, 'province_id' => 12, 'city_name' => 'Solo', 'label' => 'Solo, Jawa Tengah', 'province_name' => 'JAWA TENGAH'],
            // DI Yogyakarta (ID: 19)
            ['id' => 303, 'province_id' => 19, 'city_name' => 'Yogyakarta', 'label' => 'Yogyakarta, DI Yogyakarta', 'province_name' => 'DI YOGYAKARTA'],
            // Jawa Timur (ID: 18)
            ['id' => 401, 'province_id' => 18, 'city_name' => 'Surabaya', 'label' => 'Surabaya, Jawa Timur', 'province_name' => 'JAWA TIMUR'],
            ['id' => 402, 'province_id' => 18, 'city_name' => 'Malang', 'label' => 'Malang, Jawa Timur', 'province_name' => 'JAWA TIMUR'],
            ['id' => 403, 'province_id' => 18, 'city_name' => 'Kediri', 'label' => 'Kediri, Jawa Timur', 'province_name' => 'JAWA TIMUR'],
        ];

        // Jika tidak ada filter provinsi, return semua kota
        if ($provinceId === null) {
            return $allCities;
        }

        // Filter kota berdasarkan province_id yang diberikan
        // Callback function: cek jika city['province_id'] sama dengan $provinceId
        return array_filter($allCities, function($city) use ($provinceId) {
            return $city['province_id'] == $provinceId;
        });
    }

    // --------------------------------------------------------------------------
    // FUNGSI: SEARCH MOCK CITIES (Cari Kota Dummy)
    // --------------------------------------------------------------------------
    // Parameter: $search - Query pencarian
    //            $limit - Jumlah hasil maksimal
    // Return: Array data kota dummy yang cocok dengan pencarian

    private function searchMockCities($search, $limit)
    {
        $allCities = $this->getMockCities(null);
        $searchLower = strtolower($search);

        $results = array_filter($allCities, function($city) use ($searchLower) {
            return strpos(strtolower($city['city_name']), $searchLower) !== false ||
                   strpos(strtolower($city['label']), $searchLower) !== false;
        });

        return array_slice(array_values($results), 0, $limit);
    }

    // --------------------------------------------------------------------------
    // FUNGSI: GET MOCK SHIPPING COST (Ongkir Dummy)
    // --------------------------------------------------------------------------
    // Parameter: 
    //   $courier - Kode kurir (jne/tiki/pos)
    //   $weight  - Berat barang dalam gram
    // Return: Array ongkir dummy dengan format sama seperti API RajaOngkir
    // Logic: Hitung harga berdasarkan berat (weight * rate) dengan harga minimum
    
    private function getMockShippingCost($courier, $weight)
    {
        // Struktur data ongkir per kurir dengan layanan yang tersedia
        // value: harga dihitung dari max(harga_minimum, berat * rate)
        // etd: estimated time delivery (estimasi hari pengiriman)
        $baseCost = [
            'jne' => [
                [
                    'service' => 'OKE',
                    'description' => 'Ongkos Kirim Ekonomis',
                    'cost' => [
                        [
                            'value' => intval(max(15000, $weight * 0.1)),
                            'etd' => '2-3'
                        ]
                    ]
                ],
                [
                    'service' => 'REG',
                    'description' => 'Layanan Reguler',
                    'cost' => [
                        [
                            'value' => intval(max(20000, $weight * 0.15)),
                            'etd' => '1-2'
                        ]
                    ]
                ],
                [
                    'service' => 'YES',
                    'description' => 'Yakin Esok Sampai',
                    'cost' => [
                        [
                            'value' => intval(max(30000, $weight * 0.2)),
                            'etd' => '1'
                        ]
                    ]
                ]
            ],
            'tiki' => [
                [
                    'service' => 'REG',
                    'description' => 'Layanan Reguler',
                    'cost' => [
                        [
                            'value' => intval(max(18000, $weight * 0.12)),
                            'etd' => '2-3'
                        ]
                    ]
                ],
                [
                    'service' => 'ONS',
                    'description' => 'Over Night Service',
                    'cost' => [
                        [
                            'value' => intval(max(25000, $weight * 0.18)),
                            'etd' => '1'
                        ]
                    ]
                ]
            ],
            'pos' => [
                [
                    'service' => 'KILAT',
                    'description' => 'Paket Kilat',
                    'cost' => [
                        [
                            'value' => intval(max(12000, $weight * 0.08)),
                            'etd' => '3-4'
                        ]
                    ]
                ],
                [
                    'service' => 'EXPRESS',
                    'description' => 'Express',
                    'cost' => [
                        [
                            'value' => intval(max(22000, $weight * 0.14)),
                            'etd' => '1-2'
                        ]
                    ]
                ]
            ]
        ];

        \Log::info('Mock shipping cost', ['courier' => $courier, 'weight' => $weight, 'data' => $baseCost[$courier] ?? []]);

        // Return format yang sama dengan response API RajaOngkir lama
        // Struktur lama: array dengan object yang punya costs array
        return [
            [
                'code' => strtoupper($courier),      // Kode kurir uppercase
                'name' => strtoupper($courier),      // Nama kurir uppercase
                'costs' => $baseCost[$courier] ?? []  // Layanan kurir atau array kosong
            ]
        ];
    }

    // ==========================================================================
    // HELPER METHODS (Method Bantuan)
    // ==========================================================================
    
    // --------------------------------------------------------------------------
    // FUNGSI: GET AVAILABLE COURIERS (Daftar Kurir Tersedia)
    // --------------------------------------------------------------------------
    // Return: Array asosiatif berisi kurir yang didukung
    //         Key: kode kurir (jne, tiki, pos)
    //         Value: nama lengkap kurir
    
    public function getAvailableCouriers()
    {
        return [
            'jne' => 'JNE',              // JNE Express
            'tiki' => 'TIKI',            // TIKI
            'pos' => 'POS Indonesia'    // POS Indonesia
        ];
    }

    // --------------------------------------------------------------------------
    // FUNGSI: FORMAT COST (Format Rupiah)
    // --------------------------------------------------------------------------
    // Parameter: $cost - Angka biaya (contoh: 1500000)
    // Return: String format Rupiah (contoh: "Rp 1.500.000")
    // Contoh: 1500000 -> "Rp 1.500.000"
    
    public function formatCost($cost)
    {
        // number_format parameter:
        // 1: angka, 0: desimal, ',': separator ribuan, '.': separator desimal
        return 'Rp ' . number_format($cost, 0, ',', '.');
    }

    // --------------------------------------------------------------------------
    // FUNGSI: GET CITY NAME (Ambil Nama Kota)
    // --------------------------------------------------------------------------
    // Parameter: $cityId - ID kota yang dicari
    // Return: String nama kota (contoh: "Denpasar") atau null jika tidak ditemukan
    // Logic: Loop semua kota, cocokkan city_id, return city_name
    
    public function getCityName($cityId)
    {
        // Ambil semua data kota
        $cities = $this->getCities();
        
        // Loop untuk mencari kota dengan ID yang cocok
        foreach ($cities as $city) {
            if ($city['city_id'] == $cityId) {
                return $city['city_name'];  // Return nama kota jika ketemu
            }
        }
        
        // Return null jika kota tidak ditemukan
        return null;
    }

    // --------------------------------------------------------------------------
    // FUNGSI: GET PROVINCE NAME (Ambil Nama Provinsi)
    // --------------------------------------------------------------------------
    // Parameter: $provinceId - ID provinsi yang dicari
    // Return: String nama provinsi (contoh: "Bali") atau null jika tidak ditemukan
    // Logic: Loop semua provinsi, cocokkan id, return name

    public function getProvinceName($provinceId)
    {
        // Ambil semua data provinsi
        $provinces = $this->getProvinces();

        // Loop untuk mencari provinsi dengan ID yang cocok
        foreach ($provinces as $province) {
            if ($province['id'] == $provinceId) {
                return $province['name'];  // Return nama provinsi jika ketemu
            }
        }

        // Return null jika provinsi tidak ditemukan
        return null;
    }

    // --------------------------------------------------------------------------
    // FUNGSI: GET PROVINCE ID FROM NAME (Ambil ID dari Nama Provinsi)
    // --------------------------------------------------------------------------
    // Parameter: $provinceName - Nama provinsi yang dicari
    // Return: Integer ID provinsi atau null jika tidak ditemukan
    // Logic: Loop semua provinsi, cocokkan name, return id

    private function getProvinceIdFromName($provinceName)
    {
        // Ambil semua data provinsi
        $provinces = $this->getProvinces();

        // Loop untuk mencari provinsi dengan nama yang cocok
        foreach ($provinces as $province) {
            // Case-insensitive comparison untuk fleksibilitas
            if (strcasecmp($province['name'], $provinceName) === 0) {
                return $province['id'];
            }
        }

        // Return null jika provinsi tidak ditemukan
        return null;
    }
    
    // ==========================================================================
    // END OF CLASS RAJA ONGKIR SERVICE
    // ==========================================================================
}
