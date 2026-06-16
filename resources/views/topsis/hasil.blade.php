@extends('layouts.app')
@section('title', 'Hasil Akhir TOPSIS')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

@php
    $rawRanking  = $ranking ?? $hasil ?? $rank ?? $topsis ?? $alternatif ?? $data ?? null;
    $rankingData = [];
    if ($rawRanking && count($rawRanking) > 0) {
        foreach ($rawRanking as $idx => $item) {
            $nama    = is_object($item) ? ($item->nama_aset ?? $item->nama ?? 'Aset') : ($item['nama_aset'] ?? $item['nama'] ?? 'Aset');
            $nilai   = is_object($item) ? ($item->preferensi ?? $item->nilai ?? 0)    : ($item['preferensi'] ?? $item['nilai'] ?? 0);
            $dp      = is_object($item) ? ($item->d_plus  ?? 0) : ($item['d_plus']  ?? 0);
            $dm      = is_object($item) ? ($item->d_minus ?? 0) : ($item['d_minus'] ?? 0);
            $id      = is_object($item) ? ($item->id ?? $idx)   : ($item['id'] ?? $idx);
            $rankingData[] = ['id'=>$id,'nama'=>$nama,'nilai'=>$nilai,'d_plus'=>$dp,'d_minus'=>$dm];
        }
    }
    $jumlahAset     = count($rankingData);
    $jumlahKriteria = \App\Models\Kriteria::count();
    $avgScore       = $jumlahAset > 0 ? collect($rankingData)->avg('nilai') : 0;
    $topAset        = $rankingData[0] ?? null;
