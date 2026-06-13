# 🎯 Pengambilan Data API - Visual Guide

## Cara Pengambilan Data (3 Methods)

```
METHOD 1: BROWSER (Paling Mudah)
════════════════════════════════════════

  1. Buka browser
  2. Paste URL: http://localhost:8000/fetch-opensea/boredapeyachtclub
  3. Enter
  4. JSON response ditampilkan
  5. Data tersimpan di database

  ✅ Paling simple, tidak perlu tools


METHOD 2: COMMAND LINE (Paling Powerful)
════════════════════════════════════════

  1. Buka terminal / PowerShell
  2. cd ke folder project
  3. Jalankan:
     php artisan marketplace:fetch opensea boredapeyachtclub
  4. Output dengan table format
  5. Data tersimpan

  ✅ Bisa batch, bisa schedule


METHOD 3: POSTMAN (Paling Professional)
════════════════════════════════════════

  1. Install Postman
  2. Import postman_collection.json
  3. Click request yg mau dijalankan
  4. Click "Send"
  5. Response ditampilkan
  6. Data tersimpan

  ✅ Best untuk testing & dokumentasi
```

---

## Data Flow (Simplified)

```
┌──────────────────────────────┐
│   PILIH SALAH SATU METHOD    │
├──────────────────────────────┤
│  Browser │ CLI │ Postman    │
└──────────────────────────────┘
          │
          ▼
┌──────────────────────────────────────┐
│  CONTROLLER (OpenSeaController)      │
│  - Terima request                    │
│  - Tentukan platform & parameter     │
└──────────────────────────────────────┘
          │
          ▼
┌──────────────────────────────────────┐
│  SERVICE (MarketplaceApiService)     │
│  - Hit API marketplace               │
│  - Parse response → 5 kriteria       │
│  - Return formatted data             │
└──────────────────────────────────────┘
          │
          ▼
┌──────────────────────────────────────┐
│  SAVE TO DATABASE                    │
│  - Create aset_digitals              │
│  - Create 5 penilaians               │
│  - Return success response           │
└──────────────────────────────────────┘
          │
          ▼
┌──────────────────────────────────────┐
│  RESPONSE KEMBALI KE USER            │
│  {                                   │
│    "success": true,                  │
│    "aset_id": 1,                     │
│    "data": { 5 kriteria }            │
│  }                                   │
└──────────────────────────────────────┘
          │
          ▼
┌──────────────────────────────────────┐
│  NEXT: GUNAKAN UNTUK TOPSIS          │
│  - Ranking                           │
│  - Decision making                   │
└──────────────────────────────────────┘
```

---

## Perbandingan 3 Methods

| Aspek | Browser | CLI | Postman |
|-------|---------|-----|---------|
| Setup | ✅ Instant | ✅ Instant | ⏳ Install app |
| Kemudahan | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| Batch Processing | ❌ Lambat | ✅ Fast | ✅ Fast |
| Scheduling | ❌ Tidak bisa | ✅ Bisa | ❌ Tidak bisa |
| Dokumentasi | ⭐ 1 URL | ✅ Readable | ⭐⭐⭐⭐⭐ |
| Production | ❌ Tidak cocok | ✅ Perfect | ✅ Good |

---

## Setup Checklist (Copy-Paste)

### Step 1: Prepare Environment File
```bash
cd "c:\Users\Lenovo\SPK INVES DIGITAL\spk-aset-digital"
copy .env.example .env
```

### Step 2: Get API Keys
- OpenSea: https://docs.opensea.io/reference/api-keys
- Blur: https://blur.io/api
- Steam: No key needed!

### Step 3: Edit .env File
```env
OPENSEA_API_KEY=paste_your_key_here
OPENSEA_BASE_URL=https://api.opensea.io/v2
```

### Step 4: Test (Choose One)

**Browser:**
```
http://localhost:8000/fetch-opensea/boredapeyachtclub
```

**CLI:**
```bash
php artisan marketplace:fetch opensea boredapeyachtclub
```

**Postman:**
- Import postman_collection.json
- Send request

### Step 5: Verify Data
```bash
php artisan tinker

>>> App\Models\AsetDigital::first();
>>> App\Models\Penilaian::first()->kriteria;
```

---

## Examples: Fetch Berbagai Marketplace

```
┌─ OPENSEA (NFT Collections)
│
│  Browser:
│  http://localhost:8000/fetch-opensea/cryptopunks
│  http://localhost:8000/fetch-opensea/pudgypenguins
│  
│  CLI:
│  php artisan marketplace:fetch opensea cryptopunks
│
├─ STEAM MARKET (Gaming Items)
│
│  Browser:
│  http://localhost:8000/fetch-steam/730/AK-47%20%7C%20Dragon%20Lore
│  (730 = CS:GO App ID)
│  
│  CLI:
│  php artisan marketplace:fetch steam --params="730,AK-47 | Dragon Lore"
│
├─ BLUR (NFT Marketplace)
│
│  Browser:
│  http://localhost:8000/fetch-blur/0xbc4ca0eda.../123
│  
│  CLI:
│  php artisan marketplace:fetch blur --params="0xbc4ca0eda...,123"
│
└─ MAGIC EDEN (Solana NFTs)
   
   Browser:
   http://localhost:8000/fetch-magiceden/So1ipWXNUWYP8...
   
   CLI:
   php artisan marketplace:fetch magiceden --params="So1ipWXNUWYP8..."
```

