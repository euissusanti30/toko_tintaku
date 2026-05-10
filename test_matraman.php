<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\RajaOngkirService;

echo "=== CARI ID MATRAMAN JAKARTA PUSAT ===\n\n";

$rajaOngkir = new RajaOngkirService();

// Search for matraman
$results = $rajaOngkir->searchCities('matraman', 10);

echo "Hasil pencarian 'matraman':\n";
foreach ($results as $city) {
    echo "ID: " . $city['id'] . "\n";
    echo "Label: " . ($city['label'] ?? 'N/A') . "\n";
    echo "City: " . ($city['city_name'] ?? 'N/A') . "\n";
    echo "District: " . ($city['district_name'] ?? 'N/A') . "\n";
    echo "Subdistrict: " . ($city['subdistrict_name'] ?? 'N/A') . "\n";
    echo "Province: " . ($city['province_name'] ?? 'N/A') . "\n";
    echo "Zip: " . ($city['zip_code'] ?? 'N/A') . "\n";
    echo "---\n";
}

echo "\n=== CARI ID JAKARTA PUSAT ===\n\n";

// Search for jakarta pusat
$results2 = $rajaOngkir->searchCities('jakarta pusat', 10);

echo "Hasil pencarian 'jakarta pusat':\n";
foreach ($results2 as $city) {
    echo "ID: " . $city['id'] . "\n";
    echo "Label: " . ($city['label'] ?? 'N/A') . "\n";
    echo "City: " . ($city['city_name'] ?? 'N/A') . "\n";
    echo "District: " . ($city['district_name'] ?? 'N/A') . "\n";
    echo "Subdistrict: " . ($city['subdistrict_name'] ?? 'N/A') . "\n";
    echo "Province: " . ($city['province_name'] ?? 'N/A') . "\n";
    echo "Zip: " . ($city['zip_code'] ?? 'N/A') . "\n";
    echo "---\n";
}
