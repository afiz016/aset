<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\AsetDigital;
use App\Models\Penilaian;
use App\Services\TopsisService;
use Barryvdh\DomPDF\Facade\Pdf;
class DashboardController extends Controller
{
    protected $topsisService;

    public function __construct(TopsisService $topsisService)
    {
        $this->topsisService = $topsisService;
    }
    public function exportPdf()
    {
        $totalKriteria = Kriteria::count();
        $totalAsetDigital = AsetDigital::count();
        $totalPenilaian = Penilaian::count();

        // Mengambil hasil komputasi TOPSIS lengkap dari Service
        $topsisResult = $this->topsisService->hitungTopsis();
        
        $daftarRanking = !empty($topsisResult) && isset($topsisResult['hasil_akhir']) 
            ? $topsisResult['hasil_akhir'] 
            : [];
            
        $asetTerbaik = !empty($daftarRanking) ? $daftarRanking[0] : null;

        // Load halaman khusus cetak PDF dengan data dinamis
        $pdf = Pdf::loadView('topsis.laporan_pdf', compact(
            'totalKriteria',
            'totalAsetDigital',
            'totalPenilaian',
            'asetTerbaik',
            'daftarRanking'
        ));

        // Mengunduh berkas langsung ke komputer pengguna
        return $pdf->download('Laporan-Analisis-TOPSIS-InvestGame.pdf');
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
