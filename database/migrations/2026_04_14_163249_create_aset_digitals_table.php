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
            $table->string('jenis_aset');       // Kategori game atau jenis aset
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
