<?php

echo "=== DEBUG ENVIRONMENT ===\n\n";

// Load .env file manually
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    echo "File .env ditemukan\n";
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $lineNum => $line) {
        if (strpos($line, 'RAJAONGKIR_API_KEY') !== false) {
            echo "Baris " . ($lineNum + 1) . ": " . $line . "\n";
            echo "Panjang: " . strlen($line) . " karakter\n";
            echo "Hex: " . bin2hex($line) . "\n";
            
            // Extract value after =
            if (strpos($line, '=') !== false) {
                $value = substr($line, strpos($line, '=') + 1);
                echo "Value: '" . $value . "'\n";
                echo "Value length: " . strlen($value) . "\n";
                echo "Value hex: " . bin2hex($value) . "\n";
            }
        }
    }
} else {
    echo "File .env tidak ditemukan\n";
}

echo "\n=== TEST ENV LOADING ===\n";

// Test using Laravel's env helper
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$apiKey = env('RAJAONGKIR_API_KEY');
echo "env('RAJAONGKIR_API_KEY'): " . ($apiKey ?: 'NULL') . "\n";
echo "Length: " . strlen($apiKey ?: '') . "\n";

$configKey = config('services.rajaongkir.api_key');
echo "config('services.rajaongkir.api_key'): " . ($configKey ?: 'NULL') . "\n";
echo "Length: " . strlen($configKey ?: '') . "\n";

echo "\n=== SELESAI ===\n";
