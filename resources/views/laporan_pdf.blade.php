<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Analisis Rekomendasi Investasi TOPSIS</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; font-size: 12px; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #0284c7; padding-bottom: 12px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 15px; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; }
        .header p { margin: 6px 0 0; font-size: 11px; color: #64748b; }
        .meta-info { margin-bottom: 20px; width: 100%; }
        .meta-info td { vertical-align: top; font-size: 11px; padding: 4px 0; }
        .section-title { font-size: 12px; font-weight: bold; color: #0284c7; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; margin-top: 25px; margin-bottom: 12px; text-transform: uppercase; }
        .grid-summary { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .grid-summary td { padding: 10px; border: 1px solid #e2e8f0; background: #f8fafc; text-align: center; width: 25%; }
        .summary-num { font-size: 16px; font-weight: bold; color: #0f172a; }
        .summary-label { font-size: 9px; color: #64748b; text-transform: uppercase; margin-top: 3px; font-weight: bold; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th { background-color: #0f172a; color: #ffffff; padding: 9px 12px; font-size: 11px; text-transform: uppercase; text-align: left; border: 1px solid #0f172a; }
        table.data-table td { padding: 9px 12px; border: 1px solid #e2e8f0; font-size: 11px; }
        table.data-table tr:nth-child(even) { background-color: #f8fafc; }
        .font-mono { font-family: Courier, monospace; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer-sign { margin-top: 50px; float: right; width: 220px; text-align: center; font-size: 11px; }
        .footer-sign .space { height: 65px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Sistem Pendukung Keputusan Pemilihan Investasi Aset Digital</h2>
        <p>Output Komputasi Matriks Kedekatan Relatif Solusi Ideal Berbasis Metode TOPSIS</p>
    </div>

    <table class="meta-info">
        <tr>
            <td style="width: 18%;"><strong>Nama Analis</strong></td>
            <td style="width: 42%;">: {{ Auth::user()->name }}</td>
            <td style="width: 18%;"><strong>Tanggal Cetak</strong></td>
            <td>: {{ date('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>Algoritma Inti</strong></td>
            <td colspan="3">: TOPSIS (Jarak Euclidean)</td>
        </tr>
    </table>

    <div class="section-title">Ringkasan Parameter Basis Data</div>
    <table class="grid-summary">
        <tr>
            <td>
                <div class="summary-num">{{ $totalAsetDigital }}</div>
                <div class="summary-label">Alternatif Aset</div>
            </td>
            <td>
                <div class="summary-num">{{ $totalKriteria }}</div>
                <div class="summary-label">Kriteria Aktif</div>
            </td>
            <td>
                <div class="summary-num">{{ $totalPenilaian }}</div>
                <div class="summary-label">Matriks Data Terisi</div>
            </td>
            <td>
                <div class="summary-num" style="color: #b45309;">
                    {{ $asetTerbaik ? number_format($asetTerbaik['preferensi'], 4) : '-' }}
                </div>
                <div class="summary-label">Skor Preferensi Tertinggi</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Hasil Akhir Perankingan Kedekatan Relatif ($V_i$)</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%; text-align: center;">Rank</th>
                <th style="width: 45%;">Nama Alternatif Aset Digital</th>
                <th style="width: 25%;">Kategori/Jenis Game</th>
                <th style="width: 20%; text-align: right;">Nilai Preferensi ($V_i$)</th>
            </tr>
        </thead>
        <tbody>
            @if(count($daftarRanking) > 0)
                @foreach($daftarRanking as $index => $row)
                    <tr>
                        <td class="text-center font-mono">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <strong>{{ $row['nama_aset'] }}</strong>
                            @if($index == 0)
                                <span style="color: #b45309; font-size: 9px; font-weight: bold; margin-left: 5px;">(REKOMENDASI UTAMA)</span>
                            @endif
                        </td>
                        <td>{{ $row['jenis_aset'] ?? 'Item Game' }}</td>
                        <td class="text-right font-mono" style="{{ $index == 0 ? 'color: #b45309; font-weight: bold;' : '' }}">
                            {{ number_format($row['preferensi'], 4) }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">Belum ada data hasil perhitungan alternatif bursa terbaru.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-sign">
        <p>Wonosobo, {{ date('d F Y') }}</p>
        <p>Analis Sistem,</p>
        <div class="space"></div>
        <p><u><strong>{{ Auth::user()->name }}</strong></u></p>
    </div>

</body>
</html>