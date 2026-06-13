# API Data Retrieval Guide - SPK Aset Digital

## 📋 Overview
Sistem pengambilan data aset digital dari berbagai marketplace (OpenSea, Steam Market, Blur, Magic Eden) untuk penilaian menggunakan TOPSIS (Technique for Order Preference by Similarity to Ideal Solution).

---

## 🎯 5 Kriteria Utama yang Diambil dari API

| # | Kriteria | Tipe | Sumber Data | Contoh |
|---|----------|------|-------------|---------|
| 1 | **Harga Beli Saat Ini** | Cost (Minimize) | Floor Price dari marketplace | $12.45 |
| 2 | **Volume Transaksi 24 Jam** | Benefit (Maximize) | Trading volume | 250 ETH |
| 3 | **Tingkat Kelangkaan / Rarity** | Benefit (Maximize) | Metadata / Traits | 1-5 scale |
| 4 | **Market Sentiment** | Benefit (Maximize) | Twitter/Community | 1-5 Likert |
| 5 | **Tingkat Likuiditas** | Benefit (Maximize) | Listed items / Holders | Count |

---

## 🔧 Setup & Konfigurasi

### 1. Install Dependencies
```bash
composer require guzzlehttp/guzzle
```

### 2. Setup Environment Variables
Copy dari `.env.example` dan isi API keys:

```bash
cp .env.example .env
```

Edit `.env`:
```env
# OpenSea NFT Marketplace
OPENSEA_API_KEY=your_opensea_api_key_here
OPENSEA_BASE_URL=https://api.opensea.io/v2

# Blur NFT Marketplace
BLUR_API_KEY=your_blur_api_key_here

# Twitter API (untuk sentiment analysis)
TWITTER_BEARER_TOKEN=your_bearer_token_here

# Steam tidak butuh API key (public data)
```

### 3. Daftar API Keys

#### **OpenSea** (NFT Marketplace)
- Website: https://opensea.io/
- Doc: https://docs.opensea.io/
- Langkah:
  1. Buat akun di OpenSea
  2. Go to: https://docs.opensea.io/reference/api-keys
  3. Request API Key
  4. Copy ke `.env`

#### **Blur** (NFT Marketplace)
- Website: https://blur.io/
- Doc: https://blur.io/api
- Langkah:
  1. Buat akun Blur
  2. Pergi ke Settings → API
  3. Generate API Key
  4. Copy ke `.env`

#### **Magic Eden** (Solana NFT)
- Website: https://magiceden.io/
- Doc: https://docs.magiceden.io/
- Public API (no key needed)

#### **Steam Market** (Game Skins)
- Website: https://steamcommunity.com/market/
- Public API (no authentication needed)

#### **Twitter API** (Sentiment)
- Website: https://developer.twitter.com/
- Doc: https://developer.twitter.com/en/docs
- Untuk sentiment analysis tentang collection

---

## 💻 Cara Penggunaan

### **Opsi 1: Via HTTP Requests**

#### A. Fetch OpenSea NFT Data
```bash
curl "http://localhost:8000/fetch-opensea/boredapeyachtclub"
```

Response:
```json
{
  "success": true,
  "message": "Data boredapeyachtclub dari OpenSea berhasil disimpan",
  "aset_id": 1,
  "data": {
    "platform": "OpenSea",
    "item_name": "boredapeyachtclub",
    "harga_beli": 45.5,
    "volume_24h": 250,
    "rarity": 3,
    "sentiment": 4,
    "liquidity": 120,
    "url": "https://opensea.io/collection/boredapeyachtclub",
    "raw_data": {...}
  }
}
```

#### B. Fetch Steam Market Item
```bash
curl "http://localhost:8000/fetch-steam/730/AK-47%20%7C%20Phantom%20Disruptor"
```
- **730** = CS:GO App ID
- Item names harus URL encoded

#### C. Fetch Blur Collection
```bash
curl "http://localhost:8000/fetch-blur/0x2953399124f0cbb46d2cbacd8a89cf0599974963/123"
```
- Contract Address: Ethereum address koleksi
- Token ID: ID spesifik NFT

#### D. Fetch Magic Eden (Solana)
```bash
curl "http://localhost:8000/fetch-magiceden/So1ipWXNUWYP8pNQrQixCoWVj2p6Z7Lne8fF69YYhYs"
```

#### E. Batch Fetch Multiple Items
```bash
curl -X POST "http://localhost:8000/fetch-batch" \
  -H "Content-Type: application/json" \
  -d '{
    "platforms": [
      {
        "platform": "opensea",
        "params": {"slug": "boredapeyachtclub"}
      },
      {
        "platform": "steam",
        "params": {"app_id": 730, "item_name": "AK-47 | Dragon Lore"}
      },
      {
        "platform": "blur",
        "params": {"contract_address": "0x...", "token_id": "123"}
      }
    ]
  }'
```

---

### **Opsi 2: Via Laravel Controller (Programmatic)**

Di dalam Controller atau Command:

```php
<?php

namespace App\Http\Controllers;

use App\Services\MarketplaceApiService;

class MyController extends Controller
{
    public function demo()
    {
        // Fetch OpenSea
        $openSeaData = MarketplaceApiService::fetchOpenSeaData('boredapeyachtclub');
        
        // Fetch & Save langsung ke database
        $result = MarketplaceApiService::saveAsetFromAPI($openSeaData);
        
        return response()->json($result);
    }
}
```

---

### **Opsi 3: Via Artisan Command**

Buat command untuk scheduled fetching:

```bash
php artisan make:command FetchMarketplaceData
```

