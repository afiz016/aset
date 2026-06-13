<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AsetDigital extends Model {
    protected $table = 'aset_digitals';
    protected $fillable = ['nama_aset', 'jenis_aset'];

    public function penilaians() {
        return $this->hasMany(Penilaian::class, 'aset_digital_id');
    }
}