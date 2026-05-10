<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\RajaOngkirService;

echo "=== TEST CITIES DATA ===\n\n";

$rajaOngkir = new RajaOngkirService();

// Test get all cities
echo "1. Test Get All Cities:\n";
try {
    $cities = $rajaOngkir->getCities();
    echo "   Count: " . count($cities) . "\n";
    if (count($cities) > 0) {
        echo "   First city: " . $cities[0]['city_name'] . " (province_id: " . $cities[0]['province_id'] . ")\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test get cities for DKI Jakarta (ID: 10)
echo "2. Test Get Cities for DKI Jakarta (ID: 10):\n";
try {
    $cities = $rajaOngkir->getCities(10);
    echo "   Count: " . count($cities) . "\n";
    if (count($cities) > 0) {
        foreach ($cities as $city) {
            echo "   - " . $city['city_name'] . " (ID: " . $city['id'] . ", province_id: " . $city['province_id'] . ")\n";
        }
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test get cities for Bali (ID: 15)
echo "3. Test Get Cities for Bali (ID: 15):\n";
try {
    $cities = $rajaOngkir->getCities(15);
    echo "   Count: " . count($cities) . "\n";
    if (count($cities) > 0) {
        foreach ($cities as $city) {
            echo "   - " . $city['city_name'] . " (ID: " . $city['id'] . ", province_id: " . $city['province_id'] . ")\n";
        }
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n=== SELESAI ===\n";
