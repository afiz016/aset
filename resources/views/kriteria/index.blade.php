@extends('layouts.app')

@section('title', 'Pembobotan Kriteria')

@section('content')

<!-- Ambil font dan ikon yang dibutuhkan untuk area konten -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');
    
    /* ====================================================
       PENGATURAN VARIABEL WARNA (SYNCED WITH DASHBOARD)
       ==================================================== */
    :root {
        --color-background-primary: #0a0f1d;
        --color-background-secondary: #0f172a;
        --color-text-primary: #ffffff;
        --color-text-secondary: #94a3b8;
        --color-border-secondary: rgba(0, 212, 255, 0.16);
        --color-border-tertiary: rgba(0, 212, 255, 0.06);
        --accent-cyan: #00d4ff;
    }

    [data-theme="light"], .light {
        --color-background-primary: #f1f5f9;
        --color-background-secondary: #ffffff;
        --color-text-primary: #0f172a;
        --color-text-secondary: #64748b;
        --color-border-secondary: rgba(15, 23, 42, 0.14);
        --color-border-tertiary: rgba(15, 23, 42, 0.05);
        --accent-cyan: #0284c7;
    }

    .D-container {
        font-family: 'Space Grotesk', sans-serif;
        background: var(--color-background-primary);
        color: var(--color-text-primary);
        padding: 28px;
        display: flex;
        flex-direction: column;
        gap: 26px;
        min-height: 100vh;
        transition: background 0.3s, color 0.3s;
    }

    .glass-card-premium {
        background: var(--color-background-secondary);
        border: 0.5px solid var(--color-border-tertiary);
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s;
    }
    .glass-card-premium:hover {
        border-color: rgba(0, 212, 255, 0.2);
    }

    /* TOPBAR HEADER ACTIONS */
    .icon-btn { 
        width: 36px; 
        height: 34px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border: 0.5px solid var(--color-border-secondary); 
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

    /* PREMIUM SOLID NEON ADD BUTTON EFFECT */
    .btn-add-premium {
        background: linear-gradient(90deg, var(--accent-cyan) 0%, #4dabf7 100%);
        color: #0a0f1d !important;
        border: none !important;
        border-radius: 12px;
        padding: 11px 24px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 14px rgba(0, 212, 255, 0.25);
    }
    .btn-add-premium:hover {
        background: linear-gradient(90deg, #00bfe6 0%, #339af0 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 212, 255, 0.5);
    }
    .btn-add-premium i {
        display: inline-block;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .btn-add-premium:hover i {
        transform: rotate(90deg) scale(1.15);
    }

    /* Custom range input styling */
    input[type="range"] {
        -webkit-appearance: none;
        appearance: none;
        background: transparent;
        cursor: pointer;
        width: 100%;
    }
    input[type="range"]::-webkit-slider-runnable-track {
        background: #1e293b;
        height: 6px;
        border-radius: 3px;
    }
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        margin-top: -5px;
        background-color: var(--accent-cyan);
        height: 16px;
        width: 16px;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.6);
    }

    /* Stepper Custom Style */
    .stepper-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin-bottom: 20px;
        padding: 0 40px;
    }
    .stepper-line {
        position: absolute;
        top: 50%;
        left: 40px;
        right: 40px;
        height: 2px;
        background: #1e293b;
        transform: translateY(-50%);
        z-index: 1;
    }
    .stepper-line-progress {
        position: absolute;
        top: 50%;
        left: 40px;
        width: 25%;
        height: 2px;
        background: var(--accent-cyan);
        transform: translateY(-50%);
        z-index: 1;
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
        background: #1e293b;
        color: #64748b;
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

    .btn-accent-premium {
        border: none;
        background: var(--accent-cyan);
        color: #0a0f1d;
        font-weight: 700;
        padding: 14px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0, 212, 255, 0.18);
        transition: all 0.2s ease;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-accent-premium:hover:not(:disabled) {
        background: #00bfe6;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 212, 255, 0.28);
    }
    .btn-accent-premium:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .btn-delete-crit {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 0.5px solid rgba(239, 68, 68, 0.2);
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        cursor: pointer;
    }
    .btn-delete-crit:hover {
        background: #ef4444;
        color: #fff;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.4);
    }
</style>

<!-- 🛠️ UPGRADED HEADER: Search & Connect Wallet Dihilangkan Rapi -->
<header class="w-full h-16 border-b d-flex justify-content-end align-items-center px-4 gap-3" style="background: var(--color-background-secondary); border-color: var(--color-border-tertiary) !important;">
    <div class="d-flex align-items-center gap-3">
        <!-- Hanya Menyisakan Tombol Utama Pengaturan Tema & Refresh Sistem -->
        <div class="icon-btn" id="themeToggle" title="Ubah Tema"><i class="ti ti-sun" id="themeIcon" aria-hidden="true"></i></div>
        <div class="icon-btn" onclick="window.location.reload();" title="Refresh Data"><i class="ti ti-refresh" aria-hidden="true"></i></div>
    </div>
</header>

<div class="D-container">
    
    <!-- Stepper Progress Segment -->
    <div class="stepper-wrap">
        <div class="stepper-line"></div>
        <div class="stepper-line-progress"></div>
        
        <div class="step-node active">
            <div class="step-circle">1</div>
            <div class="step-text">Kriteria</div>
        </div>
        <div class="step-node">
            <div class="step-circle">2</div>
            <div class="step-text">Alternatif</div>
        </div>
        <div class="step-node">
            <div class="step-circle">3</div>
            <div class="step-text">Hasil</div>
        </div>
    </div>

    <!-- Page Title Header & Tombol Tambah Kriteria Premium -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--color-text-primary); font-size: 24px;">Langkah 1: Pembobotan Kriteria</h2>
            <p style="color: var(--color-text-secondary); font-size: 14px;" class="mb-0">Tentukan tingkat kepentingan relatif untuk setiap parameter investasi. Total bobot harus berjumlah 100%.</p>
        </div>
        
        <a href="{{ route('kriteria.create') }}" class="btn-add-premium">
            <i class="ti ti-plus" style="font-size: 13px;"></i> Tambah Kriteria
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 text-success rounded-3 p-3 mb-0" style="background: rgba(16, 185, 129, 0.1);">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- Form Submit Range Slider -->
    <form action="{{ route('kriteria.update-batch') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left Side: Sliders Dinamis -->
            <div class="col-lg-8">
                <div class="glass-card-premium d-flex flex-column gap-4">
                    
                    @php $totalBobotAwal = 0; @endphp
                    @forelse($kriterias as $k)
                        @php 
                            $totalBobotAwal += $k->bobot;
                            $isCost = (strtolower($k->jenis) === 'cost');

                            $icon = 'ti-coin';
                            if(stripos($k->nama_kriteria, 'volume') !== false || stripos($k->nama_kriteria, 'transaksi') !== false) {
                                $icon = 'ti-activity';
                            } elseif(stripos($k->nama_kriteria, 'langka') !== false || stripos($k->nama_kriteria, 'rarity') !== false) {
                                $icon = 'ti-diamond';
                            } elseif(stripos($k->nama_kriteria, 'sentiment') !== false || stripos($k->nama_kriteria, 'tren') !== false) {
                                $icon = 'ti-trending-up';
                            } elseif(stripos($k->nama_kriteria, 'likuid') !== false) {
                                $icon = 'ti-droplet';
                            }

                            if($isCost) {
                                $iconColor = '#ef4444'; 
                                $badgeStyle = 'background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.15);';
                            } else {
                                $iconColor = '#10b981'; 
                                $badgeStyle = 'background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.15);';
                            }
                        @endphp

                        <!-- Slider Element Row dengan Tombol Hapus -->
                        <div class="d-flex flex-column gap-2" id="row-{{ $k->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="fw-bold d-flex align-items-center gap-2" style="color: var(--color-text-primary); font-size: 14px;">
                                    <div class="d-flex align-items-center justify-content-center rounded" style="width: 28px; height: 26px; background: rgba(255,255,255,0.04);">
                                        <i class="ti {{ $icon }}" style="color: {{ $iconColor }}; font-size: 14px;"></i>
                                    </div>
                                    <span class="text-info font-mono me-1">[{{ $k->kode_kriteria }}]</span> {{ $k->nama_kriteria }} 
                                    <span class="badge rounded-pill mx-1" style="{{ $badgeStyle }} font-size: 10px;">{{ strtoupper($k->jenis) }}</span>
                                </label>
                                
                                <div class="d-flex align-items-center gap-3">
                                    <span class="fw-bold px-2 py-1 rounded" style="{{ $badgeStyle }} font-size: 12px; font-family: monospace;" id="badge-{{ $k->kode_kriteria }}">
                                        {{ $k->bobot }}%
                                    </span>
                                    <button type="button" class="btn-delete-crit" onclick="confirmDelete('{{ $k->id }}', '{{ $k->nama_kriteria }}')" title="Hapus Kriteria">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="range" 
                                   name="bobot[{{ $k->id }}]" 
                                   class="slider-input" 
                                   data-target="badge-{{ $k->kode_kriteria }}" 
                                   max="100" 
                                   min="0" 
                                   value="{{ $k->bobot }}">
                        </div>
                    @empty
                        <div class="text-center py-4" style="color: var(--color-text-secondary);">
                            <i class="ti ti-folder-off fs-3 mb-2"></i>
                            <p class="mb-0">Belum ada parameter kriteria. Silakan tambah kriteria baru.</p>
                        </div>
                    @endforelse

                </div>

                <!-- Accumulation Bar -->
                <div class="mt-3 p-3 rounded-3 d-flex justify-content-between align-items-center border" style="background: var(--color-background-secondary); border-color: var(--color-border-tertiary) !important;">
                    <div class="d-flex align-items-center">
                        <span style="color: var(--color-text-secondary); font-size: 13px;">Total Akumulasi:</span>
                        <span class="ms-2 fw-bold text-info fs-5" id="total-weight">{{ $totalBobotAwal }}%</span>
                    </div>
                    <div class="text-success fw-semibold d-flex align-items-center gap-1" id="weight-status" style="font-size: 12px;">
                        <i class="ti ti-circle-check"></i> Siap diproses
                    </div>
                </div>
            </div>

            <!-- Right Side: Informational Card -->
            <div class="col-lg-4 d-flex flex-column gap-3">
                <div class="glass-card-premium" style="border-left: 4px solid var(--accent-cyan);">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-text-primary); font-size: 15px;">
                        <i class="ti ti-info-circle text-info"></i> Mengapa Ini Penting?
                    </h5>
                    <p class="mb-3" style="color: var(--color-text-secondary); font-size: 13px; line-height: 1.5;">
                        Metode <strong>TOPSIS</strong> menghitung jarak kedekatan relatif sebuah aset terhadap solusi ideal positif dan negatif.
                    </p>
                    <div class="d-flex flex-column gap-2" style="color: var(--color-text-secondary); font-size: 12px; line-height: 1.4;">
                        <div class="d-flex gap-2">
                            <span class="text-info fw-bold">1.</span>
                            <span>Bobot kriteria menentukan "derajat pengaruh" variabel terhadap skor akhir.</span>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="text-danger fw-bold">2.</span>
                            <span>Kriteria <strong class="text-danger">COST</strong> (seperti Harga Beli) semakin kecil nilainya akan dinilai semakin bagus.</span>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="text-success fw-bold">3.</span>
                            <span>Kriteria <strong class="text-success">BENEFIT</strong> (Rarity, Volume, Likuiditas) semakin besar nilainya akan dinilai semakin bagus.</span>
                        </div>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="btn-accent-premium">
                    Simpan & Lanjut ke Alternatif <i class="ti ti-arrow-right"></i>
                </button>
                <a href="/dashboard" class="btn text-center text-decoration-none py-2 border-0" style="color: var(--color-text-secondary); font-size: 13px; font-weight: 500; background: rgba(255,255,255,0.02); border-radius: 10px;">
                    Simpan Draft
                </a>
            </div>
        </div>
    </form>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

