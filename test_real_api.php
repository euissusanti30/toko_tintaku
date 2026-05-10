<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\RajaOngkirService;

echo "=== TEST REAL API RAJA ONGKIR ===\n\n";

$rajaOngkir = new RajaOngkirService();

// Test 1: Provinces
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

// Test 2: Search Cities
echo "2. Test Search Cities (search: 'jakarta'):\n";
try {
    $cities = $rajaOngkir->searchCities('jakarta', 5);
    echo "   ✓ Berhasil mengambil " . count($cities) . " kota\n";
    if (count($cities) > 0) {
        echo "   Contoh:\n";
        foreach (array_slice($cities, 0, 3) as $city) {
            echo "   - " . ($city['label'] ?? $city['subdistrict_name'] ?? 'Unknown') . " (ID: " . $city['id'] . ")\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Shipping Cost with real API (need valid district IDs from search)
echo "3. Test Shipping Cost:\n";
try {
    // Get a valid destination ID from search first
    $cities = $rajaOngkir->searchCities('bandung', 1);
    if (count($cities) > 0) {
        $destinationId = $cities[0]['id'];
        echo "   Menggunakan destination ID: " . $destinationId . "\n";

        // Use same city as origin for testing
        $cost = $rajaOngkir->getShippingCost($destinationId, $destinationId, 1000, 'jne');

        if (is_array($cost) && count($cost) > 0) {
            echo "   ✓ Berhasil mengambil ongkir\n";
            foreach ($cost as $service) {
                $serviceName = $service['service'] ?? $service['name'] ?? 'Unknown';
                $price = $service['cost'] ?? 0;
                if (is_array($price)) {
                    $price = $price[0]['value'] ?? 0;
                }
                echo "   - " . $serviceName . ": Rp " . number_format($price) . "\n";
            }
        } else {
            echo "   ✗ Tidak ada data ongkir\n";
        }
    } else {
        echo "   ✗ Tidak ada kota ditemukan untuk test\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== SELESAI ===\n";
