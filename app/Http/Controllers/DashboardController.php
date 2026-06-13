<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\AsetDigital;
use App\Models\Penilaian;
use App\Services\TopsisService;

class DashboardController extends Controller
{
    protected $topsisService;

    public function __construct(TopsisService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    public function index()
    {
        // 1. Total Kriteria Aktif
        $totalKriteria = Kriteria::count();

        // 2. Total Aset Digital yang Telah Dinilai
        $totalAsetDigital = AsetDigital::count();

        // 3. Total Penilaian (evaluations)
        $totalPenilaian = Penilaian::count();

        // 4. Aset Digital dengan Skor Tertinggi (Rekomendasi Terbaik)
        $topsisResult = $this->topsisService->hitungTopsis();
        $asetTerbaik = !empty($topsisResult) && isset($topsisResult['hasil_akhir']) && count($topsisResult['hasil_akhir']) > 0 
            ? $topsisResult['hasil_akhir'][0] 
            : null;

        // 5. Statistik tambahan
        $rataRataPenilaian = $totalAsetDigital > 0 && $totalKriteria > 0 
            ? round($totalPenilaian / ($totalAsetDigital * $totalKriteria) * 100, 2)
            : 0;

        return view('dashboard', compact(
            'totalKriteria',
            'totalAsetDigital',
            'totalPenilaian',
            'asetTerbaik',
            'rataRataPenilaian'
        ));
    }
}
