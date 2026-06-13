<?php

namespace App\Http\Controllers;

use App\Services\TopsisService;

class TopsisController extends Controller
{
    protected $topsisService;

    // Masukkan TopsisService lewat Dependency Injection
    public function __construct(TopsisService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    public function hitung()
    {
        // 1. Eksekusi service perhitungan TOPSIS
        $hasilTopsis = $this->topsisService->hitungTopsis();
        
        // 2. Kirim hasilnya ke view dengan nama variabel yang benar
        return view('topsis.hasil', compact('hasilTopsis'));
    }
}