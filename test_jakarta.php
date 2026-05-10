<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\RajaOngkirService;

echo "=== CARI ID JAKARTA ===\n\n";

$rajaOngkir = new RajaOngkirService();

// Search for jakarta
$results = $rajaOngkir->searchCities('jakarta', 50);

echo "Hasil pencarian 'jakarta' (" . count($results) . " results):\n\n";

foreach ($results as $i => $city) {
    $label = $city['label'] ?? ($city['subdistrict_name'] . ', ' . $city['district_name'] . ', ' . $city['city_name']);
    echo ($i+1) . ". ID: " . $city['id'] . " | " . $label . "\n";
}
