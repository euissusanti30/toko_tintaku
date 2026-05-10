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
                // Timeout 10 detik (jika lebih, dianggap gagal)
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->timeout(10)->get($this->baseUrl . '/province');

                // Cek apakah response berhasil (status 200)
                if ($response->successful()) {
                    // Ambil data dari response JSON
                    // Struktur: rajaongkir -> results -> [data provinsi]
                    return $response->json()['rajaongkir']['results'];
                }
            } catch (\Exception $e) {
                // Jika terjadi error (misal: timeout, API down), 
                // log warning dan fallback ke demo mode
                \Log::warning('RajaOngkir API failed, falling back to demo mode: ' . $e->getMessage());
                return $this->getMockProvinces();
            }

            // Jika response tidak berhasil, return array kosong
            return [];
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

        // Tentukan cache key berdasarkan apakah ada filter provinsi atau tidak
        // Jika ada provinceId: cache key = "rajaongkir_cities_1" (contoh)
        // Jika tidak ada: cache key = "rajaongkir_cities"
        $cacheKey = $provinceId ? "rajaongkir_cities_{$provinceId}" : 'rajaongkir_cities';
        
        // Gunakan Cache untuk menyimpan data selama 3600 detik (1 jam)
        // Cache key dinamis berdasarkan provinsi yang dipilih
        return Cache::remember($cacheKey, 3600, function () use ($provinceId) {
            try {
                // Buat URL endpoint
                // Base URL: /city
                // Jika ada provinceId: /city?province={provinceId}
                $url = $this->baseUrl . '/city';
                if ($provinceId) {
                    $url .= "?province={$provinceId}";
                }

                // Buat HTTP GET request ke endpoint /city
                // Header 'key' berisi API key untuk autentikasi
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->timeout(10)->get($url);

                // Cek apakah response berhasil (status 200)
                if ($response->successful()) {
                    // Ambil data dari response JSON
                    return $response->json()['rajaongkir']['results'];
                }
            } catch (\Exception $e) {
                // Jika terjadi error, log warning dan fallback ke demo mode
                \Log::warning('RajaOngkir API failed, falling back to demo mode: ' . $e->getMessage());
                return $this->getMockCities($provinceId);
            }

            // Jika response tidak berhasil, return array kosong
            return [];
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

        // Buat HTTP POST request ke endpoint /cost
        // POST body berisi parameter ongkir yang diperlukan
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->post($this->baseUrl . '/cost', [
            'origin' => $origin,              // ID kota asal
            'originType' => 'city',             // Tipe asal: city (kota)
            'destination' => $destination,    // ID kota tujuan
            'destinationType' => 'city',        // Tipe tujuan: city (kota)
            'weight' => $weight,              // Berat dalam gram
            'courier' => $courier             // Kode kurir (jne/tiki/pos)
        ]);

        // Cek apakah response berhasil
        if ($response->successful()) {
            // Ambil data hasil perhitungan ongkir dari response
            return $response->json()['rajaongkir']['results'];
        }

        // Jika gagal, return null
        return null;
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
            ['province_id' => 1, 'province' => 'Bali'],
            ['province_id' => 2, 'province' => 'Jakarta'],
            ['province_id' => 3, 'province' => 'Jawa Barat'],
            ['province_id' => 4, 'province' => 'Jawa Tengah'],
            ['province_id' => 5, 'province' => 'Jawa Timur'],
            ['province_id' => 6, 'province' => 'Yogyakarta'],
            ['province_id' => 7, 'province' => 'Sumatera Utara'],
            ['province_id' => 8, 'province' => 'Sumatera Barat'],
            ['province_id' => 9, 'province' => 'Sumatera Selatan'],
            ['province_id' => 10, 'province' => 'Kalimantan'],
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
        // Struktur: city_id, province_id, city_name
        $allCities = [
            ['city_id' => 101, 'province_id' => 1, 'city_name' => 'Denpasar'], // Bali
            ['city_id' => 102, 'province_id' => 1, 'city_name' => 'Badung'],
            ['city_id' => 103, 'province_id' => 1, 'city_name' => 'Gianyar'],
            ['city_id' => 152, 'province_id' => 2, 'city_name' => 'Jakarta Pusat'], // Jakarta
            ['city_id' => 153, 'province_id' => 2, 'city_name' => 'Jakarta Utara'],
            ['city_id' => 154, 'province_id' => 2, 'city_name' => 'Jakarta Selatan'],
            ['city_id' => 155, 'province_id' => 2, 'city_name' => 'Jakarta Timur'],
            ['city_id' => 156, 'province_id' => 2, 'city_name' => 'Jakarta Barat'],
            ['city_id' => 201, 'province_id' => 3, 'city_name' => 'Bandung'], // Jawa Barat
            ['city_id' => 202, 'province_id' => 3, 'city_name' => 'Bogor'],
            ['city_id' => 203, 'province_id' => 3, 'city_name' => 'Bekasi'],
            ['city_id' => 204, 'province_id' => 3, 'city_name' => 'Depok'],
            ['city_id' => 301, 'province_id' => 4, 'city_name' => 'Semarang'], // Jawa Tengah
            ['city_id' => 302, 'province_id' => 4, 'city_name' => 'Solo'],
            ['city_id' => 303, 'province_id' => 4, 'city_name' => 'Yogyakarta'],
            ['city_id' => 401, 'province_id' => 5, 'city_name' => 'Surabaya'], // Jawa Timur
            ['city_id' => 402, 'province_id' => 5, 'city_name' => 'Malang'],
            ['city_id' => 403, 'province_id' => 5, 'city_name' => 'Kediri'],
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
                            'value' => max(15000, $weight * 0.1),
                            'etd' => '2-3'
                        ]
                    ]
                ],
                [
                    'service' => 'REG',
                    'description' => 'Layanan Reguler',
                    'cost' => [
                        [
                            'value' => max(20000, $weight * 0.15),
                            'etd' => '1-2'
                        ]
                    ]
                ],
                [
                    'service' => 'YES',
                    'description' => 'Yakin Esok Sampai',
                    'cost' => [
                        [
                            'value' => max(30000, $weight * 0.2),
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
                            'value' => max(18000, $weight * 0.12),
                            'etd' => '2-3'
                        ]
                    ]
                ],
                [
                    'service' => 'ONS',
                    'description' => 'Over Night Service',
                    'cost' => [
                        [
                            'value' => max(25000, $weight * 0.18),
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
                            'value' => max(12000, $weight * 0.08),
                            'etd' => '3-4'
                        ]
                    ]
                ],
                [
                    'service' => 'EXPRESS',
                    'description' => 'Express',
                    'cost' => [
                        [
                            'value' => max(22000, $weight * 0.14),
                            'etd' => '1-2'
                        ]
                    ]
                ]
            ]
        ];

        // Return format yang sama dengan response API RajaOngkir
        // Jika kurir tidak ditemukan, return costs kosong (?? [])
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
    // Logic: Loop semua provinsi, cocokkan province_id, return province
    
    public function getProvinceName($provinceId)
    {
        // Ambil semua data provinsi
        $provinces = $this->getProvinces();
        
        // Loop untuk mencari provinsi dengan ID yang cocok
        foreach ($provinces as $province) {
            if ($province['province_id'] == $provinceId) {
                return $province['province'];  // Return nama provinsi jika ketemu
            }
        }
        
        // Return null jika provinsi tidak ditemukan
        return null;
    }
    
    // ==========================================================================
    // END OF CLASS RAJA ONGKIR SERVICE
    // ==========================================================================
}
