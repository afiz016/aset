<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Kriteria::create(['kode_kriteria' => 'C1', 'nama_kriteria' => 'Harga Beli Saat Ini', 'bobot' => 25, 'jenis' => 'cost']);
        Kriteria::create(['kode_kriteria' => 'C2', 'nama_kriteria' => 'Volume Transaksi (24 Jam)', 'bobot' => 20, 'jenis' => 'benefit']);
        Kriteria::create(['kode_kriteria' => 'C3', 'nama_kriteria' => 'Tingkat Kelangkaan (Rarity)', 'bobot' => 20, 'jenis' => 'benefit']);
        Kriteria::create(['kode_kriteria' => 'C4', 'nama_kriteria' => 'Market Sentiment', 'bobot' => 15, 'jenis' => 'benefit']);
        Kriteria::create(['kode_kriteria' => 'C5', 'nama_kriteria' => 'Tingkat Likuiditas', 'bobot' => 20, 'jenis' => 'benefit']);
    }
}