<?php

// =============================================================================
// FILE TESTING: test_checkout.php
// =============================================================================
// File ini digunakan untuk testing service RajaOngkir secara standalone
// tanpa harus menjalankan web server atau browser
//
// CARA MENJALANKAN:
//   php test_checkout.php
//
// TUJUAN:
//   1. Verifikasi bahwa RajaOngkirService berfungsi dengan baik
//   2. Test data provinsi dan kota (dari API atau mock)
//   3. Debug dan troubleshooting data lokasi
// =============================================================================

// STEP 1: BOOTSTRAP LARAVEL
// -------------------------------------------------------------------------
// Load autoloader dari Composer untuk mengenali namespace dan class
require_once 'vendor/autoload.php';

// Inisialisasi aplikasi Laravel
// bootstrap/app.php mengembalikan instance aplikasi yang sudah dikonfigurasi
$app = require_once 'bootstrap/app.php';

// Buat instance kernel console (untuk command-line)
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Bootstrap kernel untuk menginisialisasi service providers, facades, dll
$kernel->bootstrap();

// =============================================================================
// STEP 2: IMPORT SERVICE
// =============================================================================
// Import RajaOngkirService yang akan diuji
use App\Services\RajaOngkirService;

// =============================================================================
// STEP 3: TESTING SERVICE
// =============================================================================
// Gunakan try-catch untuk menangani error yang mungkin terjadi
try {
    
    // -------------------------------------------------------------------------
    // Inisialisasi Service
    // -------------------------------------------------------------------------
    // Buat instance baru dari RajaOngkirService
    // Constructor akan otomatis mengambil konfigurasi API key dari config/services.php
    $service = new RajaOngkirService();
    
    echo "Testing checkout data...\n";
    
    // -------------------------------------------------------------------------
    // TEST 1: Ambil Data Provinsi
    // -------------------------------------------------------------------------
    // Panggil method getProvinces() untuk mengambil semua provinsi
    // Data bisa dari API RajaOngkir atau mock data (jika demo mode)
    echo "\n[TEST 1] Mengambil data provinsi...\n";
    $provinces = $service->getProvinces();
    
    // Tampilkan jumlah provinsi yang ditemukan
    // count() menghitung jumlah elemen dalam array
    echo "Found " . count($provinces) . " provinces\n";
    
    // -------------------------------------------------------------------------
    // TEST 2: Ambil Data Kota
    // -------------------------------------------------------------------------
    // Panggil method getCities() untuk mengambil semua kota
    // Parameter: null (ambil semua kota, tanpa filter)
    echo "\n[TEST 2] Mengambil data kota...\n";
    $cities = $service->getCities();
    
    // Tampilkan total jumlah kota
    echo "Found " . count($cities) . " total cities\n";
    
    // -------------------------------------------------------------------------
    // TEST 3: Grouping Kota per Provinsi
    // -------------------------------------------------------------------------
    // Logic: Mengelompokkan kota berdasarkan province_id
    // Tujuannya: Mengetahui kota mana yang termasuk ke provinsi mana
    
    echo "\n[TEST 3] Mengelompokkan kota per provinsi...\n";
    
    // Array kosong untuk menyimpan hasil grouping
    // Struktur: [province_id => [city_name1, city_name2, ...]]
    $citiesByProvince = [];
    
    // Loop semua kota dan kelompokkan berdasarkan province_id
    foreach ($cities as $city) {
        $provinceId = $city['province_id'];  // Ambil ID provinsi dari data kota
        
        // Jika provinsi belum ada di array, inisialisasi dengan array kosong
        if (!isset($citiesByProvince[$provinceId])) {
            $citiesByProvince[$provinceId] = [];
        }
        
        // Tambahkan nama kota ke array provinsi tersebut
        $citiesByProvince[$provinceId][] = $city['city_name'];
    }
    
    // -------------------------------------------------------------------------
    // OUTPUT: Tampilkan Hasil Grouping
    // -------------------------------------------------------------------------
    echo "\n========================================\n";
    echo "DAFTAR KOTA PER PROVINSI:\n";
    echo "========================================\n";
    
    // Loop untuk setiap provinsi yang memiliki kota
    foreach ($citiesByProvince as $provinceId => $provinceCities) {
        
        // Cari nama provinsi berdasarkan ID
        // Loop provinsi untuk mencocokkan province_id
        $provinceName = '';  // Default: string kosong jika tidak ditemukan
        foreach ($provinces as $province) {
            if ($province['province_id'] == $provinceId) {
                $provinceName = $province['province'];  // Ambil nama provinsi
                break;  // Keluar dari loop setelah ketemu
            }
        }
        
        // Tampilkan header provinsi dengan jumlah kota
        echo "\nProvince $provinceId ($provinceName): " . count($provinceCities) . " cities\n";
        
        // Tampilkan semua kota dalam provinsi ini dengan indentasi
        foreach ($provinceCities as $city) {
            echo "  - $city\n";  // Indentasi 2 spasi untuk hierarki
        }
        echo "\n";  // Baris kosong antar provinsi
    }
    
    // Output akhir jika berhasil
    echo "========================================\n";
    echo "TESTING SELESAI - SEMUA BERHASIL!\n";
    echo "========================================\n";
    
} catch (Exception $e) {
    // =============================================================================
    // ERROR HANDLING
    // =============================================================================
    // Jika terjadi error selama testing, tampilkan detail error
    
    echo "\n========================================\n";
    echo "ERROR TERJADI!\n";
    echo "========================================\n";
    
    // Tampilkan pesan error
    echo "Exception Message: " . $e->getMessage() . "\n";
    
    // Tampilkan stack trace (urutan file dan line yang menyebabkan error)
    // Berguna untuk debugging
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
}
