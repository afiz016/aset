<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ingat, kita memaksanya membuat tabel dengan nama "kriteria" (tanpa S)
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria', 5); // C1, C2, C3, C4, C5
            $table->string('nama_kriteria');
            $table->double('bobot');            // Bobot kriteria (W)
            $table->enum('jenis', ['benefit', 'cost']); // Sifat kriteria
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kriteria');
    }
};