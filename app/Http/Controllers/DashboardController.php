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
        // Mengambil hasil komputasi TOPSIS lengkap dari Service
        $topsisResult = $this->topsisService->hitungTopsis();

        $hasilTopsis = !empty($topsisResult) && isset($topsisResult['hasil_akhir'])
            ? $topsisResult['hasil_akhir']
            : [];

        $jumlahKriteria  = Kriteria::count();
        $tglCetak        = date('d F Y H:i');
        $judulLaporan    = 'LAPORAN RINGKASAN REKOMENDASI - DASHBOARD';
        $subJudul        = 'Ringkasan Utama Pemeringkatan Alternatif Aset Digital';
        $sumberHalaman   = 'Halaman Dashboard';

        $pdf = Pdf::loadView('topsis.laporan_pdf', compact(
            'hasilTopsis',
            'jumlahKriteria',
            'tglCetak',
            'judulLaporan',
            'subJudul',
            'sumberHalaman'
        ));

        return $pdf->download('Laporan-Analisis-TOPSIS-InvestGame.pdf');
    }

    public function index()
    {
        // 1. Total Kriteria Aktif
        $totalKriteria = Kriteria::count();

        // 2. Total Aset Digital
        $totalAsetDigital = AsetDigital::count();

        // 3. Total Penilaian
        $totalPenilaian = Penilaian::count();

        // 4. Hitung TOPSIS
        $topsisResult = $this->topsisService->hitungTopsis();
        $hasilAkhir   = !empty($topsisResult) && isset($topsisResult['hasil_akhir'])
            ? collect($topsisResult['hasil_akhir'])
            : collect();

        $asetTerbaik = $hasilAkhir->isNotEmpty() ? $hasilAkhir->first() : null;

        // 5. Kelengkapan penilaian
        $rataRataPenilaian = $totalAsetDigital > 0 && $totalKriteria > 0
            ? round($totalPenilaian / ($totalAsetDigital * $totalKriteria) * 100, 2)
            : 0;

        // 6. Ticker — aset dengan skor TOPSIS (atau tanpa skor jika belum ada)
        if ($hasilAkhir->isNotEmpty()) {
            $tickerAsets = $hasilAkhir->map(fn($r) => [
                'nama_aset'  => $r['nama_aset'],
                'jenis_aset' => $r['jenis_aset'] ?? '-',
                'preferensi' => $r['preferensi'],
            ]);
        } else {
            $tickerAsets = AsetDigital::all()->map(fn($a) => [
                'nama_aset'  => $a->nama_aset,
                'jenis_aset' => $a->jenis_aset,
                'preferensi' => null,
            ]);
        }

        // 7. Ranking top-5 untuk tabel dashboard (dari hasil TOPSIS, fallback ke list kosong)
        $daftarRanking = $hasilAkhir->take(5)->values();

        // 8. Distribusi jenis aset untuk bar chart
        $semuaAset = AsetDigital::all();
        $distribusiPlatform = $semuaAset
            ->groupBy(fn($a) => strtolower($a->jenis_aset))
            ->map(fn($group, $platform) => [
                'platform' => ucfirst($platform),
                'jumlah'   => $group->count(),
            ])
            ->sortByDesc('jumlah')
            ->values();

        // 9. Kriteria dari database untuk sidebar
        $kriterias = \App\Models\Kriteria::all();

        return view('dashboard', compact(
            'totalKriteria',
            'totalAsetDigital',
            'totalPenilaian',
            'asetTerbaik',
            'rataRataPenilaian',
            'tickerAsets',
            'daftarRanking',
            'distribusiPlatform',
            'kriterias'
        ));
    }
}
