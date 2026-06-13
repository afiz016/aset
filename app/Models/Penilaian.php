<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model {
    protected $table = 'penilaians';
    protected $fillable = ['aset_digital_id', 'kriteria_id', 'nilai'];

    public function kriteria() {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function asetDigital() {
        return $this->belongsTo(AsetDigital::class, 'aset_digital_id');
    }
}