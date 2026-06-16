<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Kriteria;
use App\Services\TopsisService;

class TopsisController extends Controller
{
    protected $topsisService;

    public function __construct(TopsisService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    /**
     * Halaman hasil perhitungan TOPSIS di browser.
     */
    public function hitung()
    {
        $topsisResult = $this->topsisService->hitungTopsis();

        $ranking = !empty($topsisResult) && isset($topsisResult['hasil_akhir'])
            ? $topsisResult['hasil_akhir']
            : [];

        return view('topsis.hasil', compact('ranking'));
    }

    /**
     * Halaman detail tahap-tahap perhitungan TOPSIS (matriks, normalisasi, bobot, dll).
     */
    public function perhitungan()
    {
        $topsisResult = $this->topsisService->hitungTopsis();

        if (empty($topsisResult)) {
            return view('topsis.perhitungan', [
                'kriterias'            => collect(),
                'asetDigitals'         => collect(),
                'matriksX'             => [],
                'matriksR'             => [],
                'matriksV'             => [],
                'solusiIdealPositif'   => [],
                'solusiIdealNegatif'   => [],
                'jarakPositif'         => [],
                'jarakNegatif'         => [],
                'hasilAkhir'           => [],
            ]);
        }

        return view('topsis.perhitungan', [
            'kriterias'            => $topsisResult['kriterias'],
            'asetDigitals'         => $topsisResult['aset_digitals'],
            'matriksX'             => $topsisResult['matriks_x'],
            'matriksR'             => $topsisResult['matriks_r'],
            'matriksV'             => $topsisResult['matriks_v'],
            'solusiIdealPositif'   => $topsisResult['solusi_ideal_positif'],
            'solusiIdealNegatif'   => $topsisResult['solusi_ideal_negatif'],
            'jarakPositif'         => $topsisResult['jarak_solusi_ideal_positif'],
            'jarakNegatif'         => $topsisResult['jarak_solusi_ideal_negatif'],
            'hasilAkhir'           => $topsisResult['hasil_akhir'],
        ]);
    }

    /**
     * Cetak laporan PDF hasil TOPSIS.
     * Bisa dipanggil dari halaman Dashboard maupun halaman Hasil TOPSIS.
     */
    public function cetakPdf(Request $request)
    {
        $topsisResult = $this->topsisService->hitungTopsis();

        $hasilTopsis = !empty($topsisResult) && isset($topsisResult['hasil_akhir'])
            ? $topsisResult['hasil_akhir']
            : [];

        $jumlahKriteria = Kriteria::count();
        $tglCetak       = date('d F Y H:i');

        // Deteksi sumber pemanggil (dashboard atau halaman topsis)
        if ($request->get('source') === 'dashboard') {
            $judulLaporan  = 'LAPORAN RINGKASAN REKOMENDASI - DASHBOARD';
            $subJudul      = 'Ringkasan Utama Pemeringkatan Alternatif Aset Digital';
            $sumberHalaman = 'Halaman Dashboard';
        } else {
            $judulLaporan  = 'LAPORAN HASIL PERHITUNGAN METODE TOPSIS';
            $subJudul      = 'Hasil Detail Analisis Solusi Ideal Positif dan Negatif';
            $sumberHalaman = 'Halaman Hasil TOPSIS';
        }

        $pdf = Pdf::loadView('topsis.laporan_pdf', compact(
            'hasilTopsis',
            'jumlahKriteria',
            'tglCetak',
            'judulLaporan',
            'subJudul',
            'sumberHalaman'
        ));

        return $pdf->download('Laporan_TOPSIS_' . date('Ymd_His') . '.pdf');
    }
}
