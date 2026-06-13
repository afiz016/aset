<?php

namespace App\Console\Commands;

use App\Services\MarketplaceApiService;
use Illuminate\Console\Command;

class FetchMarketplaceData extends Command
{
    protected $signature = 'marketplace:fetch {platform : opensea|steam|blur|magiceden|batch} {--params=}';
    protected $description = 'Fetch aset digital data dari berbagai marketplace API';

    public function handle()
    {
        $platform = $this->argument('platform');
        $params = $this->option('params');

        $this->info("🔄 Fetching data dari {$platform}...");

        try {
            $data = null;

            switch ($platform) {
                case 'opensea':
                    if (!$params) {
                        $this->error('Parameter slug dibutuhkan: --params="boredapeyachtclub"');
                        return 1;
                    }
                    $this->info("OpenSea slug: {$params}");
                    $data = MarketplaceApiService::fetchOpenSeaData($params);
                    break;

                case 'steam':
                    if (!$params) {
                        $this->error('Parameter dibutuhkan: --params="730,AK-47 | Dragon Lore"');
                        return 1;
                    }
                    [$appId, $itemName] = explode(',', $params);
                    $this->info("Steam App ID: {$appId}, Item: {$itemName}");
                    $data = MarketplaceApiService::fetchSteamMarketData($appId, trim($itemName));
                    break;

                case 'blur':
                    if (!$params) {
                        $this->error('Parameter dibutuhkan: --params="0x...,123"');
                        return 1;
                    }
                    [$contract, $tokenId] = explode(',', $params);
                    $this->info("Contract: {$contract}, Token ID: {$tokenId}");
                    $data = MarketplaceApiService::fetchBlurData($contract, $tokenId);
                    break;

                case 'magiceden':
                    if (!$params) {
                        $this->error('Parameter mint dibutuhkan: --params="So1ipWXNUWYP8..."');
                        return 1;
                    }
                    $this->info("Magic Eden mint: {$params}");
                    $data = MarketplaceApiService::fetchMagicEdenData($params);
                    break;

                case 'batch':
                    return $this->batchFetch();

                default:
                    $this->error("Platform '{$platform}' tidak dikenali");
                    return 1;
            }

            if (isset($data['error'])) {
                $this->error("❌ API Error: {$data['error']}");
                return 1;
            }

            // Save to database
            $result = MarketplaceApiService::saveAsetFromAPI($data);

            if ($result['success']) {
                $this->info("✅ {$result['message']}");
                $this->table(
                    ['Kriteria', 'Nilai'],
                    [
                        ['Harga Beli', number_format($data['harga_beli'], 2)],
                        ['Volume 24h', number_format($data['volume_24h'], 2)],
                        ['Rarity', $data['rarity']],
                        ['Sentiment', $data['sentiment']],
                        ['Liquidity', number_format($data['liquidity'], 2)]
                    ]
                );
                return 0;
            } else {
                $this->error("❌ {$result['message']}");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Exception: {$e->getMessage()}");
            return 1;
        }
    }

    private function batchFetch()
    {
        $this->info("🔄 Starting batch fetch...");
        
        $items = [
            ['platform' => 'opensea', 'params' => 'boredapeyachtclub'],
            ['platform' => 'opensea', 'params' => 'cryptopunks'],
        ];

        $results = [];
        $successCount = 0;
        $failCount = 0;

        foreach ($items as $item) {
            $this->line("  Processing: {$item['platform']} - {$item['params']}");

            $data = null;
            if ($item['platform'] === 'opensea') {
                $data = MarketplaceApiService::fetchOpenSeaData($item['params']);
            }

            if ($data && !isset($data['error'])) {
                $result = MarketplaceApiService::saveAsetFromAPI($data);
                if ($result['success']) {
                    $this->line("    ✅ Success");
                    $successCount++;
                } else {
                    $this->line("    ❌ {$result['message']}");
                    $failCount++;
                }
            } else {
                $this->line("    ❌ Failed to fetch");
                $failCount++;
            }

            sleep(2);
        }

        $this->info("\n📊 Batch Summary:");
        $this->table(
            ['Status', 'Count'],
            [
                ['✅ Success', $successCount],
                ['❌ Failed', $failCount],
                ['Total', count($items)]
            ]
        );

        return $failCount === 0 ? 0 : 1;
    }
}
