<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\RajaOngkirService;

echo "=== CARI ID JAKARTA PUSAT ===\n\n";

$rajaOngkir = new RajaOngkirService();

// Search for pusat
$results = $rajaOngkir->searchCities('pusat', 50);

echo "Hasil pencarian 'pusat' (" . count($results) . " results):\n\n";

foreach ($results as $i => $city) {
    $label = $city['label'] ?? ($city['subdistrict_name'] . ', ' . $city['district_name'] . ', ' . $city['city_name']);
    echo ($i+1) . ". ID: " . $city['id'] . " | " . $label . "\n";
}

echo "\n\n=== CARI ID DKI ===\n\n";

// Search for dki
$results2 = $rajaOngkir->searchCities('dki', 50);

echo "Hasil pencarian 'dki' (" . count($results2) . " results):\n\n";

foreach ($results2 as $i => $city) {
    $label = $city['label'] ?? ($city['subdistrict_name'] . ', ' . $city['district_name'] . ', ' . $city['city_name']);
    echo ($i+1) . ". ID: " . $city['id'] . " | " . $label . "\n";
}
