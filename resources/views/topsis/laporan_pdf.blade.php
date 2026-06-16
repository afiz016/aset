<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $judulLaporan ?? 'Laporan Hasil TOPSIS' }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #1e293b;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 12px;
        }
        .header h2 {
            margin: 0;
            color: #0f172a;
            text-transform: uppercase;
            font-size: 16px;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 11px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            color: #475569;
            font-size: 11px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        table.data-table th {
            background-color: #0f172a;
            color: white;
            text-transform: uppercase;
            font-size: 9px;
            font-weight: bold;
            padding: 10px;
            border: 1px solid #1e293b;
        }
        table.data-table td {
            padding: 10px 8px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .rank-badge {
            font-weight: bold;
            font-size: 11px;
        }
        .status {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8.5px;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
        }
        .status-strong-buy { color: #166534; background-color: #dcfce7; }
        .status-buy { color: #1e40af; background-color: #dbeafe; }
        .status-hold { color: #854d0e; background-color: #fef9c3; }
        .status-avoid { color: #991b1b; background-color: #fee2e2; }
        
        .footer {
            position: fixed;
            bottom: -10px;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ $judulLaporan ?? 'LAPORAN HASIL PERHITUNGAN TOPSIS' }}</h2>
        <p>{{ $subJudul ?? 'Sistem Pendukung Keputusan Pemeringkatan Alternatif' }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Tanggal Cetak:</strong> {{ $tglCetak ?? date('d-m-Y H:i') }}</td>
            <td style="text-align: right;"><strong>Sumber Halaman:</strong> {{ $sumberHalaman ?? 'Sistem SPK' }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="8%">Rank</th>
                <th style="text-align: left;" width="42%">Nama Alternatif</th>
                <th width="15%">Jarak Ideal (+)</th>
                <th width="15%">Jarak Ideal (-)</th>
                <th width="20%">Nilai Preferensi (V)</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($hasilTopsis) && count($hasilTopsis) > 0)
                @foreach($hasilTopsis as $index => $r)
                    @php
                        // Memastikan data aman dibaca baik berupa Object (Eloquent) maupun Array hasil Service
                        $namaAset = is_object($r) ? ($r->nama_aset ?? $r->nama ?? ($r->asetDigital->nama_aset ?? 'Aset')) : ($r['nama_aset'] ?? $r['nama'] ?? 'Aset');
                        $scoreValue = is_object($r) ? ($r->nilai ?? $r->skor ?? $r->preferensi ?? $r->v ?? 0) : ($r['nilai'] ?? $r['skor'] ?? $r['preferensi'] ?? $r['v'] ?? 0);
                        $dPlus = is_object($r) ? ($r->d_plus ?? $r->dp ?? $r->d_positif ?? 0) : ($r['d_plus'] ?? $r['dp'] ?? $r['d_positif'] ?? 0);
                        $dMinus = is_object($r) ? ($r->d_minus ?? $r->dm ?? $r->d_negatif ?? 0) : ($r['d_minus'] ?? $r['dm'] ?? $r['d_negatif'] ?? 0);
                    @endphp
                    <tr>
                        <td class="rank-badge">{{ $index + 1 }}</td>
                        <td style="text-align: left; font-weight: bold; text-transform: uppercase;">{{ str_replace('-', ' ', $namaAset) }}</td>
                        <td>{{ round($dPlus, 4) }}</td>
                        <td>{{ round($dMinus, 4) }}</td>
                        <td style="font-weight: bold; font-size: 12px; color: #0284c7;">{{ round($scoreValue, 4) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8;">Tidak ada data pemeringkatan yang tersedia.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dokumen Laporan Hasil Perhitungan Metode TOPSIS &copy; {{ date('Y') }}
    </div>

</body>
</html>