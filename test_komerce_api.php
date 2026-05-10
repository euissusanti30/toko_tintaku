<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

echo "=== TEST RAJA ONGKIR KOMERCE API ===\n\n";

$apiKey = 'PIrhNcXKa501bd9b725769dfNSpPdmOG';
$baseUrl = 'https://rajaongkir.komerce.id/api/v1';

echo "API Key: " . $apiKey . "\n";
echo "Base URL: " . $baseUrl . "\n\n";

// Test 1: Get Provinces
echo "1. Test Get Provinces:\n";
try {
    $response = Http::withHeaders([
        'key' => $apiKey
    ])->timeout(10)->get($baseUrl . '/destination/province');

    echo "   Status: " . $response->status() . "\n";
    if ($response->successful()) {
        $data = $response->json();
        echo "   Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "   Error: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Search Cities by Province name
echo "2. Test Search Cities (search: 'bandung'):\n";
try {
    $response = Http::withHeaders([
        'key' => $apiKey
    ])->timeout(10)->get($baseUrl . '/destination/domestic-destination?search=bandung&limit=10');

    echo "   Status: " . $response->status() . "\n";
    if ($response->successful()) {
        $data = $response->json();
        echo "   Response Structure: " . json_encode(array_keys($data)) . "\n";
        if (isset($data['data'])) {
            echo "   Cities Count: " . count($data['data']) . "\n";
            if (count($data['data']) > 0) {
                echo "   First City:\n";
                echo "   " . json_encode($data['data'][0], JSON_PRETTY_PRINT) . "\n";
            }
        }
    } else {
        echo "   Error: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Search Cities by Province 'jawa barat'
echo "3. Test Search Cities (search: 'jawa barat'):\n";
try {
    $response = Http::withHeaders([
        'key' => $apiKey
    ])->timeout(10)->get($baseUrl . '/destination/domestic-destination?search=jawa%20barat&limit=10');

    echo "   Status: " . $response->status() . "\n";
    if ($response->successful()) {
        $data = $response->json();
        if (isset($data['data'])) {
            echo "   Cities Count: " . count($data['data']) . "\n";
        }
    } else {
        echo "   Error: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Calculate Shipping Cost
echo "4. Test Calculate Shipping Cost:\n";
try {
    $response = Http::withHeaders([
        'key' => $apiKey,
        'Content-Type' => 'application/x-www-form-urlencoded'
    ])->asForm()->post($baseUrl . '/calculate/district/domestic-cost', [
        'origin' => 4816,  // Bandung
        'destination' => 4816, // Same as origin for test
        'weight' => 1000,
        'courier' => 'jne',
        'price' => 'lowest'
    ]);

    echo "   Status: " . $response->status() . "\n";
    if ($response->successful()) {
        $data = $response->json();
        echo "   Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "   Error: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   Exception: " . $e->getMessage() . "\n";
}

echo "\n=== SELESAI ===\n";
