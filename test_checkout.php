<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test the checkout data
use App\Services\RajaOngkirService;

try {
    $service = new RajaOngkirService();
    
    echo "Testing checkout data...\n";
    
    // Test getting provinces
    $provinces = $service->getProvinces();
    echo "Found " . count($provinces) . " provinces\n";
    
    // Test getting all cities
    $cities = $service->getCities();
    echo "Found " . count($cities) . " total cities\n";
    
    // Group cities by province
    $citiesByProvince = [];
    foreach ($cities as $city) {
        $provinceId = $city['province_id'];
        if (!isset($citiesByProvince[$provinceId])) {
            $citiesByProvince[$provinceId] = [];
        }
        $citiesByProvince[$provinceId][] = $city['city_name'];
    }
    
    echo "\nCities by province:\n";
    foreach ($citiesByProvince as $provinceId => $provinceCities) {
        $provinceName = '';
        foreach ($provinces as $province) {
            if ($province['province_id'] == $provinceId) {
                $provinceName = $province['province'];
                break;
            }
        }
        echo "Province $provinceId ($provinceName): " . count($provinceCities) . " cities\n";
        foreach ($provinceCities as $city) {
            echo "  - $city\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
