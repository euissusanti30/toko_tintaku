<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.api_key');
        $this->baseUrl = config('services.rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        
        // Demo mode - return mock data if no API key or if explicitly set to demo mode
        $this->demoMode = empty($this->apiKey) || config('services.rajaongkir.demo_mode', false);
    }

    /**
     * Get provinces
     */
    public function getProvinces()
    {
        if ($this->demoMode) {
            return $this->getMockProvinces();
        }

        return Cache::remember('rajaongkir_provinces', 3600, function () {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->timeout(10)->get($this->baseUrl . '/province');

                if ($response->successful()) {
                    return $response->json()['rajaongkir']['results'];
                }
            } catch (\Exception $e) {
                \Log::warning('RajaOngkir API failed, falling back to demo mode: ' . $e->getMessage());
                return $this->getMockProvinces();
            }

            return [];
        });
    }

    /**
     * Get cities by province
     */
    public function getCities($provinceId = null)
    {
        if ($this->demoMode) {
            return $this->getMockCities($provinceId);
        }

        $cacheKey = $provinceId ? "rajaongkir_cities_{$provinceId}" : 'rajaongkir_cities';
        
        return Cache::remember($cacheKey, 3600, function () use ($provinceId) {
            try {
                $url = $this->baseUrl . '/city';
                if ($provinceId) {
                    $url .= "?province={$provinceId}";
                }

                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->timeout(10)->get($url);

                if ($response->successful()) {
                    return $response->json()['rajaongkir']['results'];
                }
            } catch (\Exception $e) {
                \Log::warning('RajaOngkir API failed, falling back to demo mode: ' . $e->getMessage());
                return $this->getMockCities($provinceId);
            }

            return [];
        });
    }

    /**
     * Calculate shipping cost
     */
    public function getShippingCost($origin, $destination, $weight, $courier = 'jne')
    {
        if ($this->demoMode) {
            return $this->getMockShippingCost($courier, $weight);
        }

        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->post($this->baseUrl . '/cost', [
            'origin' => $origin,
            'originType' => 'city',
            'destination' => $destination,
            'destinationType' => 'city',
            'weight' => $weight,
            'courier' => $courier
        ]);

        if ($response->successful()) {
            return $response->json()['rajaongkir']['results'];
        }

        return null;
    }

    /**
     * Mock data methods for demo mode
     */
    private function getMockProvinces()
    {
        return [
            ['province_id' => 1, 'province' => 'Bali'],
            ['province_id' => 2, 'province' => 'Jakarta'],
            ['province_id' => 3, 'province' => 'Jawa Barat'],
            ['province_id' => 4, 'province' => 'Jawa Tengah'],
            ['province_id' => 5, 'province' => 'Jawa Timur'],
            ['province_id' => 6, 'province' => 'Yogyakarta'],
            ['province_id' => 7, 'province' => 'Sumatera Utara'],
            ['province_id' => 8, 'province' => 'Sumatera Barat'],
            ['province_id' => 9, 'province' => 'Sumatera Selatan'],
            ['province_id' => 10, 'province' => 'Kalimantan'],
        ];
    }

    private function getMockCities($provinceId)
    {
        $allCities = [
            ['city_id' => 101, 'province_id' => 1, 'city_name' => 'Denpasar'], // Bali
            ['city_id' => 102, 'province_id' => 1, 'city_name' => 'Badung'],
            ['city_id' => 103, 'province_id' => 1, 'city_name' => 'Gianyar'],
            ['city_id' => 152, 'province_id' => 2, 'city_name' => 'Jakarta Pusat'], // Jakarta
            ['city_id' => 153, 'province_id' => 2, 'city_name' => 'Jakarta Utara'],
            ['city_id' => 154, 'province_id' => 2, 'city_name' => 'Jakarta Selatan'],
            ['city_id' => 155, 'province_id' => 2, 'city_name' => 'Jakarta Timur'],
            ['city_id' => 156, 'province_id' => 2, 'city_name' => 'Jakarta Barat'],
            ['city_id' => 201, 'province_id' => 3, 'city_name' => 'Bandung'], // Jawa Barat
            ['city_id' => 202, 'province_id' => 3, 'city_name' => 'Bogor'],
            ['city_id' => 203, 'province_id' => 3, 'city_name' => 'Bekasi'],
            ['city_id' => 204, 'province_id' => 3, 'city_name' => 'Depok'],
            ['city_id' => 301, 'province_id' => 4, 'city_name' => 'Semarang'], // Jawa Tengah
            ['city_id' => 302, 'province_id' => 4, 'city_name' => 'Solo'],
            ['city_id' => 303, 'province_id' => 4, 'city_name' => 'Yogyakarta'],
            ['city_id' => 401, 'province_id' => 5, 'city_name' => 'Surabaya'], // Jawa Timur
            ['city_id' => 402, 'province_id' => 5, 'city_name' => 'Malang'],
            ['city_id' => 403, 'province_id' => 5, 'city_name' => 'Kediri'],
        ];

        if ($provinceId === null) {
            return $allCities;
        }

        return array_filter($allCities, function($city) use ($provinceId) {
            return $city['province_id'] == $provinceId;
        });
    }

    private function getMockShippingCost($courier, $weight)
    {
        $baseCost = [
            'jne' => [
                [
                    'service' => 'OKE',
                    'description' => 'Ongkos Kirim Ekonomis',
                    'cost' => [
                        [
                            'value' => max(15000, $weight * 0.1),
                            'etd' => '2-3'
                        ]
                    ]
                ],
                [
                    'service' => 'REG',
                    'description' => 'Layanan Reguler',
                    'cost' => [
                        [
                            'value' => max(20000, $weight * 0.15),
                            'etd' => '1-2'
                        ]
                    ]
                ],
                [
                    'service' => 'YES',
                    'description' => 'Yakin Esok Sampai',
                    'cost' => [
                        [
                            'value' => max(30000, $weight * 0.2),
                            'etd' => '1'
                        ]
                    ]
                ]
            ],
            'tiki' => [
                [
                    'service' => 'REG',
                    'description' => 'Layanan Reguler',
                    'cost' => [
                        [
                            'value' => max(18000, $weight * 0.12),
                            'etd' => '2-3'
                        ]
                    ]
                ],
                [
                    'service' => 'ONS',
                    'description' => 'Over Night Service',
                    'cost' => [
                        [
                            'value' => max(25000, $weight * 0.18),
                            'etd' => '1'
                        ]
                    ]
                ]
            ],
            'pos' => [
                [
                    'service' => 'KILAT',
                    'description' => 'Paket Kilat',
                    'cost' => [
                        [
                            'value' => max(12000, $weight * 0.08),
                            'etd' => '3-4'
                        ]
                    ]
                ],
                [
                    'service' => 'EXPRESS',
                    'description' => 'Express',
                    'cost' => [
                        [
                            'value' => max(22000, $weight * 0.14),
                            'etd' => '1-2'
                        ]
                    ]
                ]
            ]
        ];

        return [
            [
                'code' => strtoupper($courier),
                'name' => strtoupper($courier),
                'costs' => $baseCost[$courier] ?? []
            ]
        ];
    }

    /**
     * Get all available couriers
     */
    public function getAvailableCouriers()
    {
        return [
            'jne' => 'JNE',
            'tiki' => 'TIKI',
            'pos' => 'POS Indonesia'
        ];
    }

    /**
     * Format cost for display
     */
    public function formatCost($cost)
    {
        return 'Rp ' . number_format($cost, 0, ',', '.');
    }

    /**
     * Get city name by ID
     */
    public function getCityName($cityId)
    {
        $cities = $this->getCities();
        foreach ($cities as $city) {
            if ($city['city_id'] == $cityId) {
                return $city['city_name'];
            }
        }
        return null;
    }

    /**
     * Get province name by ID
     */
    public function getProvinceName($provinceId)
    {
        $provinces = $this->getProvinces();
        foreach ($provinces as $province) {
            if ($province['province_id'] == $provinceId) {
                return $province['province'];
            }
        }
        return null;
    }
}
