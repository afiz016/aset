@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')

<style>
    /* ====================================================
       TEMA & VARIABEL WARNA (DARK/LIGHT MODE)
       ==================================================== */
    :root {
        --bg-main: #0f172a; --text-main: #ffffff; --text-muted: #94a3b8;
        --card-bg: rgba(255, 255, 255, 0.02); --card-border: rgba(255, 255, 255, 0.05);
        --card-hover-bg: rgba(255, 255, 255, 0.04);
        --card-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(0, 212, 255, 0.1);
        --banner-bg: linear-gradient(135deg, rgba(0, 212, 255, 0.15) 0%, rgba(26, 33, 62, 0.8) 100%);
        --list-bg: rgba(255, 255, 255, 0.03); 
        
        /* TICKER COLORS DARK */
        --ticker-bg: linear-gradient(90deg, #0b1121 0%, #1e293b 50%, #0b1121 100%);
        --ticker-border: #00d4ff;
    }

    [data-theme="light"] {
        --bg-main: #f8fafc; --text-main: #0f172a; --text-muted: #64748b;
        --card-bg: rgba(255, 255, 255, 1); --card-border: rgba(0, 0, 0, 0.08);
        --card-hover-bg: #ffffff;
        --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.05), 0 0 15px rgba(0, 212, 255, 0.15);
        --banner-bg: linear-gradient(135deg, rgba(0, 212, 255, 0.15) 0%, rgba(255, 255, 255, 0.9) 100%);
        --list-bg: rgba(0, 0, 0, 0.02); 
        
        /* TICKER COLORS LIGHT */
        --ticker-bg: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 50%, #f8fafc 100%);
        --ticker-border: #00d4ff;
    }

    .dashboard-wrapper {
        background-color: var(--bg-main); color: var(--text-main);
        transition: background-color 0.5s ease, color 0.5s ease;
        min-height: 100vh; position: relative; overflow-x: hidden;
    }


    /* --- 1. LIVE TICKER ANIMATION (VERSI JUMBO & CENTERED) --- */
    .ticker-wrap {
        width: 100%; overflow: hidden; 
        background: var(--ticker-bg);
        border-bottom: 3px solid var(--ticker-border); 
        border-top: 1px solid rgba(0, 212, 255, 0.2); 
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5), 0 0 25px rgba(0, 212, 255, 0.2);
        padding: 25px 0; /* Padding ideal */
        white-space: nowrap; 
        position: relative;
        z-index: 10;
        display: flex;           /* Kunci agar teks di tengah */
        align-items: center;     /* Kunci agar teks di tengah */
    }
    .ticker-content {
        display: inline-flex;    /* Flexbox untuk menyejajarkan teks & ikon */
        align-items: center; 
        animation: ticker 40s linear infinite; 
        font-size: 1.25rem; 
        font-weight: 800; 
        color: var(--text-main);
        letter-spacing: 1.5px;
    }
    .ticker-content:hover { animation-play-state: paused; cursor: pointer; }
    @keyframes ticker { 0% { transform: translateX(100vw); } 100% { transform: translateX(-100%); } }
    
    .ticker-item { 
        display: inline-flex;   /* Memastikan ikon dan teks sejajar rapi */
        align-items: center; 
        padding: 0 4rem; 
    }

    /* --- 2. AMBIENT GLOW --- */
    .ambient-glow {
        position: absolute; top: -10%; right: -5%; width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(0,212,255,0.08) 0%, transparent 70%);
        border-radius: 50%; z-index: 0; pointer-events: none;
        animation: pulseGlow 8s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
    }
    @keyframes pulseGlow { 0% { transform: scale(0.8); opacity: 0.5; } 100% { transform: scale(1.2); opacity: 1; } }

    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
    .animate-entrance { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; z-index: 1; position: relative; }

    .welcome-banner { background: var(--banner-bg); border: 1px solid rgba(0, 212, 255, 0.2); border-radius: 24px; position: relative; overflow: hidden; }
    .welcome-banner::before { content: ''; position: absolute; top: 0; left: 0; width: 6px; height: 100%; background: linear-gradient(180deg, #00d4ff 0%, #4dabf7 100%); }

    .modern-widget {
        background: var(--card-bg); backdrop-filter: blur(16px);
        border: 1px solid var(--card-border); border-radius: 16px;
        transition: all 0.3s ease; color: var(--text-main); display: flex; flex-direction: column;
    }
    .modern-widget:hover { 
        transform: translateY(-4px); 
        border-color: rgba(0, 212, 255, 0.3); 
        box-shadow: 0 8px 24px rgba(0, 212, 255, 0.1);
    }

    .widget-header-icon { width: 45px; height: 45px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.2rem; }
    .icon-gold { background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.2); }
    .icon-cyan { background: rgba(0, 212, 255, 0.15); color: #00d4ff; border: 1px solid rgba(0, 212, 255, 0.2); }
    .icon-blue { background: rgba(13, 110, 253, 0.15); color: #4dabf7; border: 1px solid rgba(13, 110, 253, 0.2); }

    .score-display { font-size: 3rem; font-weight: 800; line-height: 1; background: linear-gradient(90deg, #ffc107, #ff9800); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    
    .activity-list { padding: 0; margin: 0; list-style: none; }
    .activity-item { background: var(--list-bg); border-radius: 10px; padding: 12px; margin-bottom: 10px; display: flex; align-items: center; font-size: 0.85rem; }
    .activity-dot { width: 10px; height: 10px; border-radius: 50%; margin-right: 12px; flex-shrink: 0; }

    .btn-interactive { border-radius: 12px; font-weight: 600; padding: 10px 20px; transition: all 0.2s ease; }
    .btn-interactive:hover { transform: translateY(-2px); }

    .theme-toggle-btn { background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-main); transition: all 0.2s ease; }
    .theme-toggle-btn:hover { background: rgba(0, 212, 255, 0.1); border-color: #00d4ff; }
    .mini-stat { background: rgba(255, 255, 255, 0.05); border: 1px solid var(--card-border); border-radius: 16px; padding: 12px 20px; }

    /* --- 3. FLOATING ACTION BUTTON (FAB) --- */
    .fab-btn {
        position: fixed; bottom: 40px; right: 40px; width: 65px; height: 65px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #00d4ff, #4dabf7); color: #000;
        box-shadow: 0 10px 25px rgba(0, 212, 255, 0.4); z-index: 1050;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: none; cursor: pointer;
    }
    .fab-btn:hover { transform: scale(1.1) rotate(90deg); box-shadow: 0 15px 35px rgba(0, 212, 255, 0.6); }

    .modal-content-custom { background: var(--bg-main); color: var(--text-main); border: 1px solid var(--card-border); border-radius: 20px; box-shadow: var(--card-shadow); }
    .modal-header-custom { border-bottom: 1px solid var(--card-border); }
</style>

<div class="dashboard-wrapper d-flex flex-column">

    <div class="ticker-wrap">
        <div class="ticker-content">
            <div class="ticker-item"><i class="bi bi-currency-bitcoin text-warning fs-3 me-2"></i> Bitcoin (BTC) <span class="ms-3">$64,230</span> <span class="text-success ms-3"><i class="bi bi-caret-up-fill me-1"></i>2.4%</span></div>
            <div class="ticker-item"><i class="bi bi-gem text-info fs-3 me-2"></i> CS:GO Dragon Lore <span class="ms-3">$8,500</span> <span class="text-success ms-3"><i class="bi bi-caret-up-fill me-1"></i>0.5%</span></div>
            <div class="ticker-item"><i class="bi bi-box text-secondary fs-3 me-2"></i> Ethereum (ETH) <span class="ms-3">$3,450</span> <span class="text-danger ms-3"><i class="bi bi-caret-down-fill me-1"></i>1.2%</span></div>
            <div class="ticker-item"><i class="bi bi-controller text-primary fs-3 me-2"></i> Axie Infinity (AXS) <span class="ms-3">$45.20</span> <span class="text-success ms-3"><i class="bi bi-caret-up-fill me-1"></i>5.1%</span></div>
            
            <div class="ticker-item"><i class="bi bi-currency-bitcoin text-warning fs-3 me-2"></i> Bitcoin (BTC) <span class="ms-3">$64,230</span> <span class="text-success ms-3"><i class="bi bi-caret-up-fill me-1"></i>2.4%</span></div>
            <div class="ticker-item"><i class="bi bi-gem text-info fs-3 me-2"></i> CS:GO Dragon Lore <span class="ms-3">$8,500</span> <span class="text-success ms-3"><i class="bi bi-caret-up-fill me-1"></i>0.5%</span></div>
        </div>
    </div>

    <div class="p-4 p-lg-5 flex-grow-1 position-relative">
        <div class="ambient-glow"></div>
        <div class="container z-index-1 position-relative">
            
            <div class="d-flex justify-content-end mb-4 animate-entrance" style="animation-delay: 0.05s;">
                <button id="themeToggle" class="btn theme-toggle-btn rounded-pill px-4 py-2 shadow-sm fw-bold">
                    <i class="bi bi-sun-fill text-warning me-2" id="themeIcon"></i> <span id="themeText">Mode Terang</span>
                </button>
            </div>

            <div class="row mb-5 animate-entrance" style="animation-delay: 0.1s;">
                <div class="col-12">
                    <div class="welcome-banner p-4 p-md-5 shadow-lg d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                        <div class="mb-4 mb-lg-0">
                            <span class="badge bg-info text-dark px-3 py-2 mb-3 rounded-pill tracking-wider fw-bold shadow-sm">
                                <i class="bi bi-rocket-takeoff me-1"></i> SPK Panel
                            </span>
                            <h1 class="display-5 fw-bolder mb-2" style="color: var(--text-main);">Selamat Datang, <span class="text-info">{{ Auth::user()->name }}</span>! 👋</h1>
                            <p class="lead mb-0" style="color: var(--text-muted); max-width: 600px;">
                                Pusat analitik algoritma TOPSIS. Pantau ringkasan performa investasi dan unduh laporan terbaru Anda di sini.
                            </p>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="mini-stat text-center">
                                <h3 class="fw-bold text-info mb-0">{{ $totalKriteria }}</h3>
                                <small style="color: var(--text-muted);">Kriteria Aktif</small>
                            </div>
                            <div class="mini-stat text-center">
                                <h3 class="fw-bold text-success mb-0">{{ $totalAsetDigital }}</h3>
                                <small style="color: var(--text-muted);">Aset Dianalisis</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics Cards -->
            <div class="row g-4 mb-5 animate-entrance" style="animation-delay: 0.15s;">
                <!-- Total Kriteria -->
                <div class="col-md-6 col-lg-4">
                    <div class="card modern-widget p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-2 small fw-semibold" style="color: #00d4ff; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.8rem;">Kriteria</p>
                                <h3 class="fw-bold mb-0" style="font-size: 2.2rem; color: var(--text-main);">{{ $totalKriteria }}</h3>
                            </div>
                            <div class="widget-header-icon icon-cyan" style="font-size: 1.5rem;">
                                <i class="bi bi-sliders"></i>
                            </div>
                        </div>
                        <p class="small mb-4" style="color: var(--text-muted); line-height: 1.5;">Kriteria analisis yang aktif</p>
                        <a href="{{ route('kriteria.index') }}" class="btn btn-sm btn-outline-info fw-semibold mt-auto" style="border-radius: 8px; font-size: 0.85rem;">Kelola</a>
                    </div>
                </div>

                <!-- Total Aset Digital -->
                <div class="col-md-6 col-lg-4">
                    <div class="card modern-widget p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-2 small fw-semibold" style="color: #00ff00; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.8rem;">Aset</p>
                                <h3 class="fw-bold mb-0" style="font-size: 2.2rem; color: var(--text-main);">{{ $totalAsetDigital }}</h3>
                            </div>
                            <div class="widget-header-icon" style="background: rgba(0, 255, 0, 0.15); color: #00ff00; border: 1px solid rgba(0, 255, 0, 0.2); font-size: 1.5rem;">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                        <p class="small mb-4" style="color: var(--text-muted); line-height: 1.5;">Aset digital dianalisis</p>
                        <a href="{{ route('aset-digital.index') }}" class="btn btn-sm btn-outline-success fw-semibold mt-auto" style="border-radius: 8px; font-size: 0.85rem;">Kelola</a>
                    </div>
                </div>

                <!-- Total Penilaian -->
                <div class="col-md-6 col-lg-4">
                    <div class="card modern-widget p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-2 small fw-semibold" style="color: #ffc107; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.8rem;">Penilaian</p>
                                <h3 class="fw-bold mb-0" style="font-size: 2.2rem; color: var(--text-main);">{{ $totalPenilaian }}</h3>
                            </div>
                            <div class="widget-header-icon icon-gold" style="font-size: 1.5rem;">
                                <i class="bi bi-pencil-square"></i>
                            </div>
                        </div>
                        @php
                            $maxPenilaian = $totalKriteria * $totalAsetDigital;
                            $persentase = $maxPenilaian > 0 ? round(($totalPenilaian / $maxPenilaian) * 100, 0) : 0;
                        @endphp
                        <div class="progress mb-2" style="height: 6px; background: rgba(255, 255, 255, 0.1); border-radius: 3px; overflow: hidden;">
                            <div class="progress-bar" style="background: linear-gradient(90deg, #ffc107, #ff9800); width: {{ $persentase }}%;"></div>
                        </div>
                        <small class="fw-semibold" style="color: #ffc107;">{{ $persentase }}% Lengkap</small>
                    </div>
                </div>
            </div>
                <div class="col-lg-4 animate-entrance" style="animation-delay: 0.2s;">
                    <div class="card h-100 modern-widget p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="fw-bold mb-0">Aset Peringkat #1</h5>
                            <div class="widget-header-icon icon-gold"><i class="bi bi-trophy-fill"></i></div>
                        </div>
                        <div class="text-center mt-2 mb-4">
                            @if($asetTerbaik)
                                <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill px-3 py-1 mb-2 border border-warning border-opacity-50">Sangat Direkomendasikan</span>
                                <h3 class="fw-bold mt-2 mb-1" style="color: var(--text-main);">{{ $asetTerbaik['nama_aset'] }}</h3>
                                <p class="small mb-3" style="color: var(--text-muted);">{{ $asetTerbaik['jenis_aset'] }}</p>
                                <p class="small mb-3" style="color: var(--text-muted);">Nilai Preferensi TOPSIS:</p>
                                <div class="score-display">{{ number_format($asetTerbaik['preferensi'], 4) }}</div>
                            @else
                                <span class="badge bg-secondary bg-opacity-25 text-secondary rounded-pill px-3 py-1 mb-2 border border-secondary border-opacity-50">Belum Ada Data</span>
                                <h3 class="fw-bold mt-2 mb-1" style="color: var(--text-muted);">-</h3>
                                <p class="small mb-3" style="color: var(--text-muted);">Tambahkan kriteria dan aset untuk mulai analisis</p>
                                <div class="score-display">-</div>
                            @endif
                        </div>
                        <div class="mt-auto pt-3 border-top" style="border-color: var(--card-border) !important;">
                            <a href="{{ route('topsis.hasil') }}" class="btn btn-outline-warning btn-interactive w-100">Lihat Detail Analisis</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 animate-entrance" style="animation-delay: 0.4s;">
                    <div class="card h-100 modern-widget p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="fw-bold mb-0">Aktivitas Sistem</h5>
                            <div class="widget-header-icon icon-cyan"><i class="bi bi-clock-history"></i></div>
                        </div>
                        <ul class="activity-list flex-grow-1">
                            <li class="activity-item"><div class="activity-dot bg-success shadow-sm"></div><div><span style="color: var(--text-main); font-weight: 600;">Data Aset ditambahkan</span><br><span class="small opacity-75" style="color: var(--text-muted);">Hari ini, 10:45 WIB</span></div></li>
                            <li class="activity-item"><div class="activity-dot bg-info shadow-sm"></div><div><span style="color: var(--text-main); font-weight: 600;">Kalkulasi TOPSIS selesai</span><br><span class="small opacity-75" style="color: var(--text-muted);">Hari ini, 09:30 WIB</span></div></li>
                            <li class="activity-item"><div class="activity-dot bg-warning shadow-sm"></div><div><span style="color: var(--text-main); font-weight: 600;">Bobot kriteria diubah</span><br><span class="small opacity-75" style="color: var(--text-muted);">Kemarin, 14:15 WIB</span></div></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 animate-entrance" style="animation-delay: 0.6s;">
                    <div class="card h-100 modern-widget p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="fw-bold mb-0">Ekspor Laporan</h5>
                            <div class="widget-header-icon icon-blue"><i class="bi bi-cloud-arrow-down-fill"></i></div>
                        </div>
                        <p class="small mb-4" style="color: var(--text-muted); line-height: 1.6;">Unduh matriks keputusan, hasil ideal positif/negatif, dan peringkat akhir.</p>
                        <div class="d-flex flex-column gap-3 mt-auto">
                            <button class="btn btn-danger btn-interactive d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(90deg, #dc3545, #f06573); border: none;"><i class="bi bi-file-earmark-pdf-fill fs-5 me-2"></i> Laporan PDF</button>
                            <button class="btn btn-success btn-interactive d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(90deg, #198754, #28a745); border: none;"><i class="bi bi-file-earmark-spreadsheet-fill fs-5 me-2"></i> Ekspor Excel</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 animate-entrance" style="animation-delay: 0.8s;">
                <div class="col-12">
                    <div class="card modern-widget p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Perbandingan Skor TOPSIS</h5>
                        </div>
                        <div id="topsisChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<button class="fab-btn animate-entrance" style="animation-delay: 1s;" data-bs-toggle="modal" data-bs-target="#quickModal" title="Tindakan Cepat">
    <i class="bi bi-plus-lg fs-3"></i>
</button>

<div class="modal fade" id="quickModal" tabindex="-1" aria-labelledby="quickModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="quickModalLabel">Tindakan Cepat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="text-muted mb-4">Pilih tindakan yang ingin Anda lakukan secara instan:</p>
                <div class="row g-3">
                    <div class="col-6">
                        <a href="/kriteria" class="btn btn-outline-info w-100 py-3 rounded-4 fw-bold d-flex flex-column align-items-center gap-2">
                            <i class="bi bi-ui-checks-grid fs-2"></i> Tambah Kriteria
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-warning w-100 py-3 rounded-4 fw-bold d-flex flex-column align-items-center gap-2">
                            <i class="bi bi-box-seam-fill fs-2"></i> Tambah Aset
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- KONFIGURASI GRAFIK APEXCHARTS ---
        var chartOptions = {
            series: [{ name: 'Skor Preferensi', data: [0.985, 0.872, 0.765, 0.650, 0.540] }],
            chart: { type: 'bar', height: 320, toolbar: { show: false }, background: 'transparent', fontFamily: 'Plus Jakarta Sans, sans-serif' },
            plotOptions: { bar: { borderRadius: 8, horizontal: false, columnWidth: '45%', distributed: true } },
            colors: ['#ffc107', '#00d4ff', '#4dabf7', '#a78bfa', '#f472b6'], 
            dataLabels: { enabled: true, style: { fontSize: '12px', colors: ["#fff"] } },
            xaxis: { categories: ['Ethereum', 'Bitcoin', 'Dragon Lore', 'CS:GO Case', 'Axie'], labels: { style: { colors: '#94a3b8', fontSize: '13px', fontWeight: 600 } }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { labels: { style: { colors: '#94a3b8', fontWeight: 500 } } },
            grid: { borderColor: 'rgba(255, 255, 255, 0.05)', strokeDashArray: 4, yaxis: { lines: { show: true } } },
            theme: { mode: 'dark' }, legend: { show: false }, tooltip: { theme: 'dark' }
        };
        var chart = new ApexCharts(document.querySelector("#topsisChart"), chartOptions);
        chart.render();

        // --- LOGIKA DARK/LIGHT MODE ---
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const themeText = document.getElementById('themeText');
        const htmlElement = document.documentElement;

        const currentTheme = localStorage.getItem('theme') || 'dark';
        htmlElement.setAttribute('data-theme', currentTheme);
        updateUI(currentTheme);

        themeToggleBtn.addEventListener('click', () => {
            let newTheme = htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateUI(newTheme);
        });

        function updateUI(theme) {
            if (theme === 'light') { themeIcon.className = 'bi bi-moon-fill text-primary me-2'; themeText.innerText = 'Mode Gelap'; } 
            else { themeIcon.className = 'bi bi-sun-fill text-warning me-2'; themeText.innerText = 'Mode Terang'; }
            
            chart.updateOptions({
                theme: { mode: theme },
                grid: { borderColor: theme === 'light' ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)' },
                xaxis: { labels: { style: { colors: theme === 'light' ? '#64748b' : '#94a3b8' } } },
                yaxis: { labels: { style: { colors: theme === 'light' ? '#64748b' : '#94a3b8' } } }
            });
        }
    });
</script>
@endsection