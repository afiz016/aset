<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\AsetDigital;
use App\Models\Penilaian;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Parameter Kriteria TOPSIS
        // Mendefinisikan kriteria dan bobotnya (C1-C5)
        $kriteria_c1 = Kriteria::create(['kode_kriteria' => 'C1', 'nama_kriteria' => 'Harga Beli Saat Ini', 'bobot' => 25, 'jenis' => 'cost']);
        $kriteria_c2 = Kriteria::create(['kode_kriteria' => 'C2', 'nama_kriteria' => 'Volume Transaksi (24 Jam)', 'bobot' => 20, 'jenis' => 'benefit']);
        $kriteria_c3 = Kriteria::create(['kode_kriteria' => 'C3', 'nama_kriteria' => 'Tingkat Kelangkaan (Rarity)', 'bobot' => 20, 'jenis' => 'benefit']);
        $kriteria_c4 = Kriteria::create(['kode_kriteria' => 'C4', 'nama_kriteria' => 'Market Sentiment', 'bobot' => 15, 'jenis' => 'benefit']);
        $kriteria_c5 = Kriteria::create(['kode_kriteria' => 'C5', 'nama_kriteria' => 'Tingkat Likuiditas', 'bobot' => 20, 'jenis' => 'benefit']);

        // 2. Definisi Daftar 12 Aset Master dengan Harga Awal Cadangan Rill
        $asetList = [
            // KELOMPOK OPENSEA MARKETPLACE (Satuan ETH)
            [
                'name' => 'boredapeyachtclub',
                'platform' => 'opensea',
                'harga_awal' => 14.50, // C1
                'vals' => [231.07, 4, 3, 10] // C2, C3, C4, C5
            ],
            [
                'name' => 'mutant-ape-yacht-club',
                'platform' => 'opensea',
                'harga_awal' => 2.30, // C1 (Fixed agar tidak 0)
                'vals' => [39.44, 3, 2, 21] // C2, C3, C4, C5
            ],
            [
                'name' => 'azuki',
                'platform' => 'opensea',
                'harga_awal' => 3.50, // C1 (Fixed agar tidak 0)
                'vals' => [13.96, 3, 4, 4] // C2, C3, C4, C5
            ],
            [
                'name' => 'pudgypenguins',
                'platform' => 'opensea',
                'harga_awal' => 8.00, // C1 (Fixed agar tidak 0)
                'vals' => [63.31, 4, 4, 9] // C2, C3, C4, C5
            ],
            [
                'name' => 'clonex',
                'platform' => 'opensea',
                'harga_awal' => 0.50, // C1
                'vals' => [2.78, 4, 2, 5] // C2, C3, C4, C5
            ],
            [
                'name' => 'doodles-official',
                'platform' => 'opensea',
                'harga_awal' => 0.80, // C1
                'vals' => [5.56, 2, 2, 5] // C2, C3, C4, C5
            ],
            [
                'name' => 'cool-cats-nft',
                'platform' => 'opensea',
                'harga_awal' => 0.30, // C1
                'vals' => [1.39, 3, 3, 9] // C2, C3, C4, C5
            ],

            // 🚀 FIXED CAPS: KELOMPOK STEAM MARKETPLACE (Satuan USD, Huruf Kapital Resmi CS2)
            [
                'name' => 'AK-47 | Asiimov (Field-Tested)',
                'platform' => 'steam',
                'harga_awal' => 150.00, // C1 (Fixed agar tidak 0 USD)
                'vals' => [145.00, 3, 4, 85] // C2, C3, C4, C5
            ],
            [
                'name' => 'M4A1-S | Printstream (Field-Tested)',
                'platform' => 'steam',
                'harga_awal' => 88.00, // C1
                'vals' => [98.40, 4, 5, 42] // C2, C3, C4, C5
            ],
            [
                'name' => 'AWP | Atheris (Minimal Wear)',
                'platform' => 'steam',
                'harga_awal' => 10.00, // C1 (Fixed agar tidak 0 USD)
                'vals' => [340.10, 2, 3, 120] // C2, C3, C4, C5
            ],
            [
                'name' => 'Glock-18 | Fade (Factory New)',
                'platform' => 'steam',
                'harga_awal' => 1250.00, // C1
                'vals' => [12.00, 5, 5, 4] // C2, C3, C4, C5
            ],
            [
                'name' => 'Desert Eagle | Printstream (Minimal Wear)',
                'platform' => 'steam',
                'harga_awal' => 65.00, // C1
                'vals' => [87.60, 4, 4, 38] // C2, C3, C4, C5
            ],
        ];

        // 3. Proses Loop Pendaftaran Aset & Pengisian Nilai Kriteria Awal
        foreach ($asetList as $item) {
            $aset = AsetDigital::create([
                'nama_aset' => $item['name'],
                'jenis_aset' => $item['platform']
            ]);

            // 🔥 MENYUNTIKKAN HARGA AWAL KE KRITERIA C1 (HARGA BELI)
            Penilaian::create([
                'aset_digital_id' => $aset->id,
                'kriteria_id' => $kriteria_c1->id,
                'nilai' => $item['harga_awal']
            ]);

            // Mengisi kriteria C2, C3, C4, C5 otomatis dengan data vals
            Penilaian::create(['aset_digital_id' => $aset->id, 'kriteria_id' => $kriteria_c2->id, 'nilai' => $item['vals'][0]]);
            Penilaian::create(['aset_digital_id' => $aset->id, 'kriteria_id' => $kriteria_c3->id, 'nilai' => $item['vals'][1]]);
            Penilaian::create(['aset_digital_id' => $aset->id, 'kriteria_id' => $kriteria_c4->id, 'nilai' => $item['vals'][2]]);
            Penilaian::create(['aset_digital_id' => $aset->id, 'kriteria_id' => $kriteria_c5->id, 'nilai' => $item['vals'][3]]);
        }
    }
}