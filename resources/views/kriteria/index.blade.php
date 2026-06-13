@extends('layouts.app')

@section('title', 'Data Kriteria')

@section('content')

@php
    $total = $kriterias->count();
    $benefit = $kriterias->where('jenis', 'benefit')->count();
    $cost = $kriterias->where('jenis', 'cost')->count();
    
    // Menghitung persentase untuk garis rasio
    $benefitPercent = $total > 0 ? round(($benefit / $total) * 100) : 0;
    $costPercent = $total > 0 ? round(($cost / $total) * 100) : 0;
@endphp

<style>
    /* ====================================================
       TEMA & VARIABEL WARNA (DARK/LIGHT MODE)
       ==================================================== */
    :root {
        --bg-main: #0f172a; --text-main: #ffffff; --text-muted: #94a3b8;
        --card-bg: rgba(255, 255, 255, 0.02); --card-border: rgba(255, 255, 255, 0.05);
        --card-hover-bg: rgba(255, 255, 255, 0.04);
        --card-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(0, 212, 255, 0.1);
        --input-bg: rgba(255, 255, 255, 0.03);
        --panel-bg: rgba(15, 23, 42, 0.6); 
    }

    [data-theme="light"] {
        --bg-main: #f8fafc; --text-main: #0f172a; --text-muted: #64748b;
        --card-bg: #ffffff; --card-border: rgba(0, 0, 0, 0.08);
        --card-hover-bg: #f0f8ff; 
        --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.05), 0 0 15px rgba(0, 212, 255, 0.15);
        --input-bg: rgba(0, 0, 0, 0.02);
        --panel-bg: rgba(255, 255, 255, 0.8);
    }

    .page-wrapper {
        background-color: var(--bg-main); color: var(--text-main);
        transition: background-color 0.5s ease, color 0.5s ease;
        min-height: 100vh; position: relative; overflow-x: hidden;
    }

    /* --- AMBIENT GLOW --- */
    .ambient-glow {
        position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);
        width: 700px; height: 700px;
        background: radial-gradient(circle, rgba(0,212,255,0.08) 0%, transparent 60%);
        border-radius: 50%; z-index: 0; pointer-events: none;
        animation: pulseGlow 8s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
    }
    @keyframes pulseGlow { 0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0.5; } 100% { transform: translate(-50%, -50%) scale(1.2); opacity: 1; } }

    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
    .animate-entrance { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; z-index: 1; position: relative; }

    /* --- WIDGET STATISTIK KACA --- */
    .modern-widget {
        background: var(--card-bg); backdrop-filter: blur(16px);
        border: 1px solid var(--card-border); border-radius: 20px;
        padding: 24px; transition: all 0.4s ease; display: flex; align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        position: relative; overflow: hidden;
    }
    .modern-widget:hover { transform: translateY(-5px); border-color: rgba(0, 212, 255, 0.3); box-shadow: var(--card-shadow); }
    
    .modern-widget::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, transparent 50%); pointer-events: none;
    }

    .widget-icon-box {
        width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;
        border-radius: 16px; font-size: 1.6rem; margin-right: 20px; transition: all 0.3s ease;
    }
    .modern-widget:hover .widget-icon-box { transform: scale(1.1) rotate(5deg); }

    .icon-cyan { background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(0, 212, 255, 0.05)); color: #00d4ff; border: 1px solid rgba(0, 212, 255, 0.3); }
    .icon-green { background: linear-gradient(135deg, rgba(32, 201, 151, 0.2), rgba(32, 201, 151, 0.05)); color: #20c997; border: 1px solid rgba(32, 201, 151, 0.3); }
    .icon-red { background: linear-gradient(135deg, rgba(255, 107, 107, 0.2), rgba(255, 107, 107, 0.05)); color: #ff6b6b; border: 1px solid rgba(255, 107, 107, 0.3); }

    /* Bar Rasio Dinamis */
    .ratio-bar { display: flex; height: 6px; border-radius: 10px; overflow: hidden; margin-top: 10px; background: rgba(0,0,0,0.05); }
    [data-theme="dark"] .ratio-bar { background: rgba(255,255,255,0.05); }
    .ratio-benefit { background-color: #20c997; transition: width 1s cubic-bezier(0.175, 0.885, 0.32, 1.275); } 
    .ratio-cost { background-color: #ff6b6b; transition: width 1s cubic-bezier(0.175, 0.885, 0.32, 1.275); }

    /* --- DATA PANEL --- */
    .data-panel {
        background: var(--panel-bg);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--card-border); border-radius: 24px; padding: 30px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.05);
    }

    .search-modern {
        background: var(--input-bg); border: 1px solid var(--card-border); color: var(--text-main);
        border-radius: 14px; padding: 12px 20px 12px 45px; transition: all 0.3s;
    }
    .search-modern:focus { background: var(--card-bg); border-color: #00d4ff; box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.1); color: var(--text-main); outline: none; }
    .search-icon-wrapper { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #00d4ff, #4dabf7); color: #000;
        border: none; border-radius: 12px; font-weight: 700; padding: 12px 24px; transition: all 0.3s ease; text-decoration: none;
    }
    .btn-gradient-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 212, 255, 0.4); color: #000; }

    .theme-toggle-btn { background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-main); transition: all 0.2s ease; }
    .theme-toggle-btn:hover { background: rgba(0, 212, 255, 0.1); border-color: #00d4ff; }

    /* --- TABEL HOVER PREMIUM --- */
    .table-responsive::-webkit-scrollbar { display: none; }
    .table-responsive { -ms-overflow-style: none; scrollbar-width: none; }
    
    .table-modern { border-collapse: separate; border-spacing: 0 12px; width: 100%; color: var(--text-main); margin-bottom: 0; }
    .table-modern thead th {
        background: transparent; border: none; padding: 10px 24px; font-weight: 700;
        color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1.5px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }
    
    .table-modern tbody tr { transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    
    .table-modern tbody td {
        background: var(--card-bg); border: none; 
        border-top: 1px solid var(--card-border); border-bottom: 1px solid var(--card-border);
        padding: 18px 24px; vertical-align: middle; transition: all 0.3s ease;
    }
    
    .table-modern tbody td:first-child { 
        border-left: 1px solid var(--card-border); 
        border-top-left-radius: 16px; border-bottom-left-radius: 16px; 
        position: relative; overflow: hidden;   
    }
    .table-modern tbody td:last-child { 
        border-right: 1px solid var(--card-border); 
        border-top-right-radius: 16px; border-bottom-right-radius: 16px; 
    }

    .table-modern tbody tr:not(.empty-row):hover {
        transform: scale(1.01) translateY(-3px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08); 
    }
    .table-modern tbody tr:not(.empty-row):hover td { 
        background: var(--card-hover-bg); 
        border-top-color: rgba(0, 212, 255, 0.3);
        border-bottom-color: rgba(0, 212, 255, 0.3);
    }
    
    .table-modern tbody tr:not(.empty-row):hover td:first-child::before {
        content: ''; position: absolute; left: 0; top: 0; width: 5px; height: 100%;
        background: #00d4ff; box-shadow: 0 0 15px #00d4ff; border-radius: 4px 0 0 4px;
        animation: slideDown 0.3s ease forwards;
    }

    @keyframes slideDown { 0% { transform: translateY(-100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }

    .table-modern tbody tr:not(.empty-row):hover td:last-child { border-right-color: rgba(0, 212, 255, 0.3); }

    .badge-modern { padding: 8px 16px; font-weight: 700; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .btn-action { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.2s; border: 1px solid; }
    .btn-action:hover { transform: scale(1.1); }
</style>

<div class="page-wrapper p-4 p-lg-5 flex-grow-1">
    
    <div class="ambient-glow"></div>

    <div class="container z-index-1 position-relative">
        
        <div class="d-flex justify-content-end mb-4 animate-entrance" style="animation-delay: 0.05s;">
            <button id="themeToggle" class="btn theme-toggle-btn rounded-pill px-4 py-2 shadow-sm fw-bold">
                <i class="bi bi-sun-fill text-warning me-2" id="themeIcon"></i> <span id="themeText">Mode Terang</span>
            </button>
        </div>

        <div class="d-flex align-items-center mb-5 animate-entrance" style="animation-delay: 0.1s;">
            <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-4 border border-primary border-opacity-25 shadow-sm d-flex justify-content-center align-items-center" style="width: 75px; height: 75px;">
                <i class="bi bi-ui-checks-grid text-info" style="font-size: 2.8rem; text-shadow: 0 0 20px rgba(0, 212, 255, 0.8);"></i>
            </div>
            <div>
                <h1 class="fw-bolder mb-1" style="color: var(--text-main);">Manajemen Kriteria</h1>
                <p class="mb-0 fs-6" style="color: var(--text-muted);">Pusat pengaturan bobot dan indikator penilaian SPK TOPSIS.</p>
            </div>
        </div>

        <div class="row g-4 mb-5 animate-entrance" style="animation-delay: 0.2s;">
            <div class="col-12 col-md-4">
                <div class="modern-widget flex-column align-items-start">
                    <div class="d-flex align-items-center w-100 mb-3">
                        <div class="widget-icon-box icon-cyan shadow-sm"><i class="bi bi-layers-fill"></i></div>
                        <div>
                            <h3 class="fw-bolder mb-0" style="color: var(--text-main);">{{ $total }}</h3>
                            <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Total Kriteria</span>
                        </div>
                    </div>
                    <div class="w-100">
                        <div class="d-flex justify-content-between small mb-1" style="color: var(--text-muted); font-size: 0.75rem;">
                            <span>Benefit ({{ $benefitPercent }}%)</span>
                            <span>Cost ({{ $costPercent }}%)</span>
                        </div>
                        <div class="ratio-bar">
                            <div class="ratio-benefit" style="width: {{ $benefitPercent }}%;"></div>
                            <div class="ratio-cost" style="width: {{ $costPercent }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-4">
                <div class="modern-widget h-100">
                    <div class="widget-icon-box icon-green shadow-sm"><i class="bi bi-arrow-up-right-circle-fill"></i></div>
                    <div>
                        <h3 class="fw-bolder mb-0 text-success">{{ $benefit }}</h3>
                        <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Sifat Benefit</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="modern-widget h-100">
                    <div class="widget-icon-box icon-red shadow-sm"><i class="bi bi-arrow-down-right-circle-fill"></i></div>
                    <div>
                        <h3 class="fw-bolder mb-0 text-danger">{{ $cost }}</h3>
                        <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Sifat Cost</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="data-panel animate-entrance" style="animation-delay: 0.3s;">
            
            @if(session('success'))
                <div class="alert alert-success bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-3 mb-4 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <span class="fw-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                
                <div class="position-relative" style="max-width: 400px; width: 100%;">
                    <div class="search-icon-wrapper"><i class="bi bi-search fs-5"></i></div>
                    <input type="text" id="searchInput" class="form-control search-modern w-100" placeholder="Cari kode atau nama kriteria...">
                </div>
                
                <a href="{{ route('kriteria.create') }}" class="btn btn-gradient-primary d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill me-2 fs-5"></i> Tambah Kriteria
                </a>
            </div>

            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th width="10%">Kode</th>
                            <th width="35%">Nama Kriteria</th>
                            <th width="15%">Bobot</th>
                            <th width="20%">Sifat / Jenis</th>
                            <th width="20%" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody id="tableBody">
                        @forelse($kriterias as $k)
                        <tr>
                            <td class="fw-bolder text-info fs-5">{{ $k->kode_kriteria }}</td>
                            <td class="fw-bold" style="font-size: 1.05rem;">{{ $k->nama_kriteria }}</td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-25 rounded-pill badge-modern adaptive-badge border border-secondary border-opacity-25 text-light fs-6">
                                    {{ $k->bobot }}
                                </span>
                            </td>
                            <td>
                                @if(strtolower($k->jenis) == 'benefit')
                                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill badge-modern border border-success border-opacity-50"><i class="bi bi-arrow-up-circle-fill me-1"></i> Benefit</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill badge-modern border border-danger border-opacity-50"><i class="bi bi-arrow-down-circle-fill me-1"></i> Cost</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end align-items-center">
                                    <a href="{{ route('kriteria.edit', $k->id) }}" class="btn btn-outline-warning text-warning border-warning btn-action me-2" data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </a>
                                    
                                    <form action="{{ route('kriteria.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini? Data penilaian alternatif terkait juga bisa terpengaruh.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger text-danger border-danger btn-action" data-bs-toggle="tooltip" title="Hapus">
                                            <i class="bi bi-trash3-fill fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row">
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                Belum ada data kriteria yang ditambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Tooltip Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Tema Dark/Light
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const themeText = document.getElementById('themeText');
        const htmlElement = document.documentElement;

        const currentTheme = localStorage.getItem('theme') || 'dark';
        htmlElement.setAttribute('data-theme', currentTheme);
        updateButtonUI(currentTheme);

        themeToggleBtn.addEventListener('click', () => {
            let newTheme = htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateButtonUI(newTheme);
        });

        function updateButtonUI(theme) {
            if (theme === 'light') {
                themeIcon.className = 'bi bi-moon-fill text-primary me-2';
                themeText.innerText = 'Mode Gelap';
                document.querySelectorAll('.adaptive-badge').forEach(el => {
                    el.classList.add('text-dark'); el.classList.remove('text-light');
                });
            } else {
                themeIcon.className = 'bi bi-sun-fill text-warning me-2';
                themeText.innerText = 'Mode Terang';
                document.querySelectorAll('.adaptive-badge').forEach(el => {
                    el.classList.add('text-light'); el.classList.remove('text-dark');
                });
            }
        }

        // ==========================================
        // FITUR LIVE SEARCH (PENCARIAN REAL-TIME)
        // ==========================================
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('tableBody');

        if(searchInput && tableBody) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = tableBody.querySelectorAll('tr:not(.empty-row)');

                rows.forEach(row => {
                    // Cari hanya di kolom Kode (td:nth-child(1)) dan Nama (td:nth-child(2))
                    const kode = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const nama = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    
                    if (kode.includes(filter) || nama.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection