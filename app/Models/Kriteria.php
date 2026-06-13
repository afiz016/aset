<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model {
    protected $table = 'kriteria';
    protected $fillable = ['kode_kriteria', 'nama_kriteria', 'bobot', 'jenis'];

    public function penilaians() {
        return $this->hasMany(Penilaian::class, 'kriteria_id');
    }
}