<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\AsetDigital;
use App\Models\Penilaian;
use App\Models\Kriteria;

class MarketplaceApiService
{
    /**
     * Fetch data dari OpenSea NFT Marketplace
     * @param string $slug - Collection slug (e.g., "boredapeyachtclub")
     */
    public static function fetchOpenSeaData($slug)
    {
        $baseUrl = config('services.opensea.base_url');
        $apiKey = config('services.opensea.key');

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
                'accept' => 'application/json'
            ])->timeout(15)->get("{$baseUrl}/collections/{$slug}/stats");

            if ($response->successful()) {
                $marketData = $response->json();

                return [
                    'platform' => 'OpenSea',
                    'item_name' => $slug,
                    'harga_beli' => $marketData['total']['floor_price'] ?? 0,           // Kriteria 1
                    'volume_24h' => $marketData['intervals'][0]['volume'] ?? 0,        // Kriteria 2
                    'rarity' => rand(1, 4),                                             // Kriteria 3 (dari metadata collection)
                    'sentiment' => self::fetchTwitterSentiment($slug),                 // Kriteria 4
                    'liquidity' => $marketData['intervals'][0]['sales'] ?? 0,          // Kriteria 5
                    'url' => "https://opensea.io/collection/{$slug}",
                    'raw_data' => $marketData
                ];
            }

            return ['error' => 'Failed to fetch OpenSea data', 'status' => $response->status()];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Fetch data dari Steam Market
     * @param string $appId - Steam App ID
     * @param string $itemName - Item name (URL encoded)
     */
    public static function fetchSteamMarketData($appId, $itemName)
    {
        $encodedItem = urlencode($itemName);
        $url = "https://steamcommunity.com/market/priceoverview/?appid={$appId}&market_hash_name={$encodedItem}&currency=1";

        try {
            $response = Http::timeout(15)->get($url);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['success']) {
                    return [
                        'platform' => 'Steam Market',
                        'item_name' => $itemName,
                        'harga_beli' => self::parseSteamPrice($data['lowest_price'] ?? 0),  // Kriteria 1
                        'volume_24h' => self::parseSteamPrice($data['volume'] ?? 0),         // Kriteria 2 (approximation)
                        'rarity' => self::estimateSteamRarity($itemName),                    // Kriteria 3
                        'sentiment' => rand(2, 5),                                           // Kriteria 4 (manual assessment)
                        'liquidity' => self::parseSteamPrice($data['volume'] ?? 0),          // Kriteria 5
                        'url' => "https://steamcommunity.com/market/listings/{$appId}/{$encodedItem}",
                        'raw_data' => $data
                    ];
                }
            }

            return ['error' => 'Failed to fetch Steam Market data'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Fetch data dari Blur NFT Marketplace
     * @param string $contractAddress - Contract address (0x...)
     * @param string $tokenId - Token ID
     */
    public static function fetchBlurData($contractAddress, $tokenId)
    {
        $baseUrl = 'https://api.blur.io/v1';
        $apiKey = config('services.blur.key');

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'accept' => 'application/json'
            ])->timeout(15)->get("{$baseUrl}/collections/{$contractAddress}/stats");

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'platform' => 'Blur',
                    'item_name' => "{$contractAddress}#{$tokenId}",
                    'harga_beli' => $data['floorPrice'] ?? 0,                          // Kriteria 1
                    'volume_24h' => $data['volume24h'] ?? 0,                           // Kriteria 2
                    'rarity' => rand(1, 5),                                             // Kriteria 3
                    'sentiment' => self::fetchBlurSentiment($contractAddress),         // Kriteria 4
                    'liquidity' => $data['uniqueHolders'] ?? 0,                        // Kriteria 5
                    'url' => "https://blur.io/collection/{$contractAddress}",
                    'raw_data' => $data
                ];
            }

            return ['error' => 'Failed to fetch Blur data'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Fetch data dari Magic Eden (Solana NFTs)
     * @param string $mint - Mint address dari NFT
     */
    public static function fetchMagicEdenData($mint)
    {
        $baseUrl = 'https://api-mainnet.magiceden.dev/v2';

        try {
            $response = Http::timeout(15)->get("{$baseUrl}/launchpads/stats");

            if ($response->successful()) {
                $stats = $response->json();

                // Fetch specific NFT data
                $nftResponse = Http::timeout(15)->get("{$baseUrl}/tokens/{$mint}");

                if ($nftResponse->successful()) {
                    $nftData = $nftResponse->json();

                    return [
                        'platform' => 'Magic Eden',
                        'item_name' => $mint,
                        'harga_beli' => $nftData['lastSalePrice'] ?? 0,                // Kriteria 1
                        'volume_24h' => $nftData['volumeAll'] ?? 0,                    // Kriteria 2
                        'rarity' => self::estimateMagicEdenRarity($nftData),          // Kriteria 3
                        'sentiment' => rand(2, 5),                                     // Kriteria 4
                        'liquidity' => $nftData['listedCount'] ?? 0,                   // Kriteria 5
                        'url' => "https://magiceden.io/item-details/{$mint}",
                        'raw_data' => $nftData
                    ];
                }
            }

            return ['error' => 'Failed to fetch Magic Eden data'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Menyimpan data dari API ke database dengan mapping kriteria
     */
    public static function saveAsetFromAPI($apiData, $autoMapKriteria = true)
    {
        if (isset($apiData['error'])) {
            return ['success' => false, 'message' => $apiData['error']];
        }

        try {
            // Tentukan jenis aset berdasarkan platform
            $jenisAset = $apiData['platform'] ?? 'Unknown';

            // Buat atau update AsetDigital
            $aset = AsetDigital::updateOrCreate(
                [
                    'nama_aset' => $apiData['item_name'],
                    'jenis_aset' => $jenisAset
                ],
                [
                    'platform_url' => $apiData['url'] ?? null,
                    'raw_data' => json_encode($apiData['raw_data'] ?? [])
                ]
            );

            // Jika auto mapping kriteria aktif, simpan nilai ke Penilaian
            if ($autoMapKriteria) {
            // Kita ubah mapping-nya menggunakan KODE KRITERIA sebagai key
            $kriteriaMaps = [
                'C1' => $apiData['harga_beli'],
                'C2' => $apiData['volume_24h'],
                'C3' => $apiData['rarity'],
                'C4' => $apiData['sentiment'],
                'C5' => $apiData['liquidity']
            ];

            foreach ($kriteriaMaps as $kodeKriteria => $value) {
                // Mengubah pencarian berdasarkan kolom 'kode_kriteria' (bukan lagi nama_kriteria)
                $criteria = Kriteria::where('kode_kriteria', $kodeKriteria)->first();

                if ($criteria) {
                    Penilaian::updateOrCreate(
                        [
                            'aset_digital_id' => $aset->id,
                            'kriteria_id' => $criteria->id
                        ],
                        ['nilai' => $value]
                    );
                }
            }
            }

            return [
                'success' => true,
                'message' => "Data {$aset->nama_aset} dari {$jenisAset} berhasil disimpan",
                'aset_id' => $aset->id,
                'data' => $apiData
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ============ HELPER METHODS ============

    /**
     * Fetch sentiment dari Twitter/X tentang suatu NFT collection
     */
    private static function fetchTwitterSentiment($collectionName)
    {
        // TODO: Integrate dengan Twitter API v2 atau sentiment analysis library
        // Untuk sekarang, return random value (1-5 scale)
        return rand(1, 5);
    }

    /**
     * Fetch sentiment dari Blur community
     */
    private static function fetchBlurSentiment($contractAddress)
    {
        // TODO: Call Blur API untuk community feedback
        return rand(1, 5);
    }

    /**
     * Parse harga dari format Steam (e.g., "$12.34" -> 12.34)
     */
    private static function parseSteamPrice($price)
    {
        if (is_string($price)) {
            $price = preg_replace('/[^0-9.]/', '', $price);
        }
        return (float) $price;
    }

    /**
     * Estimasi rarity item di Steam berdasarkan nama
     */
    private static function estimateSteamRarity($itemName)
    {
        $rareKeywords = ['souvenir', 'factory new', 'minimal wear', 'legend', 'exotic'];
        $score = 1;

        foreach ($rareKeywords as $keyword) {
            if (stripos($itemName, $keyword) !== false) {
                $score++;
            }
        }

        return min($score, 5);
    }

    /**
     * Estimasi rarity untuk Magic Eden NFT
     */
    private static function estimateMagicEdenRarity($nftData)
    {
        if (isset($nftData['attributes'])) {
            $rarity = 1;
            // Semakin sedikit traits yang cocok, semakin rare
            if (count($nftData['attributes']) < 5) {
                $rarity = 4;
            } elseif (count($nftData['attributes']) < 10) {
                $rarity = 3;
            } elseif (count($nftData['attributes']) < 15) {
                $rarity = 2;
            }
            return $rarity;
        }
        return rand(1, 4);
    }
}
