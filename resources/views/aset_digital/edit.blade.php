@extends('layouts.app')

@section('title', 'Edit Matriks Penilaian - ' . $aset->nama_aset)

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap');
    
    /* ====================================================
       VARIABEL WARNA PREMIUM (SINKRON ULTRA DENGAN DETAIL)
       ==================================================== */
    :root {
        --color-background-primary: #0a0f1d;
        --color-background-secondary: #0f172a;
        --color-text-primary: #ffffff;
        --color-text-secondary: #94a3b8;
        --color-border-secondary: rgba(0, 212, 255, 0.16);
        --color-border-tertiary: rgba(0, 212, 255, 0.06);
        --accent-cyan: #00d4ff;
        --accent-cyan-hover: #00bfe6;
        --input-bg: rgba(15, 23, 42, 0.6);
        --panel-preview-bg: rgba(6, 14, 32, 0.6);
    }

    [data-theme="light"], .light {
        --color-background-primary: #f1f5f9;
        --color-background-secondary: #ffffff;
        --color-text-primary: #0f172a;
        --color-text-secondary: #64748b;
        --color-border-secondary: rgba(15, 23, 42, 0.14);
        --color-border-tertiary: rgba(15, 23, 42, 0.05);
        --accent-cyan: #0284c7;
        --accent-cyan-hover: #0369a1;
        --input-bg: #f8fafc;
        --panel-preview-bg: #f1f5f9;
    }

    /* OVERRIDE HACK UNTUK MENJAGA KONSISTENSI FULLSCREEN */
    aside, .sidebar, [id*="sidebar"], [class*="sidebar"] {
        display: none !important;
    }

    main, .main-content, .content-wrapper, div.flex-1, [class*="main-content"] {
        margin-left: 0 !important;
        padding-left: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    body, html, .bg-background, main, div.overflow-y-auto, .D-container-edit {
        background-color: var(--color-background-primary) !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .D-container-edit {
        font-family: 'Space Grotesk', sans-serif;
        color: var(--color-text-primary);
        padding: 32px;
        min-height: 100vh;
    }

    .panel-premium {
        background: var(--color-background-secondary);
        border: 1px solid var(--color-border-tertiary);
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: background 0.3s, border-color 0.3s;
    }

    /* MINI PREVIEW CARD COMPONENT */
    .mini-preview-card {
        background: var(--panel-preview-bg);
        border: 1px solid var(--color-border-secondary);
        border-radius: 14px;
        overflow: hidden;
    }

    /* STYLE ELEMEN FORM CYBER */
    .form-label-cyber {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        color: var(--color-text-secondary);
        margin-bottom: 8px;
        display: block;
    }

    .form-control-cyber {
        background-color: var(--input-bg) !important;
        border: 1px solid var(--color-border-secondary) !important;
        color: var(--color-text-primary) !important;
        border-radius: 12px;
        padding: 12px 16px;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 14px;
        transition: all 0.25s ease;
    }

    .form-control-cyber:hover {
        border-color: rgba(0, 212, 255, 0.4) !important;
    }

    .form-control-cyber:focus {
        border-color: var(--accent-cyan) !important;
        box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.12) !important;
        outline: none;
    }

    .input-group-text-cyber {
        background-color: var(--color-border-tertiary) !important;
        border: 1px solid var(--color-border-secondary) !important;
        color: var(--color-text-secondary) !important;
        border-radius: 0 12px 12px 0;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        font-weight: 600;
        padding-left: 18px;
        padding-right: 18px;
    }
    
    .form-control-cyber.has-addon {
        border-radius: 12px 0 0 12px !important;
    }

    /* PREMIUM BUTTONS UTILITY */
    .btn-action-route {
        border-radius: 12px;
        padding: 14px 28px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .btn-blue-gradient {
        background: linear-gradient(90deg, var(--accent-cyan) 0%, #4dabf7 100%);
        color: #0a0f1d !important;
        border: none;
        box-shadow: 0 4px 14px rgba(0, 212, 255, 0.25);
    }

    .btn-blue-gradient:hover {
        background: linear-gradient(90deg, var(--accent-cyan-hover) 0%, #339af0 100%);
        transform: translateY(-2px);
    }

    .btn-outline-dark {
        background: transparent;
        color: var(--color-text-primary) !important;
        border: 1px solid var(--color-border-secondary) !important;
    }

    .btn-outline-dark:hover {
        background: rgba(0, 212, 255, 0.03);
        border-color: var(--accent-cyan) !important;
    }

    /* TOPBAR ACTION UTILS */
    .icon-btn { 
        width: 42px; 
        height: 40px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border: 1px solid var(--color-border-secondary); 
        border-radius: 12px; 
        cursor: pointer; 
        color: var(--color-text-secondary); 
        background: transparent; 
        transition: all 0.2s; 
    }
    .icon-btn:hover { 
        color: var(--accent-cyan); 
        border-color: var(--accent-cyan); 
        background: rgba(0, 212, 255, 0.05); 
    }
    
    .divider-cyber {
        height: 1px;
        background: linear-gradient(90deg, var(--color-border-secondary) 0%, transparent 100%);
        border: none;
        margin: 32px 0;
    }
</style>

<header class="w-full border-b d-flex justify-content-between align-items-center px-5" style="height: 84px; background: var(--color-background-secondary); border-color: var(--color-border-tertiary) !important;">
    <div style="font-size: 15px; font-weight: 600; color: var(--color-text-secondary); letter-spacing: 0.5px;" class="font-mono mb-0">
        CYBER-FINANCE PROTOCOL &gt; <span style="color: var(--accent-cyan); font-weight: 700;">EDIT ALTERNATIF</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="icon-btn" id="themeToggle" title="Ubah Tema" style="font-size: 16px;"><i class="ti ti-sun" id="themeIcon" aria-hidden="true"></i></div>
        <a href="{{ route('aset-digital.show', $aset->id) }}" class="py-2 px-4 rounded-3 d-flex align-items-center gap-2 text-decoration-none" style="border: 1px solid var(--color-border-secondary); color: var(--accent-cyan); font-size: 13px; font-weight: 700; height: 40px; transition: all 0.2s;">
            <i class="ti ti-arrow-back-up" style="font-size: 15px;"></i> Batal
        </a>
    </div>
</header>

<div class="D-container-edit">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <form action="{{ route('aset-digital.update', $aset->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="panel-premium d-flex flex-column">
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge rounded-1 text-uppercase font-mono" style="background: rgba(0, 212, 255, 0.12); color: var(--accent-cyan); font-size: 10px; letter-spacing: 0.5px; border: 0.5px solid rgba(0, 212, 255, 0.25);">
                                Parameter Matrix Adjustment
                            </span>
                            <span class="text-muted font-mono" style="font-size: 11px;">System Index: #{{ $aset->id }}</span>
                        </div>
                        <h3 class="fw-bold text-uppercase m-0" style="letter-spacing: -0.5px; color: var(--color-text-primary);">
                            Konfigurasi Nilai Objek
                        </h3>
                    </div>

                    @php
                        $modelSlug = \Illuminate\Support\Str::slug($aset->nama_aset);
                        $isSteam = (strtolower($aset->jenis_aset) === 'steam');
                        $namaGame = $isSteam ? 'Counter-Strike 2' : 'Ethereum Network';
                    @endphp
                    <div class="mini-preview-card p-3 mb-4 d-flex align-items-center gap-3">
                        <div style="width: 100px; aspect-ratio: 16/9; border-radius: 8px; overflow: hidden; background: #060e20;" class="flex-shrink-0 border">
                            <img src="{{ asset('images/assets/' . $modelSlug . '.png') }}" 
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=200&auto=format';" 
                                 class="w-100 h-100 object-fit-cover" alt="Preview">
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-uppercase" style="font-size: 15px; color: var(--color-text-primary);">{{ str_replace('-', ' ', $aset->nama_aset) }}</h5>
                            <span class="font-mono text-muted text-uppercase d-block" style="font-size: 11px;">
                                <i class="ti {{ $isSteam ? 'ti-brand-steam' : 'ti-currency-ethereum' }} text-primary" style="color: var(--accent-cyan) !important;"></i> Ecosystem: {{ $namaGame }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-2">
                        <h5 class="fw-bold mb-3 font-mono" style="font-size: 13px; color: var(--accent-cyan); letter-spacing: 0.5px;">
                            <i class="ti ti-id me-1"></i> [01] Core Specifications
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="nama_aset" class="form-label-cyber">Nama Alternatif Aset</label>
                                <input type="text" name="nama_aset" id="nama_aset" class="form-control-cyber w-100" value="{{ old('nama_aset', $aset->nama_aset) }}" required placeholder="Contoh: AK-47 | Neon Revolution">
                            </div>
                            <div class="col-md-5">
                                <label for="jenis_aset" class="form-label-cyber">Kategori Platform</label>
                                <select name="jenis_aset" id="jenis_aset" class="form-control-cyber w-100" style="cursor: pointer;" required>
                                    <option value="steam" {{ old('jenis_aset', $aset->jenis_aset) == 'steam' ? 'selected' : '' }}>STEAM (CS2 Skins)</option>
                                    <option value="opensea" {{ old('jenis_aset', $aset->jenis_aset) == 'opensea' ? 'selected' : '' }}>OPENSEA (NFT Assets)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="divider-cyber"></div>

                    <div>
                        <h5 class="fw-bold mb-1 font-mono" style="font-size: 13px; color: var(--accent-cyan); letter-spacing: 0.5px;">
                            <i class="ti ti-layers-linked me-1"></i> [02] Decision Matrix Criteria Values
                        </h5>
                        <p class="text-muted mb-4" style="font-size: 12px;">Modifikasi variabel bobot alternatif di bawah ini sesuai kalkulasi riil instrumen pasar.</p>
                        
                        <div class="row g-4">
                            @foreach($kriterias as $kriteria)
                                @php
                                    $penilaian = $aset->penilaians->where('kriteria_id', $kriteria->id)->first();
                                    $currentValue = $penilaian ? $penilaian->nilai : '';
                                    
                                    $placeholder = 'Input skor';
                                    $addonText = 'Pts';
                                    if ($kriteria->kode_kriteria === 'C1') {
                                        $placeholder = 'cth: 420.50';
                                        $addonText = $aset->jenis_aset === 'opensea' ? 'ETH' : 'USD';
                                    } elseif ($kriteria->kode_kriteria === 'C2') {
                                        $placeholder = 'cth: 150000';
                                        $addonText = $aset->jenis_aset === 'opensea' ? 'ETH' : 'USD';
                                    } elseif (in_array($kriteria->kode_kriteria, ['C3', 'C4', 'C5'])) {
                                        $placeholder = 'Skala Likert (1 - 5)';
                                        $addonText = '1 - 5';
                                    }
                                @endphp

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <label for="kriteria_{{ $kriteria->id }}" class="form-label-cyber mb-0">
                                            {{ $kriteria->kode_kriteria }}. {{ str_replace(' Saat Ini', '', $kriteria->nama_kriteria) }}
                                        </label>
                                        <span class="font-mono fw-bold text-uppercase" style="font-size: 9px; color: var(--accent-cyan); opacity: 0.85;">
                                            {{ $kriteria->jenis }}
                                        </span>
                                    </div>
                                    
                                    <div class="input-group">
                                        <input type="number" 
                                               step="any" 
                                               name="kriteria[{{ $kriteria->id }}]" 
                                               id="kriteria_{{ $kriteria->id }}" 
                                               class="form-control-cyber has-addon w-100" 
                                               value="{{ old('kriteria.' . $kriteria->id, $currentValue) }}" 
                                               required 
                                               placeholder="{{ $placeholder }}">
                                        <span class="input-group-text input-group-text-cyber">{{ $addonText }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end mt-5 pt-2" style="border-top: 1px dashed var(--color-border-secondary);">
                        <a href="{{ route('aset-digital.show', $aset->id) }}" class="btn-action-route btn-outline-dark">
                            Batal
                        </a>
                        <button type="submit" class="btn-action-route btn-blue-gradient">
                            <i class="ti ti-device-floppy"></i> Simpan Matriks
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
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
                if(themeIcon) themeIcon.className = 'ti ti-moon text-primary';
            } else {
                htmlElement.classList.remove('light');
                htmlElement.classList.add('dark');
                bodyElement.classList.remove('light');
                bodyElement.classList.add('dark');
                if(themeIcon) themeIcon.className = 'ti ti-sun text-warning';
            }
        }
    });
</script>
@endsection