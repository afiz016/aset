# 🚀 Quick Start Guide - API Data Retrieval

## 5 Menit Setup

### 1️⃣ Setup Environment
```bash
cd c:\Users\Lenovo\SPK INVES DIGITAL\spk-aset-digital

# Copy env file
copy .env.example .env

# Generate app key
php artisan key:generate

# Run migrations (jika belum)
php artisan migrate
```

### 2️⃣ Setup API Keys

Edit `.env` dan isi yang penting:

```env
# Minimal requirement (for OpenSea)
OPENSEA_API_KEY=your_key_here
OPENSEA_BASE_URL=https://api.opensea.io/v2
```

**Daftar API Keys:**
- **OpenSea**: https://docs.opensea.io/reference/api-keys
- **Blur**: https://blur.io/api (optional)
- **Twitter**: https://developer.twitter.com/ (optional, for sentiment)
- **Steam**: No key needed! (public data)

---

## ✅ Testing

### Test 1: Fetch OpenSea NFT
```bash
# Via browser
http://localhost:8000/fetch-opensea/boredapeyachtclub

# Via curl
curl "http://localhost:8000/fetch-opensea/boredapeyachtclub" | jq
```

Expected response:
```json
{
  "success": true,
  "message": "Data boredapeyachtclub dari OpenSea berhasil disimpan",
  "aset_id": 1,
  "data": {
    "harga_beli": 45.5,
    "volume_24h": 250,
    "rarity": 3,
    "sentiment": 4,
    "liquidity": 120
  }
}
```

### Test 2: Fetch Steam Market Item
```bash
curl "http://localhost:8000/fetch-steam/730/AK-47%20%7C%20Dragon%20Lore" | jq
```

### Test 3: Batch Fetch
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
        "platform": "opensea",
        "params": {"slug": "cryptopunks"}
      }
    ]
  }' | jq
```

### Test 4: Via Artisan Command
```bash
# Single fetch
php artisan marketplace:fetch opensea boredapeyachtclub

# Steam
php artisan marketplace:fetch steam --params="730,AK-47 | Dragon Lore"

# Batch
php artisan marketplace:fetch batch
```

---

## 🔍 Verifikasi Data di Database

```bash
php artisan tinker

# Check aset_digitals
>>> App\Models\AsetDigital::all();

# Check penilaians
>>> App\Models\Penilaian::with('kriteria')->get();

# Check specific aset
>>> App\Models\AsetDigital::first()->penilaians()->with('kriteria')->get();
```

---

## 📊 Data Mapping ke 5 Kriteria

| API Response | Kriteria | DB Column |
|------------|----------|-----------|
| floor_price | Harga Beli Saat Ini | `nilai` |
| volume | Volume Transaksi 24 Jam | `nilai` |
| attributes count | Tingkat Kelangkaan | `nilai` |
| community votes | Market Sentiment | `nilai` |
| sales/listed | Tingkat Likuiditas | `nilai` |

---

## 🎯 Common Workflows

### Workflow A: Manual Add 1 Item
```bash
curl "http://localhost:8000/fetch-opensea/boredapeyachtclub"
```

### Workflow B: Import 10 Items at Once
```bash
curl -X POST "http://localhost:8000/fetch-batch" \
  -H "Content-Type: application/json" \
  -d @batch_items.json
```

`batch_items.json`:
```json
{
  "platforms": [
    {"platform": "opensea", "params": {"slug": "boredapeyachtclub"}},
    {"platform": "opensea", "params": {"slug": "cryptopunks"}},
    {"platform": "steam", "params": {"app_id": 730, "item_name": "AK-47 | Dragon Lore"}}
  ]
}
```

### Workflow C: Daily Scheduled Updates
Edit `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('marketplace:fetch batch')
        ->dailyAt('02:00');
}
```

Then run scheduler:
```bash
php artisan schedule:run
```

---

## 🐛 Troubleshooting

### Error: "API Key invalid"
```
✗ Check .env file
✗ Verify OPENSEA_API_KEY is set correctly
✗ Test key at: https://docs.opensea.io/reference/api-keys
```

### Error: "Timeout"
```
✗ Steam Market might be rate-limiting
✗ Add delays between requests: sleep(2)
✗ Use caching: Cache::remember(...)
```

### Error: "No data returned"
```
✗ Verify slug/params are correct
✗ Check API documentation for valid parameters
✗ Test with curl first
```

---

## 📚 Full Documentation

See: `API_RETRIEVAL_GUIDE.md` for complete reference

---

## 🎓 Next Steps

1. ✅ Setup environment & test basic fetch
2. ✅ Get API keys untuk semua platform yang diperlukan
3. ✅ Import 20-50 items dari berbagai marketplace
4. ✅ Verify data tersimpan di database
5. ✅ Run TOPSIS calculation untuk ranking
6. ✅ Schedule daily/hourly data refresh

---

## 📞 Support

Need help? Check:
- `API_RETRIEVAL_GUIDE.md` - Full documentation
- `app/Services/MarketplaceApiService.php` - Implementation details
- `app/Http/Controllers/OpenSeaController.php` - Controller methods
