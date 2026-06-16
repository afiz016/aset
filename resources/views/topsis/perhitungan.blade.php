@extends('layouts.app')

@section('title', 'Detail Tahap Perhitungan TOPSIS')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap');

    :root {
        --bg-primary: #0a0f1d;
        --bg-secondary: #0f172a;
        --text-primary: #ffffff;
        --text-muted: #94a3b8;
        --border-color: rgba(0, 212, 255, 0.10);
        --accent: #00d4ff;
    }
    [data-theme="light"], .light {
        --bg-primary: #f1f5f9;
        --bg-secondary: #ffffff;
        --text-primary: #0f172a;
        --text-muted: #64748b;
        --border-color: rgba(15, 23, 42, 0.12);
        --accent: #0284c7;
    }

    body, html, main, .main-content, .content-wrapper, #app, .wrapper {
        background-color: var(--bg-primary) !important;
    }

    .page-wrap {
        font-family: 'Space Grotesk', sans-serif;
        color: var(--text-primary);
        padding: 24px;
    }

    /* TOPBAR */
    .topbar {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .topbar-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--accent);
        font-family: 'JetBrains Mono', monospace;
        letter-spacing: 0.5px;
    }
    .icon-btn {
        width: 38px; height: 38px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border-color);
        border-radius: 10px; cursor: pointer;
        color: var(--text-muted); background: transparent;
        transition: all 0.2s;
        text-decoration: none;
    }
    .icon-btn:hover { color: var(--accent); border-color: var(--accent); background: rgba(0,212,255,0.05); }

    /* SECTION CARD */
    .section-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        overflow-x: auto;
    }
    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--accent);
        font-family: 'JetBrains Mono', monospace;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .section-desc {
        font-size: 11.5px;
        color: var(--text-muted);
        margin-bottom: 16px;
    }

    /* TABLE */
    .topsis-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        min-width: 500px;
    }
    .topsis-table th {
        background: #0f172a;
        color: var(--accent);
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 10px 14px;
        border: 1px solid var(--border-color);
        white-space: nowrap;
    }
    [data-theme="light"] .topsis-table th,
    .light .topsis-table th {
        background: #1e293b;
    }
    .topsis-table td {
        padding: 9px 14px;
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        text-align: center;
        white-space: nowrap;
    }
    .topsis-table td.nama-col {
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .topsis-table tr:nth-child(even) td {
        background: rgba(148,163,184,0.03);
    }
    .topsis-table tr:hover td {
        background: rgba(0,212,255,0.03);
    }

    /* IDEAL ROW */
    .ideal-row td {
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
    }
    .ideal-pos td { color: #22c55e !important; background: rgba(34,197,94,0.05) !important; }
    .ideal-neg td { color: #f97316 !important; background: rgba(249,115,22,0.05) !important; }

    /* BADGE STATUS */
    .badge-rec {
        font-size: 9.5px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        font-family: 'JetBrains Mono', monospace;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .strong-buy { background: rgba(34,197,94,0.12); color: #22c55e; border: 1px solid rgba(34,197,94,0.2); }
    .buy        { background: rgba(59,130,246,0.12); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); }
    .hold       { background: rgba(234,179,8,0.12);  color: #eab308; border: 1px solid rgba(234,179,8,0.2); }
    .avoid      { background: rgba(239,68,68,0.12);  color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }

    /* STEP BADGE */
    .step-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(0,212,255,0.07);
        border: 1px solid rgba(0,212,255,0.2);
        border-radius: 20px;
        padding: 4px 14px 4px 6px;
        margin-bottom: 14px;
    }
    .step-num {
        width: 24px; height: 24px;
        background: var(--accent);
        color: #0a0f1d;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 12px;
        font-family: 'JetBrains Mono', monospace;
    }
    .step-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--accent);
        font-family: 'JetBrains Mono', monospace;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* RANK CIRCLE */
    .rank-circle {
        width: 28px; height: 28px;
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 12px;
        font-family: 'JetBrains Mono', monospace;
    }
    .rank-gold   { background: #eab308; color: #000; }
    .rank-silver { background: #94a3b8; color: #000; }
    .rank-bronze { background: #cd7f32; color: #fff; }
    .rank-other  { background: rgba(148,163,184,0.2); color: var(--text-muted); }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }
</style>

<!-- TOPBAR -->
<div class="topbar">
    <span class="topbar-title">DETAIL TAHAP PERHITUNGAN TOPSIS</span>
    <div class="d-flex gap-2 align-items-center">
        <div class="icon-btn" id="themeToggle" title="Ubah Tema">
            <i class="ti ti-sun" id="themeIcon"></i>
        </div>
        <a href="{{ route('topsis.hasil') }}" class="icon-btn" title="Kembali ke Hasil">
            <i class="ti ti-arrow-left"></i>
        </a>
        <a href="{{ route('topsis.cetak') }}" class="btn btn-sm font-mono d-inline-flex align-items-center gap-2"
           style="background: var(--accent); color: #0a0f1d; font-weight: 700; border-radius: 8px; border: none; height: 38px; padding: 0 16px; font-size: 12px;">
            <i class="ti ti-printer" style="font-size: 14px;"></i> Cetak PDF
        </a>
    </div>
</div>

<div class="page-wrap">

@if($kriterias->isEmpty() || $asetDigitals->isEmpty())
    <div class="section-card">
        <div class="empty-state">
            <i class="ti ti-database-off" style="color: var(--text-muted);"></i>
            <p style="font-size: 14px; font-weight: 600;">Data belum tersedia</p>
            <p style="font-size: 12px;">Pastikan data kriteria, alternatif, dan penilaian sudah diisi terlebih dahulu.</p>
            <a href="{{ route('kriteria.index') }}" class="btn btn-sm mt-2"
               style="background: var(--accent); color: #0a0f1d; font-weight: 700; border-radius: 8px; border: none; padding: 8px 20px;">
                Kelola Kriteria
            </a>
        </div>
    </div>
@else

{{-- ============================================================
     STEP 1: MATRIKS KEPUTUSAN (X)
     ============================================================ --}}
<div class="section-card">
    <div class="step-pill">
        <span class="step-num">1</span>
        <span class="step-label">Matriks Keputusan (X)</span>
    </div>
    <div class="section-desc">
        Nilai penilaian mentah setiap alternatif terhadap masing-masing kriteria.
        Rumus: <code style="color:var(--accent)">x<sub>ij</sub></code>
    </div>
    <div style="overflow-x:auto;">
    <table class="topsis-table">
        <thead>
            <tr>
                <th style="text-align:left;">Alternatif</th>
                @foreach($kriterias as $k)
                    <th>{{ $k->nama_kriteria }}<br><span style="font-weight:400;font-size:9px;opacity:.7;">({{ strtoupper($k->jenis) }}, W={{ $k->bobot }})</span></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($asetDigitals as $aset)
            <tr>
                <td class="nama-col">{{ $aset->nama_aset }}</td>
                @foreach($kriterias as $k)
                    <td>{{ number_format($matriksX[$aset->id][$k->id] ?? 0, 4) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

{{-- ============================================================
     STEP 2: MATRIKS NORMALISASI (R)
     ============================================================ --}}
<div class="section-card">
    <div class="step-pill">
        <span class="step-num">2</span>
        <span class="step-label">Matriks Normalisasi (R)</span>
    </div>
    <div class="section-desc">
        Setiap nilai dibagi dengan akar jumlah kuadrat kolom kolomnya.
        Rumus: <code style="color:var(--accent)">r<sub>ij</sub> = x<sub>ij</sub> / √(Σx²<sub>j</sub>)</code>
    </div>
    <div style="overflow-x:auto;">
    <table class="topsis-table">
        <thead>
            <tr>
                <th style="text-align:left;">Alternatif</th>
                @foreach($kriterias as $k)
                    <th>{{ $k->nama_kriteria }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($asetDigitals as $aset)
            <tr>
                <td class="nama-col">{{ $aset->nama_aset }}</td>
                @foreach($kriterias as $k)
                    <td>{{ number_format($matriksR[$aset->id][$k->id] ?? 0, 6) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

{{-- ============================================================
     STEP 3: MATRIKS BOBOT TERNORMALISASI (V)
     ============================================================ --}}
<div class="section-card">
    <div class="step-pill">
        <span class="step-num">3</span>
        <span class="step-label">Matriks Terbobot (V)</span>
    </div>
    <div class="section-desc">
        Nilai normalisasi dikalikan dengan bobot kriteria.
        Rumus: <code style="color:var(--accent)">v<sub>ij</sub> = w<sub>j</sub> × r<sub>ij</sub></code>
    </div>
    <div style="overflow-x:auto;">
    <table class="topsis-table">
        <thead>
            <tr>
                <th style="text-align:left;">Alternatif</th>
                @foreach($kriterias as $k)
                    <th>{{ $k->nama_kriteria }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($asetDigitals as $aset)
            <tr>
                <td class="nama-col">{{ $aset->nama_aset }}</td>
                @foreach($kriterias as $k)
                    <td>{{ number_format($matriksV[$aset->id][$k->id] ?? 0, 6) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

{{-- ============================================================
     STEP 4: SOLUSI IDEAL POSITIF & NEGATIF
     ============================================================ --}}
<div class="section-card">
    <div class="step-pill">
        <span class="step-num">4</span>
        <span class="step-label">Solusi Ideal Positif (A+) &amp; Negatif (A-)</span>
    </div>
    <div class="section-desc">
        A+ = nilai terbaik tiap kriteria. A- = nilai terburuk tiap kriteria.
        Untuk kriteria <em>benefit</em>: A+ = max, A- = min. Untuk <em>cost</em>: A+ = min, A- = max.
    </div>
    <div style="overflow-x:auto;">
    <table class="topsis-table">
        <thead>
            <tr>
                <th style="text-align:left;">Solusi</th>
                @foreach($kriterias as $k)
                    <th>{{ $k->nama_kriteria }}<br><span style="font-weight:400;font-size:9px;opacity:.7;">({{ strtoupper($k->jenis) }})</span></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr class="ideal-row ideal-pos">
                <td class="nama-col" style="text-align:left;">A+ (Ideal Positif)</td>
                @foreach($kriterias as $k)
                    <td>{{ number_format($solusiIdealPositif[$k->id] ?? 0, 6) }}</td>
                @endforeach
            </tr>
            <tr class="ideal-row ideal-neg">
                <td class="nama-col" style="text-align:left;">A- (Ideal Negatif)</td>
                @foreach($kriterias as $k)
                    <td>{{ number_format($solusiIdealNegatif[$k->id] ?? 0, 6) }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
    </div>
</div>

{{-- ============================================================
     STEP 5: JARAK & NILAI PREFERENSI + PERINGKAT
     ============================================================ --}}
<div class="section-card">
    <div class="step-pill">
        <span class="step-num">5</span>
        <span class="step-label">Jarak, Nilai Preferensi &amp; Peringkat Akhir</span>
    </div>
    <div class="section-desc">
        D+ = jarak ke solusi ideal positif, D- = jarak ke solusi ideal negatif.
        Rumus: <code style="color:var(--accent)">V<sub>i</sub> = D- / (D+ + D-)</code> &nbsp;|&nbsp;
        Semakin besar V<sub>i</sub>, semakin baik alternatif tersebut.
    </div>
    <div style="overflow-x:auto;">
    <table class="topsis-table">
        <thead>
            <tr>
                <th width="5%">Rank</th>
                <th style="text-align:left;">Alternatif</th>
                <th>D+ (Jarak A+)</th>
                <th>D- (Jarak A-)</th>
                <th>V<sub>i</sub> (Preferensi)</th>
                <th>Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilAkhir as $index => $r)
            @php
                $vi = $r['preferensi'];
                if ($vi >= 0.8)      { $badgeClass = 'strong-buy'; $badgeText = 'Strong Buy'; }
                elseif ($vi >= 0.6)  { $badgeClass = 'buy';        $badgeText = 'Buy'; }
                elseif ($vi >= 0.4)  { $badgeClass = 'hold';       $badgeText = 'Hold'; }
                else                 { $badgeClass = 'avoid';      $badgeText = 'Avoid'; }

                $rankClass = $index === 0 ? 'rank-gold' : ($index === 1 ? 'rank-silver' : ($index === 2 ? 'rank-bronze' : 'rank-other'));
            @endphp
            <tr>
                <td><span class="rank-circle {{ $rankClass }}">{{ $index + 1 }}</span></td>
                <td class="nama-col">{{ strtoupper(str_replace('-', ' ', $r['nama_aset'])) }}</td>
                <td style="font-family:'JetBrains Mono',monospace;font-size:11px;">{{ number_format($r['d_plus'], 6) }}</td>
                <td style="font-family:'JetBrains Mono',monospace;font-size:11px;">{{ number_format($r['d_minus'], 6) }}</td>
                <td style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;color:var(--accent);">{{ number_format($vi, 6) }}</td>
                <td><span class="badge-rec {{ $badgeClass }}">{{ $badgeText }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

@endif
</div>

<script>
    const themeToggleBtn = document.getElementById('themeToggle');
    const themeIcon      = document.getElementById('themeIcon');
    const htmlEl         = document.documentElement;

    function applyTheme(theme) {
        htmlEl.setAttribute('data-theme', theme);
        if (theme === 'light') {
            htmlEl.classList.add('light');
            themeIcon.className = 'ti ti-moon text-primary';
        } else {
            htmlEl.classList.remove('light');
            themeIcon.className = 'ti ti-sun text-warning';
        }
    }

    applyTheme(localStorage.getItem('theme') || 'dark');

    themeToggleBtn.addEventListener('click', () => {
        const next = htmlEl.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', next);
        applyTheme(next);
    });
</script>
@endsection
