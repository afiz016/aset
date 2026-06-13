# 🏗️ System Architecture - API Data Retrieval

## Overall Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                    USER INTERACTION LAYER                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  Browser        CLI              Postman          Programmatic      │
│  http://...     artisan cmd      requests         $service->call()   │
│                                                                       │
└─────────────────────┬───────────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│              CONTROLLER & ROUTING LAYER                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  routes/web.php                                                      │
│  ├─ GET  /fetch-opensea/{slug}           → OpenSeaController        │
│  ├─ GET  /fetch-steam/{appId}/{item}     → OpenSeaController        │
│  ├─ GET  /fetch-blur/{contract}/{token}  → OpenSeaController        │
│  ├─ GET  /fetch-magiceden/{mint}         → OpenSeaController        │
│  └─ POST /fetch-batch                    → OpenSeaController        │
│                                                                       │
│  app/Http/Controllers/OpenSeaController.php                         │
│  ├─ fetchOpenSeaData($slug)                                         │
│  ├─ fetchSteamData($appId, $itemName)                               │
│  ├─ fetchBlurData($contractAddress, $tokenId)                       │
│  ├─ fetchMagicEdenData($mint)                                       │
│  └─ fetchBatch(Request $request)                                    │
│                                                                       │
└─────────────────────┬───────────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│           SERVICE LAYER (MarketplaceApiService)                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  MarketplaceApiService::                                            │
│  ├─ fetchOpenSeaData()      ──┐                                     │
│  ├─ fetchSteamMarketData()  ──┼──> HTTP Client (Laravel Http)       │
│  ├─ fetchBlurData()         ──┤    + Error handling                 │
│  ├─ fetchMagicEdenData()    ──┘    + Timeout management             │
│  │                                                                    │
│  └─ saveAsetFromAPI()                                               │
│     ├─ Create/Update AsetDigital                                    │
│     └─ Auto-map to Kriteria & Save Penilaian                        │
│                                                                       │
│  Helper Methods:                                                     │
│  ├─ fetchTwitterSentiment()                                         │
│  ├─ fetchBlurSentiment()                                            │
│  ├─ parseSteamPrice()                                               │
│  ├─ estimateSteamRarity()                                           │
│  └─ estimateMagicEdenRarity()                                       │
│                                                                       │
└─────────────────────┬───────────────────────────────────────────────┘
                      │
        ┌─────────────┼─────────────┬──────────────┬─────────────┐
        │             │             │              │             │
        ▼             ▼             ▼              ▼             ▼
    ┌────────┐   ┌────────┐   ┌────────┐    ┌────────┐    ┌────────┐
    │ OpenSea │   │  Steam │   │  Blur  │    │ Magic  │    │Twitter │
    │  API   │   │ Market │   │  API   │    │ Eden   │    │  API   │
    └────────┘   └────────┘   └────────┘    └────────┘    └────────┘
       NFT         Gaming        NFT         Solana      Sentiment
    Marketplace   Marketplace   Marketplace  NFT         Analysis
      (Eth)        (CS:GO)      (Eth)       (SOL)
    
└─ Fetch data matching 5 kriteria ─────────────────────────────────┘

        Data Structure:
        {
          "platform": "OpenSea",
          "harga_beli": 45.5,        → Kriteria 1: Cost (minimize)
          "volume_24h": 250,         → Kriteria 2: Benefit (maximize)
          "rarity": 3,               → Kriteria 3: Benefit (maximize)
          "sentiment": 4,            → Kriteria 4: Benefit (maximize)
          "liquidity": 120           → Kriteria 5: Benefit (maximize)
        }

        │
        ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      DATABASE LAYER (SQLite)                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  aset_digitals                                                       │
│  ├─ id: 1                                                            │
│  ├─ nama_aset: "Bored Ape #123"                                     │
│  ├─ jenis_aset: "OpenSea"                                           │
│  ├─ platform_url: "https://opensea.io/..."                          │
│  └─ raw_data: {JSON response}                                       │
│                                                                       │
│  penilaians (5 rows per aset)                                       │
│  ├─ Row 1: aset_id=1, kriteria_id=1 (Harga), nilai=45.5            │
│  ├─ Row 2: aset_id=1, kriteria_id=2 (Volume), nilai=250            │
│  ├─ Row 3: aset_id=1, kriteria_id=3 (Rarity), nilai=3              │
│  ├─ Row 4: aset_id=1, kriteria_id=4 (Sentiment), nilai=4           │
│  └─ Row 5: aset_id=1, kriteria_id=5 (Liquidity), nilai=120         │
│                                                                       │
│  kriteria                                                            │
│  ├─ id=1: nama="Harga Beli Saat Ini", tipe=cost                    │
│  ├─ id=2: nama="Volume Transaksi 24 Jam", tipe=benefit             │
│  ├─ id=3: nama="Tingkat Kelangkaan", tipe=benefit                  │
│  ├─ id=4: nama="Market Sentiment", tipe=benefit                    │
│  └─ id=5: nama="Tingkat Likuiditas", tipe=benefit                  │
│                                                                       │
└─────────────────────┬───────────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│              NEXT PHASE: TOPSIS CALCULATION                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  With data dari 5 kriteria sudah tersimpan:                         │
│                                                                       │
│  1. Normalisasi nilai kriteria                                      │
│  2. Hitung weighted score                                           │
│  3. Tentukan ideal solution (positive & negative)                   │
│  4. Hitung separation measures                                      │
│  5. Calculate preference value (ranking)                            │
│  6. Sort & display results                                          │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Request Flow Diagram