---

## 5 Kriteria Mapping (Visualized)

```
OpenSea API Response:
{
  "total": { "floor_price": 45.5 }
  "intervals": [{ "volume": 250, "sales": 120 }]
}

       ↓ SERVICE MAPPING ↓

┌─────────────────────────────┐
│  5 KRITERIA TERISI:         │
├─────────────────────────────┤
│ 1️⃣  Harga: 45.5            │ (cost - minimize)
│ 2️⃣  Volume: 250            │ (benefit - maximize)
│ 3️⃣  Rarity: 3 (1-4 scale)  │ (benefit - maximize)
│ 4️⃣  Sentiment: 4 (1-5)     │ (benefit - maximize)
│ 5️⃣  Liquidity: 120         │ (benefit - maximize)
└─────────────────────────────┘

       ↓ DATABASE SAVE ↓

penilaians table:
ID | aset_id | kriteria_id | nilai
1  | 1       | 1           | 45.5     ← Harga
2  | 1       | 2           | 250      ← Volume
3  | 1       | 3           | 3        ← Rarity
4  | 1       | 4           | 4        ← Sentiment
5  | 1       | 5           | 120      ← Liquidity
```

---

## Batch Fetch (Multiple Items Sekaligus)

```
STEP 1: Prepare JSON file

{
  "platforms": [
    {
      "platform": "opensea",
      "params": {"slug": "boredapeyachtclub"}
    },
    {
      "platform": "opensea",
      "params": {"slug": "cryptopunks"}
    },
    {
      "platform": "steam",
      "params": {"app_id": "730", "item_name": "AK-47 | Dragon Lore"}
    }
  ]
}

STEP 2A: Via Postman
- Select "POST /fetch-batch" request
- Paste JSON in Body
- Click Send

STEP 2B: Via Curl
curl -X POST "http://localhost:8000/fetch-batch" \
  -H "Content-Type: application/json" \
  -d @example_batch_items.json

STEP 3: Response
{
  "status": "completed",
  "total_processed": 3,
  "results": [
    { "success": true, "aset_id": 1 },
    { "success": true, "aset_id": 2 },
    { "success": true, "aset_id": 3 }
  ]
}

✓ 3 items fetched sekaligus
✓ 3 rows in aset_digitals
✓ 15 rows in penilaians (5 per item)
```

---

## Error Handling (What to Do If...)

```
❌ Error: 401 Unauthorized from OpenSea
   → Check API key di .env
   → Verify key is valid at https://docs.opensea.io/
   → Check OPENSEA_API_KEY tidak typo

❌ Error: Timeout
   → Steam Market rate limit, try again later
   → Reduce batch size
   → Add sleep(3) between requests

❌ Error: 404 Not Found
   → Slug/itemName tidak valid
   → Coba dengan slug yang benar
   → Check OpenSea collection URL

❌ Error: No data saved
   → Check Kriteria sudah ada di database
   → Run: php artisan migrate
   → Verify model fillable properties

❌ Error: Services not found
   → Run: composer dump-autoload
   → Restart server
```

---

## Scheduling (Automatic Daily Updates)

```
Edit: app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    // Fetch batch every day at 2 AM
    $schedule->command('marketplace:fetch batch')
        ->dailyAt('02:00');
}

Run scheduler:
php artisan schedule:run

Or setup cron job:
* * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1
```

---

## Tips & Tricks

```
💡 TIP 1: Caching untuk reduce API calls
   Cache::remember("opensea_boredapeyachtclub", 3600, function() {
       return MarketplaceApiService::fetchOpenSeaData('boredapeyachtclub');
   });

💡 TIP 2: Rate Limiting
   foreach ($items as $item) {
       // fetch & save
       sleep(2); // Wait 2 seconds
   }

💡 TIP 3: Batch Import
   Use /fetch-batch endpoint untuk fetch 50+ items sekaligus

💡 TIP 4: Error Logging
   if ($result['error']) {
       Log::error('API Error: ' . $result['error']);
   }

💡 TIP 5: Validation
   Validate nilai kriteria sebelum save:
   if ($data['harga_beli'] < 0) return error;
```

---

## Decision Tree: Which Method to Use?

```
                        ┌─ Want to test 1 item?
                        │  └─→ Use BROWSER (fastest)
                        │
        Start Here → Need to automate?
                        │  └─→ Use CLI COMMAND
                        │
                        ├─ Need professional docs?
                        │  └─→ Use POSTMAN
                        │
                        └─ Building production system?
                           └─→ Use PROGRAMMATIC
                              (app/Services/MarketplaceApiService.php)
```

---

## Next Phase: TOPSIS Calculation

Setelah data fetched & tersimpan, use untuk TOPSIS:

```
1. Get aset data dari database ✅ (sekarang bisa!)
2. Get penilaian (5 kriteria) ✅ (sekarang bisa!)
3. Normalisasi nilai ← Start here
4. Weighted score calculation
5. Ideal solution determination
6. Ranking & recommendation
```

---

**READY TO START?**

1. Choose method (Browser/CLI/Postman)
2. Setup .env with API key
3. Run first test
4. Verify data in database
5. Go!

See **QUICKSTART.md** for 5-minute setup guide.
