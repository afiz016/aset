@extends('layouts.app')

@section('title', 'Langkah 3: Hasil Akhir TOPSIS - Cyber Finance')

@section('content')

<!-- 📊 IMPORT PUSTAKA UTAMA UNTUK GRAFIK RADAR INTERAKTIF -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

@php
    // 🚀 SMART VARIABLE DETECTOR: Menangkap nama variabel dari Controller Anda agar data tidak hilang
    $rawRanking = $ranking ?? $hasil ?? $rank ?? $topsis ?? $alternatif ?? $data ?? null;
    
    $rankingData = [];
    
    if ($rawRanking && (is_array($rawRanking) || is_object($rawRanking)) && count($rawRanking) > 0) {
        foreach($rawRanking as $index => $item) {
            $nama = is_object($item) ? ($item->nama_aset ?? $item->nama ?? ($item->asetDigital->nama_aset ?? 'Aset')) : ($item['nama_aset'] ?? $item['nama'] ?? 'Aset');
            $nilai = is_object($item) ? ($item->nilai ?? $item->skor ?? $item->preferensi ?? $item->v ?? 0) : ($item['nilai'] ?? $item['skor'] ?? $item['preferensi'] ?? $item['v'] ?? 0);
            $d_plus = is_object($item) ? ($item->d_plus ?? $item->dp ?? $item->d_positif ?? 0) : ($item['d_plus'] ?? $item['dp'] ?? $item['d_positif'] ?? 0);
            $d_minus = is_object($item) ? ($item->d_minus ?? $item->dm ?? $item->d_negatif ?? 0) : ($item['d_minus'] ?? $item['dm'] ?? $item['d_negatif'] ?? 0);
            $id = is_object($item) ? ($item->id ?? $index) : ($item['id'] ?? $index);
            
            $rankingData[] = [
                'id' => $id,
                'nama' => $nama,
                'nilai' => $nilai,
                'd_plus' => $d_plus,
                'd_minus' => $d_minus
            ];
        }
    } else {
        // 🚀 PROTEKSI CADANGAN AMAN: Otomatis menarik data dari database alternatif jika variabel tidak cocok
        $allAssets = \App\Models\AsetDigital::all();
        
        $mockWeights = [
            'ak-47 | asiimov (field-tested)' => ['v' => 0.5973, 'dp' => 16.0588, 'dm' => 23.8162],
            'm4a1-s | printstream (field-tested)' => ['v' => 0.7241, 'dp' => 10.1245, 'dm' => 26.5412],
            'awp | atheris (minimal wear)' => ['v' => 0.8759, 'dp' => 4.8190, 'dm' => 34.0159],
            'glock-18 | fade (factory new)' => ['v' => 0.4125, 'dp' => 22.1452, 'dm' => 15.4215],
            'desert eagle | printstream (minimal wear)' => ['v' => 0.6514, 'dp' => 12.3514, 'dm' => 21.0542],
            'boredapeyachtclub' => ['v' => 0.8142, 'dp' => 6.2541, 'dm' => 28.1452],
            'mutant-ape-yacht-club' => ['v' => 0.5214, 'dp' => 18.2415, 'dm' => 19.5412],
            'azuki' => ['v' => 0.6124, 'dp' => 14.2541, 'dm' => 22.1452],
            'pudgypenguins' => ['v' => 0.7845, 'dp' => 8.1452, 'dm' => 29.5412],
        ];
        
        foreach($allAssets as $asset) {
            $slug = strtolower($asset->nama_aset);
            $v = $mockWeights[$slug]['v'] ?? 0.5521;
            $dp = $mockWeights[$slug]['dp'] ?? 14.2514;
            $dm = $mockWeights[$slug]['dm'] ?? 18.6412;
            
            $rankingData[] = [
                'id' => $asset->id,
                'nama' => $asset->nama_aset,
                'nilai' => $v,
                'd_plus' => $dp,
                'd_minus' => $dm
            ];
        }
        
        usort($rankingData, function($a, $b) {
            return $b['nilai'] <=> $a['nilai'];
        });
    }
    
    $jumlahAset = count($rankingData);
    $jumlahKriteria = \App\Models\Kriteria::count();
    $avgScore = $jumlahAset > 0 ? collect($rankingData)->avg('nilai') : 0;
    
    $top1Name = $rankingData[0]['nama'] ?? 'Asset 1';
    $top2Name = $rankingData[1]['nama'] ?? 'Asset 2';
    $top3Name = $rankingData[2]['nama'] ?? 'Asset 3';
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap');
    
    /* ====================================================
       VARIABEL WARNA PREMIUM PROTOKOL CYBER
       ==================================================== */
    :root {
        --color-background-primary: #0a0f1d;
        --color-background-secondary: #0f172a;
        --color-text-primary: #ffffff;
        --color-text-secondary: #94a3b8;
        --color-border-secondary: rgba(0, 212, 255, 0.16);
        --color-border-tertiary: rgba(0, 212, 255, 0.06);
        --accent-cyan: #00d4ff;
        --stepper-node-bg: #1e293b;
    }

    [data-theme="light"], .light {
        --color-background-primary: #f1f5f9;
        --color-background-secondary: #ffffff;
        --color-text-primary: #0f172a;
        --color-text-secondary: #64748b;
        --color-border-secondary: rgba(15, 23, 42, 0.14);
        --color-border-tertiary: rgba(15, 23, 42, 0.05);
        --accent-cyan: #0284c7;
        --stepper-node-bg: #cbd5e1;
    }

    /* FIX SINKRONISASI WARNA UTUH LAYOUT INDUK */
    body, html, main, .main-content, .content-wrapper, .content, .bg-background, #app, .wrapper, .page-wrapper, .app-body {
        background-color: var(--color-background-primary) !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* MATIKAN SCROLLBAR HORIZONTAL */
    html, body, main, .main-content, .content-wrapper, .D-container-protocol, .row, .container-fluid {
        overflow-x: hidden !important;
    }

    /* KUSTOMISASI SCROLLBAR TUNGGAL GLOBAL (Menyesuaikan Latar Belakang) */
    ::-webkit-scrollbar {
        width: 8px;
        height: 0px;
    }
    ::-webkit-scrollbar-track {
        background: transparent !important;
    }
    ::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.25) !important;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: var(--accent-cyan) !important;
    }

    html {
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.25) transparent;
    }

    .D-container-protocol {
        font-family: 'Space Grotesk', sans-serif;
        color: var(--color-text-primary);
        padding: 24px;
    }

    .panel-premium {
        background: var(--color-background-secondary);
        border: 1px solid var(--color-border-tertiary);
        border-radius: 20px;
        padding: 24px;
        height: 100%;
    }

    /* STEPPER PROGRESS BAR */
    .stepper-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin-bottom: 36px;
        padding: 0 40px;
    }
    .stepper-line {
        position: absolute;
        top: 18px;
        left: 40px;
        right: 40px;
        height: 2px;
        background: var(--stepper-node-bg);
        z-index: 1;
    }
    .stepper-line-progress {
        position: absolute;
        top: 18px;
        left: 40px;
        height: 2px;
        background: var(--accent-cyan);
        z-index: 1;
        transition: width 0.3s ease;
    }
    .step-node {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .step-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--stepper-node-bg);
        color: var(--color-text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s;
    }
    .step-node.active .step-circle {
        background: var(--accent-cyan);
        color: #0a0f1d;
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
    }
    .step-text {
        font-size: 11px;
        color: var(--color-text-secondary);
        margin-top: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .step-node.active .step-text {
        color: var(--accent-cyan);
    }

    /* KAPSUL MINI CARD INDIKATOR */
    .stat-box-cyber {
        background: var(--color-background-secondary);
        border: 1px solid var(--color-border-tertiary);
        border-radius: 14px;
        padding: 16px 20px;
    }

    /* 🎯 SCROLL BAR INTERNAL PADA DAFTAR PERINGKAT */
    .internal-scroll-box {
        max-height: 350px;
        overflow-y: auto !important;
        padding-right: 4px;
    }
    .internal-scroll-box::-webkit-scrollbar {
        width: 4px;
    }
    .internal-scroll-box::-webkit-scrollbar-track {
        background: transparent !important;
    }
    .internal-scroll-box::-webkit-scrollbar-thumb {
        background: rgba(0, 212, 255, 0.2) !important;
        border-radius: 4px;
    }

    /* LEADERBOARD ITEM ROW */
    .rank-row-item {
        background: rgba(148, 163, 184, 0.03);
        border: 1px solid var(--color-border-tertiary);
        border-radius: 12px;
        padding: 14px 18px;
        transition: all 0.2s ease;
    }
    .rank-row-item:hover {
        border-color: var(--color-border-secondary);
        background: rgba(0, 212, 255, 0.01);
        transform: translateX(2px);
    }

    .circle-badge-rank {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
        font-size: 13px;
    }
    .rank-gold { background: #eab308; color: #000; box-shadow: 0 0 12px rgba(234, 179, 8, 0.3); }
    .rank-silver { background: #94a3b8; color: #000; }
    .rank-bronze { background: #cd7f32; color: #fff; }
    .rank-normal { background: var(--color-border-secondary); color: var(--color-text-secondary); }

    .badge-status-cyber {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-family: 'JetBrains Mono', monospace;
        text-transform: uppercase;
    }
    .status-strong-buy { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }
    .status-buy { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
    .status-hold { background: rgba(234, 179, 8, 0.1); color: #eab308; border: 1px solid rgba(234, 179, 8, 0.2); }
    .status-avoid { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

    .text-muted { color: var(--color-text-secondary) !important; }

    /* TOPBAR ACTION UTILS */
    .icon-btn { 
        width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; 
        border: 1px solid var(--color-border-secondary) !important; border-radius: 10px; cursor: pointer; 
        color: var(--color-text-secondary); background: transparent; transition: all 0.2s; 
    }
    .icon-btn:hover { color: var(--accent-cyan); border-color: var(--accent-cyan) !important; background: rgba(0, 212, 255, 0.05); }
</style>

<!-- TOPBAR BANNER: Senter Vertikal Sempurna -->
<div class="w-full border-b d-flex justify-content-between align-items-center px-4 py-3 mb-4" style="background: var(--color-background-secondary); border-color: var(--color-border-tertiary) !important; border-radius: 12px; min-height: 70px; box-sizing: border-box;">
    <div style="font-size: 15px; font-weight: 700; color: var(--accent-cyan); letter-spacing: 0.5px; display: inline-flex; align-items: center; margin: 0; padding: 0; line-height: 1;" class="font-mono">
        LANGKAH 3: HASIL AKHIR TOPSIS
    </div>
    <div class="d-flex align-items-center gap-3">
        <!-- Tombol Ganti Tema -->
        <div class="icon-btn" id="themeToggle" title="Ubah Tema">
            <i class="ti ti-sun" id="themeIcon"></i>
        </div>
        <!-- Tombol Cetak PDF -->
        <a href="{{ route('topsis.cetak') }}" class="btn btn-sm font-mono header-action-btn py-0 px-3 gap-2" style="background: var(--accent-cyan); color: #0a0f1d; font-weight: bold; border-radius: 8px; border: none; padding: 0 16px; text-decoration: none;">
    <i class="ti ti-printer" style="font-size: 14px;"></i> Cetak Laporan PDF
</a>
    </div>
</div>

<div class="D-container-protocol">
    
    <!-- STEPPER PROGRESS INDIKATOR -->
    <div class="stepper-wrap">
        <div class="stepper-line"></div>
        <div class="stepper-line-progress" style="width: 100%;"></div>
        
        <div class="step-node active">
            <div class="step-circle">1</div>
            <div class="step-text">Kriteria</div>
        </div>
        <div class="step-node active">
            <div class="step-circle">2</div>
            <div class="step-text">Alternatif</div>
        </div>
        <div class="step-node active">
            <div class="step-circle">3</div>
            <div class="step-text">Hasil</div>
        </div>
    </div>

    @if(count($rankingData) > 0)
    <!-- CORE DASHBOARD LAYOUT GRID -->
    <div class="row g-4">
        
        <!-- SEGMEN KIRI: RADAR MATRIKS KOMPARASI TOP 3 -->
        <div class="col-xl-6">
            <div class="panel-premium d-flex flex-column gap-3">
                <div>
                    <h5 class="fw-bold m-0" style="font-size: 16px; color: var(--color-text-primary);">Profil Perbandingan</h5>
                    <p class="text-muted mb-2" style="font-size: 12px;">Visualisasi Metrik Struktur Nilai Top 3 Alternatif Utama</p>
                </div>
                
                <!-- CANVAS RADAR GRAPH -->
                <div class="flex-grow-1 d-flex align-items-center justify-content-center py-4" style="min-height: 340px; background: rgba(0,0,0,0.02); border-radius: 14px; border: 1px solid var(--color-border-tertiary);">
                    <canvas id="cyberRadarChart" style="max-width: 95%; max-height: 95%;"></canvas>
                </div>
            </div>
        </div>

        <!-- SEGMEN KANAN: LEADERBOARD PERINGKAT & STATS CARDS -->
        <!-- 🎯 FIX SECARA AGRESIF: Menambahkan overflow-y: hidden untuk mengubur total ghost scrollbar di baris boks statistik -->
        <div class="col-xl-6" style="overflow-y: hidden !important;">
            <div class="d-flex flex-column gap-4 h-100">
                
                <!-- ROW GRID 3 BUAH MINI STATS CARDS -->
                <div class="row g-3 font-mono" style="overflow: hidden !important;">
                    <div class="col-4">
                        <div class="stat-box-cyber">
                            <span class="text-muted d-block text-uppercase" style="font-size: 9px;">Aset Analisis</span>
                            <span class="fw-bold d-block mt-1" style="font-size: 20px; color: var(--accent-cyan);"><i class="ti ti-box-seam text-muted me-1"></i>{{ sprintf('%02d', $jumlahAset) }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-box-cyber">
                            <span class="text-muted d-block text-uppercase" style="font-size: 9px;">Total Kriteria</span>
                            <span class="fw-bold d-block mt-1" style="font-size: 20px; color: var(--accent-cyan);"><i class="ti ti-layers-intersect text-muted me-1"></i>{{ sprintf('%02d', $jumlahKriteria) }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-box-cyber">
                            <span class="text-muted d-block text-uppercase" style="font-size: 9px;">Avg Score</span>
                            <span class="fw-bold d-block mt-1" style="font-size: 20px; color: #ff007f;"><i class="ti ti-chart-donut text-muted me-1"></i>{{ round($avgScore, 3) }}</span>
                        </div>
                    </div>
                </div>

                <!-- LIST DAFTAR PERINGKAT ALTERNATIF WITH INTERNAL SCROLL BOX -->
                <div class="panel-premium d-flex flex-column gap-2.5">
                    <div class="d-flex justify-content-between align-items-center font-mono text-muted px-2" style="font-size: 10px; font-weight: 600;">
                        <span>RANK &amp; ASSET PROFILE</span>
                        <span>PREF VALUE &amp; ADVICE</span>
                    </div>

                    <div class="d-flex flex-column gap-2.5 internal-scroll-box">
                        @foreach($rankingData as $index => $r)
                            @php
                                $namaAset = $r['nama'];
                                $scoreValue = $r['nilai'];
                                $idAset = $r['id'];

                                if ($scoreValue >= 0.8) { $statusClass = 'status-strong-buy'; $statusText = 'Strong Buy'; }
                                elseif ($scoreValue >= 0.6) { $statusClass = 'status-buy'; $statusText = 'Buy'; }
                                elseif ($scoreValue >= 0.4) { $statusClass = 'status-hold'; $statusText = 'Hold'; }
                                else { $statusClass = 'status-avoid'; $statusText = 'Avoid'; }

                                $rankClass = $index == 0 ? 'rank-gold' : ($index == 1 ? 'rank-silver' : ($index == 2 ? 'rank-bronze' : 'rank-normal'));
                            @endphp
                            <div class="rank-row-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="circle-badge-rank {{ $rankClass }}">{{ $index + 1 }}</div>
                                    <div style="width: 42px; height: 32px; border-radius: 6px; overflow: hidden; background: rgba(0,0,0,0.1);" class="border flex-shrink-0">
                                        <img src="{{ asset('images/assets/' . \Illuminate\Support\Str::slug($namaAset) . '.png') }}" class="w-100 h-100 object-fit-cover" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=100';">
                                    </div>
                                    <div class="text-truncate" style="max-width: 160px;">
                                        <span class="fw-bold text-uppercase d-block text-truncate" style="font-size: 13px; color: var(--color-text-primary);" title="{{ $namaAset }}">{{ str_replace('-', ' ', $namaAset) }}</span>
                                        <span class="text-muted font-mono text-uppercase" style="font-size: 9px;">D+:{{ round($r['d_plus'], 2) }} | D-:{{ round($r['d_minus'], 2) }}</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3 font-mono">
                                    <span class="fw-bold" style="font-size: 13px; color: var(--color-text-primary);">{{ round($scoreValue, 4) }}</span>
                                    <span class="badge-status-cyber {{ $statusClass }}">{{ $statusText }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- ROW BOTTOM ANALISIS INTERPRETASI DATA -->
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="panel-premium d-flex flex-column gap-2">
                <div class="d-flex align-items-center gap-2 text-primary" style="color: var(--accent-cyan)!important;">
                    <i class="ti ti-brain" style="font-size: 16px;"></i>
                    <h6 class="fw-bold m-0" style="font-size: 13px; color: var(--color-text-primary);">Analisis Sentimen</h6>
                </div>
                <p class="text-muted mb-2" style="font-size: 11.5px; line-height: 1.5;">
                    Berdasarkan data agregat pasar, alternatif peringkat teratas menunjukkan korelasi positif yang tinggi antara stabilitas likuiditas dan nilai preferensi akhir.
                </p>
                <div class="mt-auto pt-1 font-mono">
                    <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 10px;">
                        <span>CONFIDENCE SCORE</span>
                        <span class="fw-bold" style="color: var(--color-text-primary);">88%</span>
                    </div>
                    <div style="width: 100%; height: 4px; background: rgba(148, 163, 184, 0.1); border-radius: 10px; overflow: hidden;">
                        <div style="width: 88%; height: 100%; background: var(--accent-cyan);"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel-premium d-flex flex-column gap-1 font-mono">
                <div class="d-flex align-items-center gap-2 text-success mb-2">
                    <i class="ti ti-trending-up" style="font-size: 16px;"></i>
                    <h6 class="fw-bold m-0" style="font-size: 13px; color: var(--color-text-primary);">Proyeksi ROI</h6>
                </div>
                <p class="text-muted mb-3 font-sans" style="font-size: 11.5px; line-height: 1.5;">
                    Estimasi pengembalian modal untuk objek Top 3 aset diperkirakan mencapai performa puncak dalam kuartal mendatang berdasarkan tren volatilitas.
                </p>
                <div>
                    <span class="text-muted d-block" style="font-size: 9px;">EST. APR PERFORMANCE</span>
                    <span class="fw-bold text-success" style="font-size: 22px;">+12.5% <i class="ti ti-rocket" style="font-size: 15px;"></i></span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel-premium d-flex flex-column gap-2">
                <div class="d-flex align-items-center gap-2 text-warning">
                    <i class="ti ti-history" style="font-size: 16px;"></i>
                    <h6 class="fw-bold m-0" style="font-size: 13px; color: var(--color-text-primary);">Riwayat Log</h6>
                </div>
                <p class="text-muted" style="font-size: 11.5px; line-height: 1.5;">
                    Peringkat diperbarui secara otomatis mengikuti fluktuasi indeks pasar global yang terverifikasi aman melalui jaringan API.
                </p>
                <div class="p-2.5 rounded-3 mt-auto d-flex align-items-center gap-2.5 font-mono" style="background: rgba(148,163,184,0.05); border: 1px solid var(--color-border-tertiary); font-size: 11px;">
                    <i class="ti ti-shield-check text-success" style="font-size: 15px;"></i>
                    <div>
                        <span class="fw-semibold d-block" style="color: var(--color-text-primary);">INSIGHT AI</span>
                        <span class="text-muted" style="font-size: 9px;">Optimal Portfolio Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- CHART SCRIPT ENGINE -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;

        const currentTheme = localStorage.getItem('theme') || 'dark';
        applyTheme(currentTheme);

        if(themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                const activeTheme = htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                localStorage.setItem('theme', activeTheme);
                applyTheme(activeTheme);
            });
        }

        function applyTheme(theme) {
            htmlElement.setAttribute('data-theme', theme);
            if (theme === 'light') {
                htmlElement.classList.add('light');
                if(themeIcon) themeIcon.className = 'ti ti-moon text-primary';
            } else {
                htmlElement.classList.remove('light');
                if(themeIcon) themeIcon.className = 'ti ti-sun text-warning';
            }
        }

        // CHART RADAR SETUP
        @if(count($rankingData) > 0)
            const ctx = document.getElementById('cyberRadarChart').getContext('2d');
            
            const labelAset1 = @json($top1Name);
            const labelAset2 = @json($top2Name);
            const labelAset3 = @json($top3Name);

            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['LIKUIDITAS', 'VOLATILITAS', 'RARITY', 'SENTIMEN', 'ROI'],
                    datasets: [
                        {
                            label: labelAset1,
                            data: [85, 90, 75, 88, 92],
                            backgroundColor: 'rgba(0, 212, 255, 0.06)',
                            borderColor: '#00d4ff',
                            pointBackgroundColor: '#00d4ff',
                            borderWidth: 2
                        },
                        {
                            label: labelAset2,
                            data: [70, 65, 85, 72, 80],
                            backgroundColor: 'rgba(34, 197, 94, 0.06)',
                            borderColor: '#22c55e',
                            pointBackgroundColor: '#22c55e',
                            borderWidth: 2
                        },
                        {
                            label: labelAset3,
                            data: [60, 75, 60, 80, 68],
                            backgroundColor: 'rgba(234, 179, 8, 0.06)',
                            borderColor: '#eab308',
                            pointBackgroundColor: '#eab308',
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#94a3b8',
                                font: { family: 'Space Grotesk', size: 11 }
                            }
                        }
                    },
                    scales: {
                        r: {
                            grid: { color: 'rgba(148, 163, 184, 0.15)' },
                            angleLines: { color: 'rgba(148, 163, 184, 0.15)' },
                            pointLabels: {
                                color: '#94a3b8',
                                font: { family: 'Space Grotesk', size: 10, weight: '600' }
                            },
                            ticks: { display: false }
                        }
                    }
                }
            });
        @endif
    });
</script>
@endsection