### Single Item Fetch
```
┌─────────┐
│ Browser │ GET /fetch-opensea/boredapeyachtclub
└────┬────┘
     │
     ▼
┌──────────────────────────────┐
│ OpenSeaController            │
│ - fetchOpenSeaData($slug)    │
└────┬──────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────────────┐
│ MarketplaceApiService                            │
│ - fetchOpenSeaData("boredapeyachtclub")          │
│   ├─ Config API key from .env                    │
│   ├─ Http::get(OPENSEA_BASE_URL/collections/...)│
│   ├─ Parse response → 5 kriteria values         │
│   └─ Return $apiData array                       │
└────┬────────────────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────────────┐
│ MarketplaceApiService::saveAsetFromAPI()         │
│ ├─ updateOrCreate(aset_digitals)                 │
│ ├─ Loop foreach 5 kriteria:                      │
│ │  └─ updateOrCreate(penilaians)                 │
│ └─ Return success response                       │
└────┬────────────────────────────────────────────┘
     │
     ▼
┌─────────────────────────────────┐
│ Database Update                  │
│ ├─ aset_digitals insert/update   │
│ └─ penilaians insert/update (×5) │
└────┬────────────────────────────┘
     │
     ▼
┌─────────────────────────────────────────────────┐
│ Response to User                                │
│ {                                               │
│   "success": true,                              │
│   "message": "Data ... berhasil disimpan",      │
│   "aset_id": 1,                                 │
│   "data": { 5 kriteria values... }              │
│ }                                               │
└─────────────────────────────────────────────────┘
```

### Batch Fetch
```
┌──────────┐
│ Postman  │ POST /fetch-batch { platforms: [...] }
└────┬─────┘
     │
     ▼
┌──────────────────────────────┐
│ OpenSeaController            │
│ - fetchBatch(Request $req)   │
└────┬──────────────────────────┘
     │
     ▼
┌────────────────────────────────────────┐
│ Loop foreach platform in request       │
├────────────────────────────────────────┤
│ Iter 1: opensea/boredapeyachtclub      │
│   └─> MarketplaceApiService::fetch...()│
│       └─> saveAsetFromAPI()            │
│           └─> Result[0]                │
│                                        │
│ Iter 2: opensea/cryptopunks           │
│   └─> MarketplaceApiService::fetch...()│
│       └─> saveAsetFromAPI()            │
│           └─> Result[1]                │
│                                        │
│ Iter 3: steam/730/AK-47 Dragon Lore   │
│   └─> MarketplaceApiService::fetch...()│
│       └─> saveAsetFromAPI()            │
│           └─> Result[2]                │
└────┬───────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────┐
│ Response to User                         │
│ {                                        │
│   "status": "completed",                 │
│   "total_processed": 3,                  │
│   "results": [ {...}, {...}, {...} ]    │
│ }                                        │
└──────────────────────────────────────────┘
```

---

## Environment Configuration

```
.env file contains:
├─ OpenSea
│  ├─ OPENSEA_API_KEY (required)
│  └─ OPENSEA_BASE_URL=https://api.opensea.io/v2
│
├─ Blur
│  └─ BLUR_API_KEY (optional)
│
├─ Twitter/Sentiment
│  └─ TWITTER_BEARER_TOKEN (optional)
│
└─ Steam (no key required - public API)
```

---

## Technology Stack

```
Frontend:
├─ HTTP Client (curl, Postman, Browser)
└─ JSON payload

Backend:
├─ Laravel Framework
├─ PHP 8.x
├─ Eloquent ORM
├─ Laravel Http Client (Guzzle)
└─ SQLite Database

External APIs:
├─ OpenSea API v2
├─ Steam Community Market
├─ Blur API
├─ Magic Eden API
└─ Twitter API (optional)
```

---

## Deployment Considerations

1. **API Keys**: Store safely in .env
2. **Rate Limiting**: Implement backoff strategy
3. **Caching**: Use Redis for frequently fetched items
4. **Scheduled Jobs**: Use Laravel Scheduler for daily refresh
5. **Error Logs**: Monitor for API failures
6. **Database**: Backup penilaians table regularly
