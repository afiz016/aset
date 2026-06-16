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

    /* ── SCROLLBAR — menyesuaikan background ── */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: var(--color-background-primary); }
    ::-webkit-scrollbar-thumb { background: rgba(0, 212, 255, 0.15); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(0, 212, 255, 0.35); }
    [data-theme="light"] ::-webkit-scrollbar-track,
    .light ::-webkit-scrollbar-track { background: #f1f5f9; }
    [data-theme="light"] ::-webkit-scrollbar-thumb,
    .light ::-webkit-scrollbar-thumb { background: rgba(15,23,42,0.12); }
    html { scrollbar-width: thin; scrollbar-color: rgba(0,212,255,0.15) var(--color-background-primary); }
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
                @php
                    // Ikon berdasarkan jenis aset
                    $iconMap = [
                        'nft'        => ['icon' => 'ti-box',               'color' => '#a78bfa'],
                        'opensea'    => ['icon' => 'ti-anchor',             'color' => '#60a5fa'],
                        'ethereum'   => ['icon' => 'ti-currency-ethereum',  'color' => '#818cf8'],
                        'steam'      => ['icon' => 'ti-brand-steam',        'color' => '#00d4ff'],
                        'cs2'        => ['icon' => 'ti-diamond',            'color' => '#00d4ff'],
                        'csgo'       => ['icon' => 'ti-diamond',            'color' => '#00d4ff'],
                        'dota'       => ['icon' => 'ti-sword',              'color' => '#f59e0b'],
                        'dota2'      => ['icon' => 'ti-sword',              'color' => '#f59e0b'],
                        'axie'       => ['icon' => 'ti-device-gamepad-2',   'color' => '#22c55e'],
                        'solana'     => ['icon' => 'ti-layers-subtract',    'color' => '#9333ea'],
                        'bitcoin'    => ['icon' => 'ti-currency-bitcoin',   'color' => '#f59e0b'],
                        'default'    => ['icon' => 'ti-asset',              'color' => '#94a3b8'],
                    ];

                    function getTickerIcon(string $jenis, array $map): array {
                        $j = strtolower($jenis);
                        foreach ($map as $key => $val) {
                            if ($key !== 'default' && str_contains($j, $key)) return $val;
                        }
                        return $map['default'];
                    }
                @endphp

                {{-- Render dua group identik agar marquee seamless loop --}}
                @for($g = 0; $g < 2; $g++)
                <div class="ticker-group">
                    @forelse($tickerAsets as $ta)
                    @php
                        $ic = getTickerIcon($ta['jenis_aset'], $iconMap);
                        $skor = $ta['preferensi'];
                        // Tentukan arah dan warna berdasarkan skor preferensi
                        if ($skor === null) {
                            $arrow = ''; $arrowColor = '#94a3b8'; $skorLabel = '';
                        } elseif ($skor >= 0.6) {
                            $arrow = '▲'; $arrowColor = '#22c55e';
                            $skorLabel = number_format($skor * 100, 1) . '%';
                        } elseif ($skor >= 0.4) {
                            $arrow = '◆'; $arrowColor = '#f59e0b';
                            $skorLabel = number_format($skor * 100, 1) . '%';
                        } else {
                            $arrow = '▼'; $arrowColor = '#ef4444';
                            $skorLabel = number_format($skor * 100, 1) . '%';
                        }
                    @endphp
                    <div class="ticker-item">
                        <i class="ti {{ $ic['icon'] }}" style="color:{{ $ic['color'] }};"></i>
                        <span style="color:var(--color-text-primary);font-weight:600;">{{ $ta['nama_aset'] }}</span>
                        @if($skor !== null)
                            <span style="color:#00d4ff;font-family:'JetBrains Mono',monospace;font-size:0.95rem;">V={{ number_format($skor, 4) }}</span>
                            <span style="color:{{ $arrowColor }};font-weight:700;font-size:0.9rem;">{{ $arrow }} {{ $skorLabel }}</span>
                        @else
                            <span style="color:#64748b;font-size:0.85rem;">{{ $ta['jenis_aset'] }}</span>
                        @endif
                    </div>
                    @empty
                    <div class="ticker-item">
                        <i class="ti ti-info-circle" style="color:#94a3b8;"></i>
                        <span style="color:#94a3b8;">Belum ada data aset — silakan tambah aset terlebih dahulu</span>
                    </div>
                    @endforelse
                </div>
                @endfor
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
                        @forelse($daftarRanking as $idx => $r)
                        @php
                            $vi     = $r['preferensi'];
                            $isGold = $idx === 0;
                            $barAm  = $vi < 0.6;   // amber bar untuk skor rendah

                            // Badge rekomendasi
                            if ($vi >= 0.8)      { $badgeClass = 'b-teal';   $badgeText = 'Strong Buy'; }
                            elseif ($vi >= 0.6)  { $badgeClass = 'b-amber';  $badgeText = 'Buy'; }
                            elseif ($vi >= 0.4)  { $badgeClass = 'b-purple'; $badgeText = 'Hold'; }
                            else                 { $badgeClass = 'b-red';    $badgeText = 'Avoid'; }

                            $namaDisplay = strtoupper(str_replace('-', ' ', $r['nama_aset']));
                            $jenis       = ucfirst($r['jenis_aset'] ?? '-');
                        @endphp
                        <div class="rank-row">
                            <div class="rn {{ $isGold ? 'gold' : '' }}">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</div>
                            <div>
                                <div class="asset-name" title="{{ $r['nama_aset'] }}">{{ $namaDisplay }}</div>
                                <div class="asset-game">{{ $jenis }} · <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span></div>
                            </div>
                            <div class="score-num">{{ number_format($vi, 3) }}</div>
                            <div class="bar-bg">
                                <div class="bar-fill {{ $barAm ? 'am' : '' }}" data-w="{{ $vi * 100 }}"></div>
                            </div>
                        </div>
                        @empty
                        <div style="padding:32px 22px; text-align:center; color:var(--color-text-secondary); font-size:13px;">
                            <i class="ti ti-database-off" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                            Belum ada data ranking. Lengkapi penilaian terlebih dahulu.
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="side-panel">
                    <div class="card">
                        <div class="card-head">
                            <div class="card-title">Bobot Kriteria</div>
                            <a href="{{ route('kriteria.index') }}" class="icon-btn" style="width:22px; height:22px; border:none; color:var(--color-text-secondary);"><i class="ti ti-edit" style="font-size:12px;" aria-hidden="true"></i></a>
                        </div>
                        <div style="padding:8px 14px;">
                            @php
                                $kritIcons = [
                                    'harga'      => ['icon'=>'ti-coin',         'bg'=>'rgba(239,68,68,0.1)',    'color'=>'#ef4444'],
                                    'volume'     => ['icon'=>'ti-activity',     'bg'=>'rgba(0,212,255,0.1)',    'color'=>'var(--accent-cyan)'],
                                    'rarity'     => ['icon'=>'ti-diamond',      'bg'=>'rgba(99,102,241,0.1)',   'color'=>'#6366f1'],
                                    'sentiment'  => ['icon'=>'ti-trending-up',  'bg'=>'rgba(245,158,11,0.1)',   'color'=>'#f59e0b'],
                                    'likuid'     => ['icon'=>'ti-droplet',      'bg'=>'rgba(0,212,255,0.1)',    'color'=>'var(--accent-cyan)'],
                                    'default'    => ['icon'=>'ti-adjustments',  'bg'=>'rgba(148,163,184,0.1)', 'color'=>'#94a3b8'],
                                ];
                                function getKritIcon(string $nama, array $map): array {
                                    $n = strtolower($nama);
                                    if (str_contains($n,'harga') || str_contains($n,'beli') || str_contains($n,'cost')) return $map['harga'];
                                    if (str_contains($n,'volume') || str_contains($n,'transaksi')) return $map['volume'];
                                    if (str_contains($n,'rarity') || str_contains($n,'langka') || str_contains($n,'kelangkaan')) return $map['rarity'];
                                    if (str_contains($n,'sentiment') || str_contains($n,'pasar')) return $map['sentiment'];
                                    if (str_contains($n,'likuid')) return $map['likuid'];
                                    return $map['default'];
                                }
                            @endphp
                            @forelse($kriterias as $kr)
                            @php $ki = getKritIcon($kr->nama_kriteria, $kritIcons); @endphp
                            <div class="crit-row">
                                <div class="crit-icon" style="background:{{ $ki['bg'] }};"><i class="ti {{ $ki['icon'] }}" style="font-size:13px;color:{{ $ki['color'] }};" aria-hidden="true"></i></div>
                                <div class="crit-name">{{ $kr->nama_kriteria }}</div>
                                <span class="crit-type {{ strtolower($kr->jenis)==='benefit' ? 'b-teal' : 'b-red' }} badge">{{ ucfirst($kr->jenis) }}</span>
                                <div class="crit-w">{{ $kr->bobot }}</div>
                            </div>
                            @empty
                            <div style="padding:16px;text-align:center;color:var(--color-text-secondary);font-size:12px;">Belum ada kriteria.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-head">
                            <div class="card-title">Notifikasi Pasar</div>
                            <span class="badge b-teal">Info</span>
                        </div>
                        <div style="padding:8px 14px;">
                            @if($asetTerbaik)
                            <div class="alert-row">
                                <div class="alert-dot" style="background:var(--accent-cyan);"></div>
                                <div>
                                    <div class="alert-text">{{ strtoupper(str_replace('-',' ',$asetTerbaik['nama_aset'])) }} meraih skor tertinggi {{ number_format($asetTerbaik['preferensi'],4) }}</div>
                                    <div class="alert-time">Hasil perhitungan TOPSIS terbaru</div>
                                </div>
                            </div>
                            @endif
                            <div class="alert-row">
                                <div class="alert-dot" style="background:#f59e0b;"></div>
                                <div>
                                    <div class="alert-text">{{ $totalAsetDigital }} aset terdaftar · {{ $totalPenilaian }} data penilaian</div>
                                    <div class="alert-time">{{ $rataRataPenilaian }}% matriks terisi</div>
                                </div>
                            </div>
                            <div class="alert-row">
                                <div class="alert-dot" style="background:#6366f1;"></div>
                                <div>
                                    <div class="alert-text">{{ $totalKriteria }} kriteria aktif dalam sistem</div>
                                    <div class="alert-time">Manajemen kriteria</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom-grid">
                {{-- CHART: Distribusi Aset per Platform pakai Chart.js --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-title">Distribusi Aset per Platform</div>
                        <span class="badge b-teal">{{ $totalAsetDigital }} Aset</span>
                    </div>
                    <div style="padding: 20px 22px 16px; position:relative; height:220px;">
                        <canvas id="platformChart"></canvas>
                    </div>
                </div>

                {{-- RING: Komposisi Platform pakai Chart.js Doughnut --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-title">Komposisi Platform</div>
                        <span class="badge b-purple">{{ $totalAsetDigital }} aset</span>
                    </div>
                    <div style="display:flex; align-items:center; padding:14px 18px 14px; gap:16px;">
                        <div style="position:relative; width:130px; height:130px; flex-shrink:0;">
                            <canvas id="doughnutChart" width="130" height="130"></canvas>
                            <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                                <span style="font-size:24px;font-weight:700;color:var(--color-text-primary);">{{ $totalAsetDigital }}</span>
                                <span style="font-size:10px;color:var(--color-text-secondary);text-transform:uppercase;font-weight:700;letter-spacing:.5px;">total</span>
                            </div>
                        </div>
                        <div class="ring-info" style="flex:1;">
                            @php $ringColors = ['#00d4ff','#f59e0b','#6366f1','#ef4444','#22c55e','#ec4899']; @endphp
                            @foreach($distribusiPlatform as $idx => $dp)
                            @php $rc = $ringColors[$idx % count($ringColors)]; @endphp
                            <div class="ri-row">
                                <span style="display:flex;align-items:center;color:var(--color-text-primary);">
                                    <div class="ri-dot" style="background:{{ $rc }};"></div>{{ $dp['platform'] }}
                                </span>
                                <span style="font-weight:700;color:var(--color-text-primary);">
                                    {{ $dp['jumlah'] }}
                                    <span style="color:var(--color-text-secondary);font-weight:400;font-size:11px;">
                                        ({{ $totalAsetDigital > 0 ? round($dp['jumlah']/$totalAsetDigital*100) : 0 }}%)
                                    </span>
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
                htmlElement.classList.remove('dark'); htmlElement.classList.add('light');
                bodyElement.classList.remove('dark'); bodyElement.classList.add('light');
                themeIcon.className = 'ti ti-moon text-primary';
            } else {
                htmlElement.classList.remove('light'); htmlElement.classList.add('dark');
                bodyElement.classList.remove('light'); bodyElement.classList.add('dark');
                themeIcon.className = 'ti ti-sun text-warning';
            }
        }

        // ── DATA DARI BLADE ──
        const platformLabels = @json($distribusiPlatform->pluck('platform'));
        const platformData   = @json($distribusiPlatform->pluck('jumlah'));
        const chartColors    = ['#00d4ff','#f59e0b','#6366f1','#ef4444','#22c55e','#ec4899','#14b8a6','#a78bfa'];
        const barColors      = platformData.map((_, i) => chartColors[i % chartColors.length]);

        // ── BAR CHART — Distribusi per Platform ──
        const barCtx = document.getElementById('platformChart');
        if (barCtx) {
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: platformLabels,
                    datasets: [{
                        label: 'Jumlah Aset',
                        data: platformData,
                        backgroundColor: barColors.map(c => c + '22'),
                        borderColor: barColors,
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            borderColor: 'rgba(0,212,255,0.2)',
                            borderWidth: 1,
                            titleColor: '#00d4ff',
                            bodyColor: '#f1f5f9',
                            padding: 10,
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y} aset`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: {
                                color: '#64748b',
                                font: { size: 11, weight: '600' }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(148,163,184,0.06)',
                                drawBorder: false
                            },
                            border: { display: false, dash: [4,4] },
                            ticks: {
                                color: '#64748b',
                                font: { size: 10 },
                                stepSize: 1,
                                padding: 8
                            },
                            beginAtZero: true
                        }
                    },
                    animation: {
                        duration: 900,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // ── DOUGHNUT CHART — Komposisi Platform ──
        const dCtx = document.getElementById('doughnutChart');
        if (dCtx) {
            new Chart(dCtx, {
                type: 'doughnut',
                data: {
                    labels: platformLabels,
                    datasets: [{
                        data: platformData,
                        backgroundColor: barColors.map(c => c + '33'),
                        borderColor: barColors,
                        borderWidth: 2,
                        hoverBorderWidth: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '68%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            borderColor: 'rgba(0,212,255,0.2)',
                            borderWidth: 1,
                            titleColor: '#00d4ff',
                            bodyColor: '#f1f5f9',
                            padding: 10,
                            callbacks: {
                                label: ctx => ` ${ctx.label}: ${ctx.parsed} aset`
                            }
                        }
                    },
                    animation: { duration: 900, easing: 'easeOutQuart' }
                }
            });
        }
    });
</script>
@endsection