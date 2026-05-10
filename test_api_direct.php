<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

echo "=== TEST DIRECT API CALL ===\n\n";

$apiKey = env('RAJAONGKIR_API_KEY');
$baseUrl = 'https://api.rajaongkir.com/starter';

echo "API Key: " . $apiKey . "\n";
echo "Base URL: " . $baseUrl . "\n\n";

// Test direct API call
echo "1. Test Direct API Call - Get Provinces:\n";
try {
    $response = Http::withHeaders([
        'key' => $apiKey
    ])->timeout(10)->get($baseUrl . '/province');
    
    echo "   Status Code: " . $response->status() . "\n";
    echo "   Successful: " . ($response->successful() ? 'YES' : 'NO') . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        echo "   Response Structure: " . json_encode(array_keys($data)) . "\n";
        if (isset($data['rajaongkir']['results'])) {
            echo "   Provinces Count: " . count($data['rajaongkir']['results']) . "\n";
            echo "   First Province: " . $data['rajaongkir']['results'][0]['province'] . "\n";
        }
    } else {
        echo "   Error Response: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   Exception: " . $e->getMessage() . "\n";
}

echo "\n2. Test Direct API Call - Get Cities:\n";
try {
    $response = Http::withHeaders([
        'key' => $apiKey
    ])->timeout(10)->get($baseUrl . '/city');
    
    echo "   Status Code: " . $response->status() . "\n";
    echo "   Successful: " . ($response->successful() ? 'YES' : 'NO') . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        if (isset($data['rajaongkir']['results'])) {
            echo "   Cities Count: " . count($data['rajaongkir']['results']) . "\n";
            echo "   First City: " . $data['rajaongkir']['results'][0]['city_name'] . "\n";
        }
    } else {
        echo "   Error Response: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   Exception: " . $e->getMessage() . "\n";
}

echo "\n=== SELESAI ===\n";
