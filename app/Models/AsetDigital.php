<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AsetDigital extends Model {
    protected $table = 'aset_digitals';
    // Perbarui baris fillable di bawah ini
    protected $fillable = ['nama_aset', 'jenis_aset', 'foto_url', 'platform_url', 'raw_data'];

    public function penilaians() {
        return $this->hasMany(Penilaian::class, 'aset_digital_id');
    }
}