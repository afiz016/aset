<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil TOPSIS - Cyber Finance</title>
    <style>
        /* CSS SEDERHANA KHUSUS DOMPDF */
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #00d4ff;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #64748b;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            color: #64748b;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #0f172a;
            color: white;
            text-transform: uppercase;
            font-size: 10px;
            padding: 10px;
            border: 1px solid #1e293b;
        }
        table.data-table td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .rank-badge {
            font-weight: bold;
            font-size: 14px;
        }
        .rank-1 { color: #eab308; }
        .rank-2 { color: #94a3b8; }
        .rank-3 { color: #cd7f32; }
        .status {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 3px 8px;
            border-radius: 10px;
        }
        /* Warna Status Mode Terang untuk Cetakan */
        .status-strong-buy { color: #166534; background-color: #dcfce7; }
        .status-buy { color: #1e40af; background-color: #dbeafe; }
        .status-hold { color: #854d0e; background-color: #fef9c3; }
        .status-avoid { color: #991b1b; background-color: #fee2e2; }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            padding: 10px 0;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Protokol Cyber Finance</h2>
        <p>Laporan Akhir Analisis Kelayakan Investasi Aset Digital (Metode TOPSIS)</p>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Tanggal Cetak:</strong> {{ $tglCetak }}</td>
            <td style="text-align: right;"><strong>Total Kriteria:</strong> {{ $jumlahKriteria }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>Rank</th>
                <th style="text-align: left;">Nama Aset Digital</th>
                <th>Solusi Ideal (+)</th>
                <th>Solusi Ideal (-)</th>
                <th>Nilai Preferensi (V)</th>
                <th>Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rankingData as $index => $r)
                @php
                    $scoreValue = $r['nilai'];
                    if ($scoreValue >= 0.8) { $statusClass = 'status-strong-buy'; $statusText = 'Strong Buy'; }
                    elseif ($scoreValue >= 0.6) { $statusClass = 'status-buy'; $statusText = 'Buy'; }
                    elseif ($scoreValue >= 0.4) { $statusClass = 'status-hold'; $statusText = 'Hold'; }
                    else { $statusClass = 'status-avoid'; $statusText = 'Avoid'; }
                    
                    $rankClass = $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : ''));
                @endphp
                <tr>
                    <td class="rank-badge {{ $rankClass }}">{{ $index + 1 }}</td>
                    <td style="text-align: left; font-weight: bold; text-transform: uppercase;">{{ str_replace('-', ' ', $r['nama']) }}</td>
                    <td>{{ round($r['d_plus'], 4) }}</td>
                    <td>{{ round($r['d_minus'], 4) }}</td>
                    <td style="font-weight: bold; font-size: 13px;">{{ round($scoreValue, 4) }}</td>
                    <td><span class="status {{ $statusClass }}">{{ $statusText }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dihasilkan secara otomatis oleh Sistem Protokol Cyber Finance &copy; {{ date('Y') }}
    </div>

</body>
</html>