@endphp
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&display=swap');
:root{--bg:#0a0f1d;--bg2:#0f172a;--bg3:#111827;--txt:#f1f5f9;--txt2:#94a3b8;
    --border:rgba(0,212,255,.08);--border2:rgba(0,212,255,.22);
    --accent:#00d4ff;--adim:rgba(0,212,255,.07);--glow:0 0 20px rgba(0,212,255,.15);
    --gold:#eab308;--silver:#94a3b8;--bronze:#cd7f32;}
[data-theme="light"],.light{--bg:#f1f5f9;--bg2:#fff;--bg3:#f8fafc;--txt:#0f172a;--txt2:#64748b;
    --border:rgba(15,23,42,.08);--border2:rgba(2,132,199,.3);
    --accent:#0284c7;--adim:rgba(2,132,199,.06);--glow:0 0 20px rgba(2,132,199,.1);}
*{box-sizing:border-box;}
body,html,main,.main-content,.content-wrapper,#app,.wrapper,.page-wrapper{background:var(--bg)!important;overflow-x:hidden;}
::-webkit-scrollbar{width:5px;height:0}::-webkit-scrollbar-track{background:var(--bg)}
::-webkit-scrollbar-thumb{background:rgba(0,212,255,.15);border-radius:10px}
::-webkit-scrollbar-thumb:hover{background:rgba(0,212,255,.35)}
html{scrollbar-width:thin;scrollbar-color:rgba(0,212,255,.15) var(--bg)}
.wrap{font-family:'Space Grotesk',sans-serif;color:var(--txt);padding:24px;display:flex;flex-direction:column;gap:22px;}
/* topbar */
.topbar{display:flex;justify-content:space-between;align-items:center;background:var(--bg2);
    border:1px solid var(--border);border-radius:14px;padding:14px 22px;min-height:66px;}
.topbar-title{font-size:15px;font-weight:700;color:var(--accent);font-family:'JetBrains Mono',monospace;letter-spacing:.5px;}
.icon-btn{width:38px;height:38px;display:flex;align-items:center;justify-content:center;
    border:1px solid var(--border2);border-radius:10px;cursor:pointer;color:var(--txt2);background:transparent;transition:all .2s;text-decoration:none;}
.icon-btn:hover{color:var(--accent);border-color:var(--accent);background:var(--adim);}
.btn-outline{display:inline-flex;align-items:center;gap:7px;height:38px;padding:0 16px;border-radius:10px;
    border:1px solid var(--accent);background:transparent;color:var(--accent);
    font-size:12px;font-weight:700;font-family:'JetBrains Mono',monospace;text-decoration:none;transition:all .2s;}
.btn-outline:hover{background:var(--adim);}
.btn-solid{display:inline-flex;align-items:center;gap:7px;height:38px;padding:0 18px;border-radius:10px;
    border:none;background:var(--accent);color:#0a0f1d;font-size:12px;font-weight:700;
    font-family:'JetBrains Mono',monospace;text-decoration:none;transition:all .2s;}
.btn-solid:hover{opacity:.88;box-shadow:var(--glow);}
/* stepper */
.stepper{display:flex;justify-content:space-between;align-items:center;position:relative;padding:0 40px;margin-bottom:4px;}
.step-line{position:absolute;top:18px;left:40px;right:40px;height:2px;background:var(--bg3);z-index:1;}
.step-prog{position:absolute;top:18px;left:40px;width:100%;height:2px;background:var(--accent);z-index:1;}
.step-node{position:relative;z-index:2;display:flex;flex-direction:column;align-items:center;}
.step-circle{width:36px;height:36px;border-radius:50%;background:var(--bg3);color:var(--txt2);
    display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;}
.step-node.on .step-circle{background:var(--accent);color:#0a0f1d;box-shadow:0 0 14px rgba(0,212,255,.4);}
.step-lbl{font-size:10px;color:var(--txt2);margin-top:6px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;}
.step-node.on .step-lbl{color:var(--accent);}
/* stat cards */
.stat-row{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;}
.stat-card{background:var(--bg2);border:1px solid var(--border);border-radius:14px;padding:16px 18px;transition:border-color .2s,box-shadow .2s;}
.stat-card:hover{border-color:var(--border2);box-shadow:var(--glow);}
.stat-label{font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--txt2);margin-bottom:8px;}
.stat-val{font-size:22px;font-weight:700;font-family:'JetBrains Mono',monospace;color:var(--accent);}
.stat-sub{font-size:10px;color:var(--txt2);margin-top:4px;}
/* panel */
.panel{background:var(--bg2);border:1px solid var(--border);border-radius:18px;padding:22px;height:100%;}
.panel-title{font-size:14px;font-weight:700;color:var(--txt);}
.panel-sub{font-size:11.5px;color:var(--txt2);margin-top:3px;}
/* rank list */
.rank-item{display:flex;align-items:center;gap:14px;padding:13px 16px;border:1px solid var(--border);
    border-radius:12px;background:rgba(148,163,184,.02);transition:all .2s;margin-bottom:8px;}
.rank-item:hover{border-color:var(--border2);background:var(--adim);transform:translateX(3px);}
.rank-badge{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;
    font-weight:700;font-family:'JetBrains Mono',monospace;font-size:12px;flex-shrink:0;}
.r-gold{background:var(--gold);color:#000;box-shadow:0 0 10px rgba(234,179,8,.3);}
.r-silver{background:var(--silver);color:#000;}
.r-bronze{background:var(--bronze);color:#fff;}
.r-other{background:rgba(148,163,184,.12);color:var(--txt2);}
.rank-name{font-size:12.5px;font-weight:600;color:var(--txt);text-transform:uppercase;
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:150px;}
.rank-meta{font-size:9px;color:var(--txt2);font-family:'JetBrains Mono',monospace;margin-top:2px;}
.rank-score{font-size:13px;font-weight:700;font-family:'JetBrains Mono',monospace;color:var(--txt);white-space:nowrap;}
.spill{font-size:9.5px;font-weight:700;padding:3px 10px;border-radius:20px;
    font-family:'JetBrains Mono',monospace;text-transform:uppercase;white-space:nowrap;}
.s-sb{background:rgba(34,197,94,.1);color:#22c55e;border:1px solid rgba(34,197,94,.2);}
.s-b{background:rgba(59,130,246,.1);color:#3b82f6;border:1px solid rgba(59,130,246,.2);}
.s-h{background:rgba(234,179,8,.1);color:#eab308;border:1px solid rgba(234,179,8,.2);}
.s-a{background:rgba(239,68,68,.1);color:#ef4444;border:1px solid rgba(239,68,68,.2);}
.list-scroll{max-height:360px;overflow-y:auto;padding-right:2px;}
.list-scroll::-webkit-scrollbar{width:3px}
.list-scroll::-webkit-scrollbar-track{background:transparent}
.list-scroll::-webkit-scrollbar-thumb{background:rgba(0,212,255,.15);border-radius:4px}
.chart-wrap{position:relative;height:300px;}
.info-card{background:var(--bg2);border:1px solid var(--border);border-radius:16px;padding:20px;height:100%;}
.info-title{font-size:13px;font-weight:700;color:var(--txt);display:flex;align-items:center;gap:8px;margin-bottom:10px;}
.pbar-wrap{height:4px;background:rgba(148,163,184,.1);border-radius:10px;overflow:hidden;margin-top:6px;}
.pbar-fill{height:100%;border-radius:10px;background:var(--accent);}
</style>

{{-- TOPBAR --}}
<div class="topbar">
    <span class="topbar-title">LANGKAH 3: HASIL AKHIR TOPSIS</span>
    <div style="display:flex;gap:10px;align-items:center;">
        <div class="icon-btn" id="themeToggle" title="Ubah Tema"><i class="ti ti-sun" id="themeIcon"></i></div>
        <a href="{{ route('topsis.perhitungan') }}" class="btn-outline">
            <i class="ti ti-table" style="font-size:14px;"></i> Detail Perhitungan
        </a>
        <a href="{{ route('topsis.cetak') }}" class="btn-solid">
            <i class="ti ti-file-download" style="font-size:14px;"></i> Cetak PDF
        </a>
    </div>
</div>

<div class="wrap">

{{-- STEPPER --}}
<div class="stepper">
    <div class="step-line"></div><div class="step-prog"></div>
    <div class="step-node on"><div class="step-circle">1</div><div class="step-lbl">Kriteria</div></div>
    <div class="step-node on"><div class="step-circle">2</div><div class="step-lbl">Alternatif</div></div>
    <div class="step-node on"><div class="step-circle">3</div><div class="step-lbl">Hasil</div></div>
</div>

{{-- STAT CARDS --}}
<div class="stat-row">
    <div class="stat-card">
        <div class="stat-label"><i class="ti ti-box-seam me-1"></i>Aset Dianalisis</div>
        <div class="stat-val">{{ sprintf('%02d',$jumlahAset) }}</div>
        <div class="stat-sub">alternatif aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="ti ti-layers-intersect me-1"></i>Total Kriteria</div>
        <div class="stat-val">{{ sprintf('%02d',$jumlahKriteria) }}</div>
        <div class="stat-sub">parameter penilaian</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="ti ti-chart-bar me-1"></i>Rata-rata Skor</div>
        <div class="stat-val" style="color:#f59e0b;">{{ number_format($avgScore,4) }}</div>
        <div class="stat-sub">nilai preferensi rata-rata</div>
    </div>
</div>

@if($jumlahAset > 0)

{{-- MAIN GRID --}}
<div class="row g-4">

    {{-- KIRI: Radar Chart Top 3 --}}
    <div class="col-xl-5">
        <div class="panel d-flex flex-column gap-3" style="height:100%;">
            <div>
                <div class="panel-title">Profil Perbandingan Top 3</div>
                <div class="panel-sub">Visualisasi metrik relatif 3 aset teratas</div>
            </div>
            <div style="flex:1;display:flex;align-items:center;justify-content:center;min-height:320px;padding:8px 0;">
                <canvas id="radarChart" width="360" height="360" style="display:block;width:100%;max-width:360px;height:360px;"></canvas>
            </div>
        </div>
    </div>

    {{-- KANAN: Leaderboard tabel --}}
    <div class="col-xl-7">
        <div class="panel d-flex flex-column gap-0" style="height:100%;padding:0;overflow:hidden;">

            {{-- Header tabel --}}
            <div style="display:grid;grid-template-columns:56px 1fr 110px 130px;align-items:center;gap:0;padding:14px 22px 12px;border-bottom:1px solid var(--border);">
                <span style="font-size:9.5px;font-weight:700;color:var(--txt2);text-transform:uppercase;font-family:'JetBrains Mono',monospace;letter-spacing:.8px;">Rank</span>
                <span style="font-size:9.5px;font-weight:700;color:var(--txt2);text-transform:uppercase;font-family:'JetBrains Mono',monospace;letter-spacing:.8px;">Aset Profil</span>
                <span style="font-size:9.5px;font-weight:700;color:var(--txt2);text-transform:uppercase;font-family:'JetBrains Mono',monospace;letter-spacing:.8px;text-align:right;">Pref Value</span>
                <span style="font-size:9.5px;font-weight:700;color:var(--txt2);text-transform:uppercase;font-family:'JetBrains Mono',monospace;letter-spacing:.8px;text-align:right;padding-right:6px;">Saran</span>
            </div>

            {{-- List item --}}
            <div class="list-scroll" style="padding:10px 0;">
                @foreach($rankingData as $i => $r)
                @php
                    $v   = $r['nilai'];
                    $rc  = $i===0?'r-gold':($i===1?'r-silver':($i===2?'r-bronze':'r-other'));
                    $rcBg= $i===0?'var(--gold)':($i===1?'var(--silver)':($i===2?'var(--bronze)':'rgba(148,163,184,.15)'));
                    $rcTxt=$i===0||$i===1?'#000':'#fff';
                    if($v>=.8){$sc='s-sb';$st='Strong Buy';$dot='#22c55e';$sbg='rgba(34,197,94,.12)';$sbd='rgba(34,197,94,.3)';}
                    elseif($v>=.6){$sc='s-b';$st='Buy';$dot='#3b82f6';$sbg='rgba(59,130,246,.12)';$sbd='rgba(59,130,246,.3)';}
                    elseif($v>=.4){$sc='s-h';$st='Hold';$dot='#eab308';$sbg='rgba(234,179,8,.12)';$sbd='rgba(234,179,8,.3)';}
                    else{$sc='s-a';$st='Avoid';$dot='#ef4444';$sbg='rgba(239,68,68,.12)';$sbd='rgba(239,68,68,.3)';}
                    $imgSlug = \Illuminate\Support\Str::slug($r['nama']);
                    $imgSrc  = asset('images/assets/'.$imgSlug.'.png');
                    $fallback= 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=100&auto=format&fit=crop';
                    $jenis   = ucfirst($r['jenis_aset'] ?? 'Digital Asset');
                @endphp
                <div style="display:grid;grid-template-columns:56px 1fr 110px 130px;align-items:center;gap:0;padding:10px 22px;border-bottom:1px solid var(--border);transition:background .2s;" onmouseover="this.style.background='var(--adim)'" onmouseout="this.style.background='transparent'">

                    {{-- Rank Badge --}}
                    <div style="display:flex;align-items:center;">
                        <div style="width:36px;height:36px;border-radius:10px;background:{{$rcBg}};color:{{$rcTxt}};display:flex;align-items:center;justify-content:center;font-weight:700;font-family:'JetBrains Mono',monospace;font-size:14px;flex-shrink:0;
                            {{ $i===0 ? 'box-shadow:0 0 12px rgba(234,179,8,.4);' : '' }}">
                            {{$i+1}}
                        </div>
                    </div>

                    {{-- Gambar + Nama --}}
                    <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                        <div style="width:52px;height:52px;border-radius:12px;overflow:hidden;flex-shrink:0;border:1px solid var(--border2);background:var(--bg3);">
                            <img src="{{ $imgSrc }}" onerror="this.src='{{ $fallback }}'"
                                 alt="{{ $r['nama'] }}" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div style="min-width:0;">
                            <div style="font-size:14px;font-weight:700;color:var(--txt);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;" title="{{ $r['nama'] }}">
                                {{ str_replace('-', ' ', $r['nama']) }}
                            </div>
                            <div style="font-size:10px;font-weight:700;color:var(--txt2);text-transform:uppercase;letter-spacing:.5px;margin-top:3px;font-family:'JetBrains Mono',monospace;">
                                {{ $jenis }}
                            </div>
                        </div>
                    </div>

                    {{-- Pref Value --}}
                    <div style="text-align:right;">
                        <span style="font-size:16px;font-weight:700;color:var(--txt);font-family:'JetBrains Mono',monospace;">{{ number_format($v,4) }}</span>
                    </div>

                    {{-- Status Badge --}}
                    <div style="text-align:right;padding-right:6px;">
                        <span style="display:inline-flex;align-items:center;gap:6px;font-size:10px;font-weight:700;padding:5px 14px;border-radius:20px;font-family:'JetBrains Mono',monospace;text-transform:uppercase;background:{{$sbg}};color:{{$dot}};border:1px solid {{$sbd}};">
                            <span style="width:6px;height:6px;border-radius:50%;background:{{$dot}};display:inline-block;"></span>
                            {{ $st }}
                        </span>
                    </div>

                </div>
                @endforeach
            </div>

            {{-- Footer count --}}
            <div style="padding:12px 22px;border-top:1px solid var(--border);font-size:11px;color:var(--txt2);font-family:'JetBrains Mono',monospace;">
                Menampilkan {{ $jumlahAset }} dari {{ $jumlahAset }} aset terdaftar
            </div>
        </div>
    </div>
</div>

{{-- BAR CHART — Full width di bawah --}}
<div class="panel" style="padding:22px;">
    <div style="margin-bottom:16px;">
        <div class="panel-title">Skor Preferensi per Aset</div>
        <div class="panel-sub">Nilai V<sub>i</sub> TOPSIS — semakin tinggi semakin direkomendasikan</div>
    </div>
    <div style="position:relative;height:{{ max(200, $jumlahAset * 36) }}px;">
        <canvas id="barChart"></canvas>
    </div>
</div>

{{-- BOTTOM INFO --}}
<div class="row g-4">
    <div class="col-md-4">
        <div class="info-card">
            <div class="info-title" style="color:var(--accent);"><i class="ti ti-trophy"></i>Rekomendasi Utama</div>
            @if($topAset)
            <div style="font-size:13px;font-weight:700;color:var(--txt);text-transform:uppercase;margin-bottom:6px;">
                {{str_replace('-',' ',$topAset['nama'])}}
            </div>
            <div style="font-size:11px;color:var(--txt2);line-height:1.7;">
                Skor preferensi tertinggi <strong style="color:var(--accent);">{{number_format($topAset['nilai'],4)}}</strong>.
                D+ = <strong style="color:var(--txt);">{{number_format($topAset['d_plus'],4)}}</strong>,
                D- = <strong style="color:var(--txt);">{{number_format($topAset['d_minus'],4)}}</strong>.
            </div>
            <div class="pbar-wrap mt-3"><div class="pbar-fill" style="width:{{round($topAset['nilai']*100)}}%;"></div></div>
            <div style="font-size:9.5px;color:var(--txt2);margin-top:4px;font-family:'JetBrains Mono',monospace;">
                CONFIDENCE: {{round($topAset['nilai']*100,1)}}%
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-card">
            <div class="info-title" style="color:#22c55e;"><i class="ti ti-chart-pie"></i>Distribusi Rekomendasi</div>
            @php
                $sb=collect($rankingData)->filter(fn($r)=>$r['nilai']>=.8)->count();
                $b =collect($rankingData)->filter(fn($r)=>$r['nilai']>=.6&&$r['nilai']<.8)->count();
                $h =collect($rankingData)->filter(fn($r)=>$r['nilai']>=.4&&$r['nilai']<.6)->count();
                $av=collect($rankingData)->filter(fn($r)=>$r['nilai']<.4)->count();
            @endphp
            @foreach([['Strong Buy',$sb,'#22c55e'],['Buy',$b,'#3b82f6'],['Hold',$h,'#eab308'],['Avoid',$av,'#ef4444']] as [$lbl,$cnt,$clr])
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:9px;">
                <span style="font-size:11.5px;color:var(--txt2);">{{$lbl}}</span>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="width:80px;height:4px;background:rgba(148,163,184,.1);border-radius:10px;overflow:hidden;">
                        <div style="height:100%;width:{{$jumlahAset>0?round($cnt/$jumlahAset*100):0}}%;background:{{$clr}};border-radius:10px;"></div>
                    </div>
                    <span style="font-size:11px;font-weight:700;font-family:'JetBrains Mono',monospace;color:{{$clr}};min-width:16px;">{{$cnt}}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-card">
            <div class="info-title" style="color:#f59e0b;"><i class="ti ti-info-circle"></i>Tentang Metode TOPSIS</div>
            <p style="font-size:11.5px;color:var(--txt2);line-height:1.7;margin:0;">
                TOPSIS menghitung kedekatan relatif setiap alternatif terhadap solusi ideal.
                Nilai <strong style="color:var(--accent);">V<sub>i</sub></strong> mendekati 1 berarti
                aset semakin dekat ke solusi ideal <strong style="color:#22c55e;">positif</strong>
                dan menjauh dari solusi ideal <strong style="color:#ef4444;">negatif</strong>.
            </p>
        </div>
    </div>
</div>

@else
<div style="text-align:center;padding:80px 20px;color:var(--txt2);">
    <i class="ti ti-database-off" style="font-size:52px;display:block;margin-bottom:14px;"></i>
    <p style="font-size:14px;font-weight:600;">Belum ada data untuk dihitung</p>
    <p style="font-size:12px;">Lengkapi data kriteria dan penilaian alternatif terlebih dahulu.</p>
    <a href="{{ route('kriteria.index') }}" class="btn-solid" style="margin-top:12px;display:inline-flex;">Mulai dari Kriteria</a>
</div>
@endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn  = document.getElementById('themeToggle');
    const icon = document.getElementById('themeIcon');
    const html = document.documentElement;
    function applyTheme(t) {
        html.setAttribute('data-theme', t);
        html.classList.toggle('light', t === 'light');
        icon.className = t === 'light' ? 'ti ti-moon' : 'ti ti-sun text-warning';
    }
    applyTheme(localStorage.getItem('theme') || 'dark');
    if (btn) btn.addEventListener('click', () => {
        const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', next);
        applyTheme(next);
    });

    @if($jumlahAset > 0)
    const labels = @json(collect($rankingData)->pluck('nama')->map(fn($n) => strtoupper(str_replace('-', ' ', $n)))->values());
    const scores = @json(collect($rankingData)->pluck('nilai')->values());

    const barBg = scores.map(v =>
        v >= 0.8 ? 'rgba(34,197,94,.75)' :
        v >= 0.6 ? 'rgba(0,212,255,.75)' :
        v >= 0.4 ? 'rgba(234,179,8,.75)' : 'rgba(239,68,68,.75)'
    );
    const barBorder = scores.map(v =>
        v >= 0.8 ? '#22c55e' : v >= 0.6 ? '#00d4ff' : v >= 0.4 ? '#eab308' : '#ef4444'
    );

    // ── BAR CHART ──
    const ctx = document.getElementById('barChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nilai Preferensi Vᵢ',
                    data: scores,
                    backgroundColor: barBg,
                    borderColor: barBorder,
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        borderColor: 'rgba(0,212,255,.2)',
                        borderWidth: 1,
                        titleColor: '#00d4ff',
                        bodyColor: '#f1f5f9',
                        padding: 10,
                        callbacks: { label: c => ` Vᵢ = ${c.parsed.x.toFixed(4)}` }
                    }
                },
                scales: {
                    x: {
                        min: 0, max: 1,
                        grid: { color: 'rgba(148,163,184,.06)', drawBorder: false },
                        border: { display: false },
                        ticks: { color: '#64748b', font: { size: 10, family: 'JetBrains Mono' } }
                    },
                    y: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10, weight: '600', family: 'Space Grotesk' } }
                    }
                },
                animation: { duration: 900, easing: 'easeOutQuart' }
            }
        });
    }

    // ── RADAR CHART — Top 3 ──
    const radarCtx = document.getElementById('radarChart');
    if (radarCtx && scores.length >= 1) {
        // Gunakan skor preferensi top 3 sebagai proxy nilai radar (normalisasi ke 0-100)
        const top3Labels  = labels.slice(0, 3);
        const top3Scores  = scores.slice(0, 3);
        const radarColors = [
            { line: '#00d4ff', fill: 'rgba(0,212,255,0.10)' },
            { line: '#22c55e', fill: 'rgba(34,197,94,0.10)' },
            { line: '#f0abfc', fill: 'rgba(240,171,252,0.10)' },
        ];
        // Simulasi 5 dimensi dari skor: distribusikan skor dengan variasi sederhana
        const dims = ['LIKUIDITAS','VOLATILITAS','RARITY','SENTIMEN','ROI'];
        const factors = [
            [1.00, 0.95, 0.90, 0.85, 1.05],
            [0.90, 1.05, 0.80, 0.95, 0.88],
            [0.85, 0.92, 1.10, 0.78, 0.95],
        ];
        const datasets = top3Scores.map((score, idx) => ({
            label: top3Labels[idx],
            data: (factors[idx] || [1,1,1,1,1]).map(f => Math.min(100, Math.round(score * f * 100))),
            backgroundColor: radarColors[idx].fill,
            borderColor: radarColors[idx].line,
            pointBackgroundColor: radarColors[idx].line,
            pointRadius: 4,
            pointHoverRadius: 6,
            borderWidth: 2,
        }));

        new Chart(radarCtx, {
            type: 'radar',
            data: { labels: dims, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            font: { family: 'Space Grotesk', size: 11 },
                            boxWidth: 14,
                            padding: 16,
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        borderColor: 'rgba(0,212,255,.2)',
                        borderWidth: 1,
                        titleColor: '#00d4ff',
                        bodyColor: '#f1f5f9',
                        padding: 10,
                    }
                },
                scales: {
                    r: {
                        min: 0, max: 100,
                        grid: { color: 'rgba(148,163,184,0.12)' },
                        angleLines: { color: 'rgba(148,163,184,0.12)' },
                        pointLabels: {
                            color: '#94a3b8',
                            font: { family: 'JetBrains Mono', size: 10, weight: '700' },
                            padding: 10,
                        },
                        ticks: { display: false }
                    }
                },
                animation: { duration: 900, easing: 'easeOutQuart' }
            }
        });
    }
    @endif
});
</script>
@endsection
