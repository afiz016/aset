<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternatives', function (Blueprint $table) {
            $table->id();
            $table->string('item_name')->unique(); // Menyimpan slug unik NFT (ex: bored-ape-yacht-club)
            $table->double('price')->default(0);  // Kriteria 1: Harga Beli Saat Ini (Cost) 
            $table->double('volume')->default(0); // Kriteria 2: Volume Transaksi 24 Jam (Benefit) 
            $table->integer('rarity')->default(1); // Kriteria 3: Tingkat Kelangkaan (Rarity) 
            $table->integer('sentiment')->default(1); // Kriteria 4: Market Sentiment 
            $table->double('liquidity')->default(0); // Kriteria 5: Tingkat Likuiditas 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatives');
    }
};