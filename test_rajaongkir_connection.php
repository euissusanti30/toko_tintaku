<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\RajaOngkirService;

echo "=== TEST KONEKSI RAJA ONGKIR ===\n\n";

// Buat instance RajaOngkirService
$rajaOngkir = new RajaOngkirService();

// Cek status demo mode
$reflection = new ReflectionClass($rajaOngkir);
$apiKeyProperty = $reflection->getProperty('apiKey');
$apiKeyProperty->setAccessible(true);
$apiKey = $apiKeyProperty->getValue($rajaOngkir);

$demoModeProperty = $reflection->getProperty('demoMode');
$demoModeProperty->setAccessible(true);
$demoMode = $demoModeProperty->getValue($rajaOngkir);

echo "API Key: " . ($apiKey ? $apiKey : "TIDAK DISET") . "\n";
echo "Demo Mode: " . ($demoMode ? "AKTIF" : "TIDAK AKTIF") . "\n";
echo "Status: " . ($demoMode ? "MENGGUNAKAN DATA DUMMY" : "TERHUBUNG KE API RAJA ONGKIR") . "\n\n";

// Test get provinces
echo "1. Test Get Provinces:\n";
try {
    $provinces = $rajaOngkir->getProvinces();
    echo "   ✓ Berhasil mengambil " . count($provinces) . " provinsi\n";
    if (count($provinces) > 0) {
        echo "   Contoh: " . $provinces[0]['name'] . " (ID: " . $provinces[0]['id'] . ")\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test get cities
echo "2. Test Get Cities:\n";
try {
    // New API requires search parameter, not province filter
    echo "   Note: API baru menggunakan search parameter, bukan filter provinsi\n";
    echo "   Skipping cities test (perlu parameter search)\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test shipping cost
echo "3. Test Get Shipping Cost:\n";
try {
    $cost = $rajaOngkir->getShippingCost(152, 101, 1000, 'jne'); // Jakarta -> Denpasar, 1kg
    if ($cost) {
        echo "   ✓ Berhasil menghitung ongkir\n";
        echo "   Detail: " . json_encode($cost, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "   ✗ Gagal menghitung ongkir\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== SELESAI ===\n";