</div>

<!-- Script Sinkronisasi Light/Dark Mode & Live Calculator Sliders -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sliders = document.querySelectorAll('.slider-input');
        const totalDisplay = document.getElementById('total-weight');
        const weightStatus = document.getElementById('weight-status');
        const submitBtn = document.getElementById('submitBtn');

        function updateTotal() {
            let total = 0;
            sliders.forEach(slider => {
                total += parseInt(slider.value);
                const targetId = slider.getAttribute('data-target');
                document.getElementById(targetId).textContent = slider.value + '%';
            });
            
            totalDisplay.textContent = total + '%';

            if (total === 100) {
                totalDisplay.style.color = 'var(--accent-cyan)';
                weightStatus.innerHTML = '<i class="ti ti-circle-check"></i> Siap diproses';
                weightStatus.style.color = '#10b981'; 
                submitBtn.disabled = false;
            } else {
                totalDisplay.style.color = '#ef4444'; 
                weightStatus.innerHTML = '<i class="ti ti-alert-triangle"></i> Total harus 100%';
                weightStatus.style.color = '#ef4444';
                submitBtn.disabled = true;
            }
        }

        sliders.forEach(slider => {
            slider.addEventListener('input', updateTotal);
        });

        updateTotal();

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

    function confirmDelete(id, name) {
        if (confirm(`Apakah Anda yakin ingin menghapus kriteria "${name}"?\nPenghapusan ini akan ikut menghapus seluruh data penilaian alternatif terkait.`)) {
            const form = document.getElementById('delete-form');
            form.action = `/kriteria/${id}`;
            form.submit();
        }
    }
</script>
@endsection