```php
<?php

namespace App\Console\Commands;

use App\Services\MarketplaceApiService;
use Illuminate\Console\Command;

class FetchMarketplaceData extends Command
{
    protected $signature = 'marketplace:fetch {platform} {params*}';
    protected $description = 'Fetch data dari marketplace';

    public function handle()
    {
        $platform = $this->argument('platform');
        $params = $this->argument('params');

        switch ($platform) {
            case 'opensea':
                $data = MarketplaceApiService::fetchOpenSeaData($params[0]);
                break;
            case 'steam':
                $data = MarketplaceApiService::fetchSteamMarketData($params[0], $params[1]);
                break;
            case 'blur':
                $data = MarketplaceApiService::fetchBlurData($params[0], $params[1]);
                break;
            case 'magiceden':
                $data = MarketplaceApiService::fetchMagicEdenData($params[0]);
                break;
            default:
                $this->error("Platform tidak dikenali");
                return;
        }

        $result = MarketplaceApiService::saveAsetFromAPI($data);
        
        if ($result['success']) {
            $this->info("✓ {$result['message']}");
        } else {
            $this->error("✗ {$result['message']}");
        }
    }
}
```

Gunakan:
```bash
php artisan marketplace:fetch opensea boredapeyachtclub
php artisan marketplace:fetch steam 730 "AK-47 | Dragon Lore"
```

---

## 📊 Database Schema

Data yang disimpan:

### **aset_digitals** table
```
id: 1
nama_aset: "Boring Ape Yacht Club #123"
jenis_aset: "OpenSea"
platform_url: "https://opensea.io/collection/boredapeyachtclub"
raw_data: {JSON dari API response}
```

### **penilaians** table
```
id: 1, aset_digital_id: 1, kriteria_id: 1, nilai: 45.5      // Harga Beli
id: 2, aset_digital_id: 1, kriteria_id: 2, nilai: 250       // Volume 24h
id: 3, aset_digital_id: 1, kriteria_id: 3, nilai: 3         // Rarity
id: 4, aset_digital_id: 1, kriteria_id: 4, nilai: 4         // Sentiment
id: 5, aset_digital_id: 1, kriteria_id: 5, nilai: 120       // Liquidity
```

---

## 🔄 Data Flow Diagram

```
┌─────────────────┐
│  API Marketplace│
│ (OpenSea, etc)  │
└────────┬────────┘
         │ HTTP Request
         ▼
┌─────────────────────┐
│ MarketplaceApiService
│ - fetchOpenSeaData()
│ - fetchSteamData()
│ - fetchBlurData()
│ - etc
└────────┬────────────┘
         │ Processed Data
         ▼
┌──────────────────────┐
│ saveAsetFromAPI()    │
│ - Create AsetDigital
│ - Auto-map to Kriteria
│ - Save Penilaian
└────────┬─────────────┘
         │ Stored in DB
         ▼
┌──────────────────────┐
│ TOPSIS Calculation   │
│ - Normalisasi        │
│ - Weighted Score     │
│ - Ranking            │
└──────────────────────┘
```

---

## 🚀 Best Practices

### 1. **Error Handling**
```php
$data = MarketplaceApiService::fetchOpenSeaData($slug);

if (isset($data['error'])) {
    // Handle error
    Log::error('API Error: ' . $data['error']);
    return response()->json($data, 400);
}
```

### 2. **Rate Limiting**
```php
// Tambah cache untuk menghindari request berulang dalam 1 jam
$data = cache()->remember("opensea_{$slug}", 3600, function() use ($slug) {
    return MarketplaceApiService::fetchOpenSeaData($slug);
});
```

### 3. **Scheduled Updates**
Di `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Update setiap 6 jam
    $schedule->command('marketplace:fetch opensea boredapeyachtclub')
        ->everyFourHours();
}
```

### 4. **Validation**
```php
// Validate data before save
if ($apiData['harga_beli'] < 0 || $apiData['volume_24h'] < 0) {
    return response()->json(['error' => 'Invalid data'], 422);
}
```

---

## 📝 Example Workflows

### Workflow 1: Manual Single Fetch
```
1. User akses: http://localhost:8000/fetch-opensea/boredapeyachtclub
2. Controller call: MarketplaceApiService::fetchOpenSeaData()
3. Service hit OpenSea API
4. Data parsed & mapped to 5 kriteria
5. Data saved ke database
6. Response dengan status sukses
```

### Workflow 2: Batch Import
```
1. User POST ke /fetch-batch dengan 10 items
2. Loop through each platform
3. Fetch data dari masing-masing API
4. Save semua ke database
5. Return summary results
```

### Workflow 3: Scheduled Refresh
```
1. Setiap hari jam 2 pagi jalankan scheduler
2. Command fetch data untuk semua existing items
3. Update nilai kriteria di database
4. TOPSIS re-calculate dengan data terbaru
```

---

## ⚙️ Troubleshooting

| Masalah | Solusi |
|---------|--------|
| 401 Unauthorized dari OpenSea | Check API Key di `.env`, pastikan valid & punya rate limit |
| Timeout saat fetch Steam | Steam punya rate limit ketat, gunakan cache/backoff |
| Data tidak tersimpan | Check model fillable, pastikan Kriteria sudah ada di DB |
| Sentiment selalu random | TODO: Implement Twitter sentiment analysis |

---

## 🎓 Referensi

- [OpenSea API Docs](https://docs.opensea.io/)
- [Steam Community Market](https://steamcommunity.com/market/)
- [Blur Marketplace](https://blur.io/api)
- [Magic Eden API](https://docs.magiceden.io/)
- [Laravel HTTP Client](https://laravel.com/docs/http-client)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
