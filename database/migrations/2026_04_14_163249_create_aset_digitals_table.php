<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('aset_digitals', function (Blueprint $table) {
        $table->id();
        $table->string('nama_aset');        // Nama skin / item NFT
        $table->string('jenis_aset');       // Kategori game atau platform bursa
        $table->string('foto_url')->nullable(); // TAMBAHKAN INI: Untuk menyimpan link gambar asli
        $table->string('platform_url')->nullable(); 
        $table->text('raw_data')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset_digitals');
    }
};
