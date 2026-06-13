<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;

    // Daftarkan semua kolom yang bisa diisi oleh API OpenSea
    protected $fillable = [
        'item_name',
        'price',
        'volume',
        'rarity',
        'sentiment',
        'liquidity',
    ];
}