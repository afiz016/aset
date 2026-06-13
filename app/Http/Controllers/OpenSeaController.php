<?php
namespace App\Http\Controllers;

use App\Services\MarketplaceApiService;
use Illuminate\Http\Request;

class OpenSeaController extends Controller
{
    /**
     * Fetch NFT data dari OpenSea dan simpan ke database
     * Route: GET /fetch-opensea/{slug}
     */
    public function fetchOpenSeaData($slug)
    {
        $apiData = MarketplaceApiService::fetchOpenSeaData($slug);
        $result = MarketplaceApiService::saveAsetFromAPI($apiData);

        return response()->json($result);
    }

    /**
     * Fetch Steam Market item dan simpan ke database
     * Route: GET /fetch-steam/{appId}/{itemName}
     */
    public function fetchSteamData($appId, $itemName)
    {
        $apiData = MarketplaceApiService::fetchSteamMarketData($appId, $itemName);
        $result = MarketplaceApiService::saveAsetFromAPI($apiData);

        return response()->json($result);
    }

    /**
     * Fetch Blur NFT data dan simpan ke database
     * Route: GET /fetch-blur/{contractAddress}/{tokenId}
     */
    public function fetchBlurData($contractAddress, $tokenId)
    {
        $apiData = MarketplaceApiService::fetchBlurData($contractAddress, $tokenId);
        $result = MarketplaceApiService::saveAsetFromAPI($apiData);

        return response()->json($result);
    }

    /**
     * Fetch Magic Eden (Solana) NFT data dan simpan ke database
     * Route: GET /fetch-magiceden/{mint}
     */
    public function fetchMagicEdenData($mint)
    {
        $apiData = MarketplaceApiService::fetchMagicEdenData($mint);
        $result = MarketplaceApiService::saveAsetFromAPI($apiData);

        return response()->json($result);
    }

    /**
     * Batch fetch dari multiple platforms
     * POST /fetch-batch
     * Body: { platforms: [{ platform: 'opensea', params: {...} }, ...] }
     */
    public function fetchBatch(Request $request)
    {
        $platforms = $request->input('platforms', []);
        $results = [];

        foreach ($platforms as $item) {
            $platform = $item['platform'] ?? null;
            $params = $item['params'] ?? [];

            switch ($platform) {
                case 'opensea':
                    $apiData = MarketplaceApiService::fetchOpenSeaData($params['slug']);
                    break;
                case 'steam':
                    $apiData = MarketplaceApiService::fetchSteamMarketData(
                        $params['app_id'],
                        $params['item_name']
                    );
                    break;
                case 'blur':
                    $apiData = MarketplaceApiService::fetchBlurData(
                        $params['contract_address'],
                        $params['token_id']
                    );
                    break;
                case 'magiceden':
                    $apiData = MarketplaceApiService::fetchMagicEdenData($params['mint']);
                    break;
                default:
                    $apiData = ['error' => "Platform '{$platform}' tidak didukung"];
            }

            $results[] = MarketplaceApiService::saveAsetFromAPI($apiData);
        }

        return response()->json([
            'status' => 'completed',
            'total_processed' => count($results),
            'results' => $results
        ]);
    }
}