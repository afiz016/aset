@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');
    
    /* ====================================================
       PENGATURAN VARIABEL WARNA (PREMIUM MIDNIGHT NAVY)
       ==================================================== */
    :root {
        --color-background-primary: #0a0f1d;     /* Deep Midnight Cyber Blue */
        --color-background-secondary: #0f172a;   /* Navy Blue Card Base */
        --color-text-primary: #ffffff;
        --color-text-secondary: #94a3b8;         /* Warna abu-abu terang untuk mode malam */
        --color-border-secondary: rgba(0, 212, 255, 0.16);
        --color-border-tertiary: rgba(0, 212, 255, 0.06);
        --border-radius-lg: 16px;
        --border-radius-md: 12px;
        --font-sans: 'Inter', system-ui, sans-serif;
        --ticker-bg: #070a14;
        --ticker-border: rgba(0, 212, 255, 0.3);
        --accent-cyan: #00d4ff;                  /* Cyan Utama */
    }

    [data-theme="light"], .light {
        --color-background-primary: #f1f5f9;    
        --color-background-secondary: #ffffff;  
        --color-text-primary: #0f172a;           
        --color-text-secondary: #64748b;         
        --color-border-secondary: rgba(15, 23, 42, 0.14);
        --color-border-tertiary: rgba(15, 23, 42, 0.05);
        --ticker-bg: #e2e8f0;
        --ticker-border: #0284c7;
        --accent-cyan: #0284c7;                  
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    
    .D {
        font-family: 'Space Grotesk', var(--font-sans);
        background: var(--color-background-primary);
        border: 0.5px solid var(--color-border-tertiary);
        border-radius: var(--border-radius-lg);
        overflow-x: hidden;
        overflow-y: visible; 
        transition: background 0.3s, border-color 0.3s, color 0.3s;
        width: 100%;
        display: flex;
        flex-direction: column;
    }
    
    /* TOPBAR & LAYERING TITLE OPTIMIZATION */
    .topbar { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 24px 28px; 
        border-bottom: 0.5px solid var(--color-border-tertiary); 
        background: var(--color-background-secondary); 
    }
    .topbar-title-wrapper {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .page-badge {
        font-size: 10px;
        font-weight: 700;
        color: var(--accent-cyan);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        line-height: 1;
    }
    .page-title-premium {
        font-size: 22px;
        font-weight: 700;
        color: var(--color-text-primary);
        letter-spacing: -0.5px;
        line-height: 1.2;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .page-title-separator {
        color: var(--color-border-secondary);
        font-weight: 300;
    }
    .page-title-user {
        color: var(--accent-cyan);
        font-weight: 500;
    }
    .top-right { display: flex; align-items: center; gap: 14px; }
    
    /* BUTTONS STYLE */
    .btn-sm { font-family: inherit; font-size: 12px; padding: 8px 18px; border: 0.5px solid var(--color-border-secondary); border-radius: var(--border-radius-md); background: transparent; color: var(--color-text-primary); cursor: pointer; font-weight: 600; transition: all 0.2s ease; }
    .btn-sm:hover { background: rgba(0, 212, 255, 0.08); border-color: var(--accent-cyan); }
    .btn-accent { border: none; background: var(--accent-cyan); color: #0a0f1d; font-weight: 700; box-shadow: 0 4px 14px rgba(0, 212, 255, 0.18); }
    [data-theme="light"] .btn-accent { color: #ffffff; }
    .btn-accent:hover { background: #00bfe6; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(0, 212, 255, 0.28); }
    
    .icon-btn { width: 36px; height: 34px; display: flex; align-items: center; justify-content: center; border: 0.5px solid var(--color-border-tertiary); border-radius: var(--border-radius-md); cursor: pointer; color: var(--color-text-secondary); background: transparent; transition: all 0.2s; }
    .icon-btn:hover { color: var(--accent-cyan); border-color: var(--accent-cyan); background: rgba(0, 212, 255, 0.05); }
    
    /* CONTAINER & GRID LAYOUT CONTAINER */
    .body { padding: 28px; display: flex; flex-direction: column; gap: 26px; }
    .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 18px; }
    .stat { background: var(--color-background-secondary); border-radius: var(--border-radius-md); padding: 20px; border: 0.5px solid var(--color-border-tertiary); transition: transform 0.2s, border-color 0.2s; }
    .stat:hover { border-color: rgba(0, 212, 255, 0.25); transform: translateY(-2px); }
    
    /* PERBAIKAN: Mengubah var(--text-secondary) menjadi var(--color-text-secondary) agar kelihatan di mode malam */
    .stat-label { font-size: 11px; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: .9px; margin-bottom: 12px; display: flex; align-items: center; gap: 7px; font-weight: 700; }
    .stat-label i { color: var(--accent-cyan); font-size: 14px; }
    .stat-val { font-size: 32px; font-weight: 700; color: var(--color-text-primary); line-height: 1.2; font-family: 'Space Grotesk', sans-serif; }
    .stat-delta { font-size: 11px; margin-top: 10px; display: flex; align-items: center; gap: 4px; font-weight: 500; font-family: var(--font-sans); }
    .up { color: #10b981; } .down { color: #ef4444; }
    
    /* PANEL UTAMA & SIDEBAR SIZING */
    .mid-grid { display: grid; grid-template-columns: minmax(0, 1fr) 320px; gap: 26px; }
    .card { background: var(--color-background-secondary); border: 0.5px solid var(--color-border-tertiary); border-radius: var(--border-radius-lg); overflow: hidden; transition: background 0.3s; }
    .card-head { display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; border-bottom: 0.5px solid var(--color-border-tertiary); }
    .card-title { font-size: 14px; font-weight: 700; color: var(--color-text-primary); tracking-wide; line-height: 1.4; }
    .card-sub { font-size: 11px; color: var(--color-text-secondary); margin-top: 4px; font-family: var(--font-sans); line-height: 1.4; }
    
    /* BADGES */
    .badge { font-size: 10px; padding: 4px 10px; border-radius: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; }
    .b-teal { background: rgba(0, 212, 255, 0.1); color: var(--accent-cyan); border: 0.5px solid rgba(0, 212, 255, 0.15); }
    .b-amber { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 0.5px solid rgba(245, 158, 11, 0.15); }
    .b-red { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 0.5px solid rgba(239, 68, 68, 0.15); }
    .b-purple { background: rgba(99, 102, 241, 0.1); color: #6366f1; border: 0.5px solid rgba(99, 102, 241, 0.15); }
    
    /* PERFECT ALIGNED RANKING TABLE */
    .rank-list { padding: 6px 0; }
    .rank-row { display: grid; grid-template-columns: 44px minmax(0, 1fr) 75px 160px; align-items: center; gap: 18px; padding: 16px 22px; border-bottom: 0.5px solid var(--color-border-tertiary); transition: background 0.2s; }
    .rank-row:hover { background: rgba(255, 255, 255, 0.015); }
    .rank-row:last-child { border-bottom: none; }
    
    /* PERBAIKAN: var(--color-text-secondary) */
    .rn { font-size: 15px; font-weight: 700; color: var(--color-text-secondary); text-align: center; }
    .rn.gold { color: #f59e0b; font-size: 16px; }
    .asset-name { font-size: 14px; font-weight: 600; color: var(--color-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.4; }
    .asset-game { font-size: 11px; color: var(--color-text-secondary); margin-top: 5px; display: flex; align-items: center; gap: 6px; font-family: var(--font-sans); }
    
    .score-num { font-size: 14px; font-weight: 700; color: var(--color-text-primary); text-align: right; font-family: monospace; }
    .bar-bg { height: 8px; background: var(--color-background-primary); border-radius: 5px; overflow: hidden; border: 0.5px solid var(--color-border-tertiary); }
    .bar-fill { height: 100%; border-radius: 5px; background: var(--accent-cyan); width: 0; transition: width 1.2s cubic-bezier(.4,0,.2,1); box-shadow: 0 0 8px rgba(0, 212, 255, 0.4); }
    .bar-fill.am { background: #f59e0b; box-shadow: 0 0 8px rgba(245, 158, 11, 0.4); }
    
    /* CRITERIA & SIDE PANEL ROWS */
    .side-panel { display: flex; flex-direction: column; gap: 26px; }
    .crit-row { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 0.5px solid var(--color-border-tertiary); }
    .crit-row:last-child { border-bottom: none; }
    .crit-icon { width: 30px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .crit-icon i { font-size: 15px; }
    .crit-name { font-size: 13px; color: var(--color-text-primary); flex: 1; min-width: 0; font-weight: 500; line-height: 1.4; }
    .crit-type { font-size: 10px; padding: 2px 7px; border-radius: 6px; font-weight: 600; }
    .crit-w { font-size: 12px; font-weight: 700; color: var(--color-text-primary); min-width: 38px; text-align: right; font-family: monospace; }
    
    .alert-row { display: flex; align-items: flex-start; gap: 14px; padding: 14px 0; border-bottom: 0.5px solid var(--color-border-tertiary); }
    .alert-row:last-child { border-bottom: none; }
    .alert-dot { width: 7px; height: 7px; border-radius: 50%; margin-top: 6px; flex-shrink: 0; }
    .alert-text { font-size: 12px; color: var(--color-text-primary); line-height: 1.5; font-family: var(--font-sans); }
    .alert-time { font-size: 10px; color: var(--color-text-secondary); margin-top: 5px; font-weight: 500; }
    
    /* BOTTOM VISUAL CHARTS */
    .bottom-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 26px; }
    .mini-chart { padding: 22px 20px; }
    .chart-bars { display: flex; align-items: flex-end; gap: 12px; height: 100px; border-bottom: 1px solid var(--color-border-secondary); padding-bottom: 2px; }
    .cbar { flex: 1; border-radius: 4px 4px 0 0; min-width: 0; transition: height 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    .chart-labels { display: flex; gap: 12px; margin-top: 10px; }
    
    /* PERBAIKAN: var(--color-text-secondary) */
    .clabel { flex: 1; font-size: 10px; color: var(--color-text-secondary); text-align: center; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 600; }
    
    .progress-ring { display: flex; align-items: center; justify-content: center; padding: 14px; }
    .ring-wrap { position: relative; width: 110px; height: 110px; }
    .ring-wrap svg { width: 110px; height: 110px; transform: rotate(-90deg); }
    .ring-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .ring-val { font-size: 26px; font-weight: 700; color: var(--color-text-primary); }
    
    /* PERBAIKAN: var(--color-text-secondary) */
    .ring-label { font-size: 10px; color: var(--color-text-secondary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.3px; }
    .ring-info { flex: 1; padding: 14px 18px 14px 0; display: flex; flex-direction: column; justify-content: center; gap: 10px; }
    
    /* PERBAIKAN: Ditambahkan pewarnaan teks dasar agar angka persentase ring bursa di kanan terwarnai terang di mode malam */
    .ri-row { display: flex; align-items: center; justify-content: space-between; font-size: 12px; font-family: var(--font-sans); color: var(--color-text-secondary); }
    .ri-dot { width: 8px; height: 8px; border-radius: 50%; margin-right: 10px; flex-shrink: 0; }

    /* RUNNING TICKER BAR */
    .ticker-wrap { 
        width: 100%; 
        overflow: hidden; 
        background: var(--ticker-bg); 
        border-bottom: 2px solid var(--ticker-border); 
        padding: 18px 0; 
        display: flex; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .ticker-content { 
        display: flex; 
        width: max-content; 
        animation: marquee 35s linear infinite; 
        white-space: nowrap; 
    }
    .ticker-content:hover { animation-play-state: paused; }
    .ticker-group { 
        display: flex; 
        align-items: center; 
        flex-wrap: nowrap; 
    }
    .ticker-item { 
        display: inline-flex; 
        align-items: center; 
        gap: 10px;                    
        padding: 0 4rem;              
        font-size: 1.05rem;           
        font-weight: 700; 
        color: var(--color-text-primary); 
        letter-spacing: 0.6px;
        line-height: 1.5; 
        white-space: nowrap;          
    }
    .ticker-item i { font-size: 18px; display: inline-block; line-height: 1; }
    @keyframes marquee { 0% { transform: translate3d(0, 0, 0); } 100% { transform: translate3d(-50%, 0, 0); } }
</style>

<div class="D">

    <div class="main">
        
        <div class="topbar">
            <div class="topbar-title-wrapper">
                <span class="page-badge">In-Game Asset Investment Analytics Hub</span>
                <h1 class="page-title-premium">
                         DASHBOARD 
                    <span class="page-title-separator">|</span> 
                    <span class="page-title-user">{{ Auth::user()->name }}</span>
                </h1>
            </div>
            <div class="top-right">
                <div class="icon-btn" id="themeToggle" title="Ubah Tema"><i class="ti ti-sun" id="themeIcon" aria-hidden="true"></i></div>
                <div class="icon-btn" onclick="window.location.reload();" title="Refresh Data"><i class="ti ti-refresh" aria-hidden="true"></i></div>
                <a href="{{ route('dashboard.exportPdf') }}" class="btn-sm text-decoration-none text-center d-inline-flex align-items-center">
                    Export PDF
                </a>
                <a href="{{ route('aset-digital.index') }}" class="btn-sm btn-accent text-decoration-none d-inline-flex align-items-center gap-1">
                    <i class="ti ti-plus" style="font-size:11px;" aria-hidden="true"></i> Aset Baru ↗
                </a>
            </div>
        </div>

        <div class="ticker-wrap">
            <div class="ticker-content">
                <div class="ticker-group">
                    <div class="ticker-item"><i class="ti ti-currency-bitcoin text-warning"></i> Bitcoin (BTC) <span class="text-info">$64,230</span> <span class="text-success">▲ 2.4%</span></div>
                    <div class="ticker-item"><i class="ti ti-diamond text-info"></i> CS:GO Dragon Lore <span class="text-info">$8,500</span> <span class="text-success">▲ 0.5%</span></div>
                    <div class="ticker-item"><i class="ti ti-currency-ethereum text-secondary"></i> Ethereum (ETH) <span class="text-info">$3,450</span> <span class="text-danger">▼ 1.2%</span></div>
                    <div class="ticker-item"><i class="ti ti-device-gamepad text-primary"></i> Axie Infinity (AXS) <span class="text-info">$45.20</span> <span class="text-success">▲ 5.1%</span></div>
                </div>
                <div class="ticker-group">
                    <div class="ticker-item"><i class="ti ti-currency-bitcoin text-warning"></i> Bitcoin (BTC) <span class="text-info">$64,230</span> <span class="text-success">▲ 2.4%</span></div>
                    <div class="ticker-item"><i class="ti ti-diamond text-info"></i> CS:GO Dragon Lore <span class="text-info">$8,500</span> <span class="text-success">▲ 0.5%</span></div>
                    <div class="ticker-item"><i class="ti ti-currency-ethereum text-secondary"></i> Ethereum (ETH) <span class="text-info">$3,450</span> <span class="text-danger">▼ 1.2%</span></div>
                    <div class="ticker-item"><i class="ti ti-device-gamepad text-primary"></i> Axie Infinity (AXS) <span class="text-info">$45.20</span> <span class="text-success">▲ 5.1%</span></div>
                </div>
            </div>
        </div>

        <div class="body">
            <div class="stats">
                <div class="stat">
                    <div class="stat-label"><i class="ti ti-layers-subtract" aria-hidden="true"></i> Total Aset</div>
                    <div class="stat-val">{{ $totalAsetDigital }}</div>
                    <div class="stat-delta up"><i class="ti ti-arrow-up" style="font-size:10px;" aria-hidden="true"></i> Terdaftar aktif</div>
                </div>
                <div class="stat">
                    <div class="stat-label"><i class="ti ti-star" aria-hidden="true"></i> Skor Tertinggi</div>
                    <div class="stat-val font-mono" style="color: #f59e0b;">
                        {{ $asetTerbaik ? number_format($asetTerbaik['preferensi'], 3) : '0.847' }}
                    </div>
                    <div class="stat-delta text-truncate" style="color:var(--color-text-secondary); display:block; max-width:180px;" title="{{ $asetTerbaik ? $asetTerbaik['nama_aset'] : 'Dragon Lore' }}">
                        {{ $asetTerbaik ? $asetTerbaik['nama_aset'] : 'Dragon Lore' }}
                    </div>
                </div>
                <div class="stat">
                    <div class="stat-label"><i class="ti ti-activity" aria-hidden="true"></i> Volume 24 Jam</div>
                    <div class="stat-val">$18.2K</div>
                    <div class="stat-delta up"><i class="ti ti-trending-up" style="font-size:10px;" aria-hidden="true"></i> +12.4%</div>
                </div>
                <div class="stat">
                    <div class="stat-label"><i class="ti ti-clock" aria-hidden="true"></i> Matriks Penilaian</div>
                    <div class="stat-val">{{ $totalPenilaian }}</div>
                    @php
                        $maxPenilaian = $totalKriteria * $totalAsetDigital;
                        $persentase = $maxPenilaian > 0 ? round(($totalPenilaian / $maxPenilaian) * 100, 0) : 0;
                    @endphp
                    <div class="stat-delta {{ $persentase == 100 ? 'up' : 'text-warning' }} fw-bold">
                        {{ $persentase }}% Terisi[cite: 2]
                    </div>
                </div>
            </div>

            <div class="mid-grid">
                <div class="card">
                    <div class="card-head">
                        <div>
                            <div class="card-title">Ranking Investasi Aset In-Game</div>
                            <div class="card-sub">Diurutkan berdasarkan nilai kedekatan relatif solusi ideal ($V_i$)[cite: 2]</div>
                        </div>
                        <span class="badge b-teal">Live</span>
                    </div>
                    <div class="rank-list">
                        <div class="rank-row">
                            <div class="rn gold">01</div>
                            <div>
                                <div class="asset-name">{{ $asetTerbaik ? $asetTerbaik['nama_aset'] : 'Dragon Lore AK-47' }}</div>
                                <div class="asset-game">Counter-Strike 2 · <span class="badge b-amber">Solusi Utama</span></div>
                            </div>
                            <div class="score-num">{{ $asetTerbaik ? number_format($asetTerbaik['preferensi'], 3) : '0.847' }}</div>
                            <div class="bar-bg"><div class="bar-fill" data-w="{{ $asetTerbaik ? $asetTerbaik['preferensi'] * 100 : '84.7' }}"></div></div>
                        </div>
                        <div class="rank-row">
                            <div class="rn">02</div>
                            <div><div class="asset-name">Bayonet Marble Fade</div><div class="asset-game">Counter-Strike 2 · <span class="badge b-amber">Covert</span></div></div>
                            <div class="score-num">0.791</div>
                            <div class="bar-bg"><div class="bar-fill" data-w="79.1"></div></div>
                        </div>
                        <div class="rank-row">
                            <div class="rn">03</div>
                            <div><div class="asset-name">Arcana Phantom Assn.</div><div class="asset-game">Dota 2 · <span class="badge b-purple">Arcana</span></div></div>
                            <div class="score-num">0.724</div>
                            <div class="bar-bg"><div class="bar-fill" data-w="72.4"></div></div>
                        </div>
                        <div class="rank-row">
                            <div class="rn">04</div>
                            <div><div class="asset-name">Immortal Wraith King</div><div class="asset-game">Dota 2 · <span class="badge b-teal">Immortal</span></div></div>
                            <div class="score-num">0.663</div>
                            <div class="bar-bg"><div class="bar-fill am" data-w="66.3"></div></div>
                        </div>
                        <div class="rank-row">
                            <div class="rn">05</div>
                            <div><div class="asset-name">M4A4 Howl</div><div class="asset-game">Counter-Strike 2 · <span class="badge b-red">Contraband</span></div></div>
                            <div class="score-num">0.612</div>
                            <div class="bar-bg"><div class="bar-fill am" data-w="61.2"></div></div>
                        </div>
                    </div>
                </div>

                <div class="side-panel">
                    <div class="card">
                        <div class="card-head">
                            <div class="card-title">Bobot Kriteria</div>
                            <a href="{{ route('kriteria.index') }}" class="icon-btn" style="width:22px; height:22px; border:none; color:var(--color-text-secondary);"><i class="ti ti-edit" style="font-size:12px;" aria-hidden="true"></i></a>
                        </div>
                        <div style="padding:8px 14px;">
                            <div class="crit-row">
                                <div class="crit-icon" style="background:rgba(239,68,68,0.1);"><i class="ti ti-coin" style="font-size:13px;color:#ef4444;" aria-hidden="true"></i></div>
                                <div class="crit-name">Harga Beli</div>
                                <span class="crit-type b-red badge">Cost</span>
                                <div class="crit-w">20%</div>
                            </div>
                            <div class="crit-row">
                                <div class="crit-icon" style="background:rgba(0, 212, 255, 0.1);"><i class="ti ti-activity" style="font-size:13px;color:var(--accent-cyan);" aria-hidden="true"></i></div>
                                <div class="crit-name">Volume Transaksi</div>
                                <span class="crit-type b-teal badge">Benefit</span>
                                <div class="crit-w">25%</div>
                            </div>
                            <div class="crit-row">
                                <div class="crit-icon" style="background:rgba(99,102,241,0.1);"><i class="ti ti-diamond" style="font-size:13px;color:#6366f1;" aria-hidden="true"></i></div>
                                <div class="crit-name">Tingkat Rarity</div>
                                <span class="crit-type b-purple badge">Benefit</span>
                                <div class="crit-w">25%</div>
                            </div>
                            <div class="crit-row">
                                <div class="crit-icon" style="background:rgba(245,158,11,0.1);"><i class="ti ti-trending-up" style="font-size:13px;color:#f59e0b;" aria-hidden="true"></i></div>
                                <div class="crit-name">Market Sentiment</div>
                                <span class="crit-type b-teal badge">Benefit</span>
                                <div class="crit-w">15%</div>
                            </div>
                            <div class="crit-row">
                                <div class="crit-icon" style="background:rgba(0, 212, 255, 0.1);"><i class="ti ti-droplet" style="font-size:13px;color:var(--accent-cyan);" aria-hidden="true"></i></div>
                                <div class="crit-name">Likuiditas</div>
                                <span class="crit-type b-teal badge">Benefit</span>
                                <div class="crit-w">15%</div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-head"><div class="card-title">Notifikasi Pasar</div><span class="badge b-red">3 baru</span></div>
                        <div style="padding:8px 14px;">
                            <div class="alert-row">
                                <div class="alert-dot" style="background:var(--accent-cyan);"></div>
                                <div><div class="alert-text">Dragon Lore naik 8.3% dalam 2 jam terakhir</div><div class="alert-time">14 menit lalu</div></div>
                            </div>
                            <div class="alert-row">
                                <div class="alert-dot" style="background:#f59e0b;"></div>
                                <div><div class="alert-text">Volume M4A4 Howl menurun 15% hari ini</div><div class="alert-time">1 jam lalu</div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom-grid">
                <div class="card">
                    <div class="card-head"><div class="card-title">Distribusi Volume per Platform</div><span class="badge b-teal">7 hari</span></div>
                    <div class="mini-chart">
                        <div class="chart-bars">
                            <div class="cbar" style="height:80%; background:var(--accent-cyan);"></div>
                            <div class="cbar" style="height:55%; background:var(--accent-cyan); opacity:.7;"></div>
                            <div class="cbar" style="height:65%; background:var(--accent-cyan); opacity:.6;"></div>
                            <div class="cbar" style="height:42%; background:#f59e0b;"></div>
                            <div class="cbar" style="height:30%; background:#f59e0b; opacity:.7;"></div>
                            <div class="cbar" style="height:20%; background:#6366f1;"></div>
                            <div class="cbar" style="height:15%; background:#6366f1; opacity:.7;"></div>
                        </div>
                        <div class="chart-labels">
                            <div class="clabel">Steam</div>
                            <div class="clabel">CS2</div>
                            <div class="clabel">DMarket</div>
                            <div class="clabel">Dota2</div>
                            <div class="clabel">Skinport</div>
                            <div class="clabel">OpenSea</div>
                            <div class="clabel">Rarible</div>
                        </div>
                        <div style="display:flex; gap:12px; margin-top:14px; font-family: var(--font-sans);">
                            <div style="display:flex; align-items:center; gap:4px; font-size:10px; color:var(--color-text-secondary);"><div style="width:8px; height:8px; border-radius:2px; background:var(--accent-cyan);"></div>Counter-Strike</div>
                            <div style="display:flex; align-items:center; gap:4px; font-size:10px; color:var(--color-text-secondary);"><div style="width:8px; height:8px; border-radius:2px; background:#f59e0b;"></div>Dota 2</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-head"><div class="card-title">Distribusi Rarity Aset</div><span class="badge b-purple">{{ $totalAsetDigital }} aset</span></div>
                    <div style="display:flex; align-items:center; padding:10px 18px 14px;">
                        <div class="progress-ring">
                            <div class="ring-wrap">
                                <svg viewBox="0 0 90 90">
                                    <circle cx="45" cy="45" r="35" fill="none" stroke-width="10" stroke="var(--color-background-primary)"/>
                                    <circle cx="45" cy="45" r="35" fill="none" stroke-width="10" stroke="var(--accent-cyan)" stroke-dasharray="66 154" stroke-dashoffset="0" stroke-linecap="round"/>
                                    <circle cx="45" cy="45" r="35" fill="none" stroke-width="10" stroke="#f59e0b" stroke-dasharray="44 176" stroke-dashoffset="-66" stroke-linecap="round"/>
                                    <circle cx="45" cy="45" r="35" fill="none" stroke-width="10" stroke="#6366f1" stroke-dasharray="33 187" stroke-dashoffset="-110" stroke-linecap="round"/>
                                    <circle cx="45" cy="45" r="35" fill="none" stroke-width="10" stroke="#ef4444" stroke-dasharray="17 203" stroke-dashoffset="-143" stroke-linecap="round"/>
                                </svg>
                                <div class="ring-center">
                                    <div class="ring-val">{{ $totalAsetDigital }}</div>
                                    <div class="ring-label">total</div>
                                </div>
                            </div>
                        </div>
                        <div class="ring-info">
                            <div class="ri-row"><span style="display:flex; align-items:center; color:var(--color-text-primary);"><div class="ri-dot" style="background:var(--accent-cyan);"></div>Covert</span><span>42%</span></div>
                            <div class="ri-row"><span style="display:flex; align-items:center; color:var(--color-text-primary);"><div class="ri-dot" style="background:#f59e0b;"></div>Immortal</span><span>29%</span></div>
                            <div class="ri-row"><span style="display:flex; align-items:center; color:var(--color-text-primary);"><div class="ri-dot" style="background:#6366f1;"></div>Arcana</span><span>21%</span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ANIMASI LOADING TRACER PROGRESS BAR RANKING
        setTimeout(() => {
            document.querySelectorAll('.bar-fill').forEach(b => {
                b.style.width = (b.dataset.w || 0) + '%';
            });
        }, 300);

        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;
        const bodyElement = document.body;

        const currentTheme = localStorage.getItem('theme') || 'dark';
        applyTheme(currentTheme);

        themeToggleBtn.addEventListener('click', () => {
            const activeTheme = htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', activeTheme);
            applyTheme(activeTheme);
        });

        function applyTheme(theme) {
            htmlElement.setAttribute('data-theme', theme);

            if (theme === 'light') {
                htmlElement.classList.remove('dark');
                htmlElement.classList.add('light');
                bodyElement.classList.remove('dark');
                bodyElement.classList.add('light');
                themeIcon.className = 'ti ti-moon text-primary';
            } else {
                htmlElement.classList.remove('light');
                htmlElement.classList.add('dark');
                bodyElement.classList.remove('light');
                bodyElement.classList.add('dark');
                themeIcon.className = 'ti ti-sun text-warning';
            }
        }
    });
</script>
@endsection