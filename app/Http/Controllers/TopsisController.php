<?php

namespace App\Http\Controllers;

use App\Services\TopsisService;
use App\Models\Kriteria;
// 🚀 IMPORT FACADE DOMPDF
use Barryvdh\DomPDF\Facade\Pdf; 

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

    // 🚀 FUNGSI BARU: LANGSUNG DOWNLOAD PDF SINKRON DENGAN SERVICE
    public function cetakPdf()
    {
        // 1. Eksekusi service perhitungan yang sama persis agar datanya sinkron
        $hasilTopsis = $this->topsisService->hitungTopsis();
        
        // 2. Ambil meta data tambahan untuk kelengkapan laporan TA
        $jumlahKriteria = Kriteria::count();
        $tglCetak = now()->translatedFormat('d F Y H:i'); // Format waktu Indonesia

        // 3. Render ke view khusus cetak PDF dengan variabel hasilTopsis
        $pdf = Pdf::loadView('topsis.cetak_pdf', compact('hasilTopsis', 'jumlahKriteria', 'tglCetak'));
        
        // 4. Atur ukuran kertas ke A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        // 5. Langsung memicu browser untuk download otomatis berkas PDF
        return $pdf->download('Laporan-Hasil-TOPSIS-' . now()->format('YmdHi') . '.pdf');
    }
}