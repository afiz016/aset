@extends('layouts.app')

@section('title', 'Tambah Kriteria Baru')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');
    
    /* ====================================================
       PENGATURAN VARIABEL WARNA (SYNCED WITH INDEX & DASHBOARD)
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
        padding: 28px;
        transition: all 0.3s;
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

    /* PREMIUM CONTROLS STYLE */
    .form-control-premium {
        background: var(--color-background-primary) !important;
        border: 1px solid var(--color-border-secondary) !important;
        color: var(--color-text-primary) !important;
        border-radius: 10px;
        padding: 12px 16px;
        transition: all 0.2s ease-in-out;
    }
    .form-control-premium:focus {
        border-color: var(--accent-cyan) !important;
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.25) !important;
    }

    /* 🛠️ PERBAIKAN: Efek Kontras Tulisan Contoh (Placeholder) */
    .form-control-premium::placeholder {
        color: var(--color-text-secondary) !important;
        opacity: 0.6 !important;
    }

    .form-select-premium {
        background: var(--color-background-primary) !important;
        border: 1px solid var(--color-border-secondary) !important;
        color: var(--color-text-primary) !important;
        border-radius: 10px;
        padding: 12px 16px;
        transition: all 0.2s ease-in-out;
    }
    .form-select-premium:focus {
        border-color: var(--accent-cyan) !important;
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.25) !important;
    }

    /* BUTTON ACTION GLOW */
    .btn-save-premium {
        background: linear-gradient(90deg, var(--accent-cyan) 0%, #4dabf7 100%);
        color: #0a0f1d !important;
        border: none !important;
        border-radius: 12px;
        padding: 14px 28px;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 14px rgba(0, 212, 255, 0.25);
        width: 100%;
    }
    .btn-save-premium:hover {
        background: linear-gradient(90deg, #00bfe6 0%, #339af0 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 212, 255, 0.45);
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
</style>

<header class="w-full h-16 border-b d-flex justify-content-end align-items-center px-4 gap-3" style="background: var(--color-background-secondary); border-color: var(--color-border-tertiary) !important;">
    <div class="d-flex align-items-center gap-3">
        <div class="icon-btn" id="themeToggle" title="Ubah Tema"><i class="ti ti-sun" id="themeIcon" aria-hidden="true"></i></div>
        <div class="icon-btn" onclick="window.location.reload();" title="Refresh Data"><i class="ti ti-refresh" aria-hidden="true"></i></div>
    </div>
</header>

<div class="D-container">
    
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

    <div class="mb-2">
        <h2 class="fw-bold mb-1" style="color: var(--color-text-primary); font-size: 24px;">Tambah Parameter Kriteria Baru</h2>
        <p style="color: var(--color-text-secondary); font-size: 14px;" class="mb-0">Daftarkan parameter ukur baru ke dalam sistem perhitungan solusi ideal TOPSIS.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 text-danger rounded-3 p-3" style="background: rgba(239, 68, 68, 0.1);">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kriteria.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card-premium d-flex flex-column gap-4">
                    
                    <div class="form-group d-flex flex-column gap-2">
                        <label for="kode_kriteria" class="fw-bold" style="color: var(--color-text-primary); font-size: 14px;">
                            <i class="ti ti-hash text-info me-1"></i> Kode Kriteria
                        </label>
                        <input type="text" 
                               name="kode_kriteria" 
                               id="kode_kriteria" 
                               class="form-control form-control-premium" 
                               placeholder="Contoh: C6" 
                               value="{{ old('kode_kriteria') }}" 
                               required>
                        <small style="color: var(--color-text-secondary); font-size: 11px;">Gunakan urutan kode kriteria berikutnya agar terstruktur rapi.</small>
                    </div>

                    <div class="form-group d-flex flex-column gap-2">
                        <label for="nama_kriteria" class="fw-bold" style="color: var(--color-text-primary); font-size: 14px;">
                            <i class="ti ti-abc text-info me-1"></i> Nama Kriteria
                        </label>
                        <input type="text" 
                               name="nama_kriteria" 
                               id="nama_kriteria" 
                               class="form-control form-control-premium" 
                               placeholder="Contoh: Likuiditas Pasar atau Volume 24 Jam" 
                               value="{{ old('nama_kriteria') }}" 
                               required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 form-group d-flex flex-column gap-2">
                            <label for="bobot" class="fw-bold" style="color: var(--color-text-primary); font-size: 14px;">
                                <i class="ti ti-percentage text-info me-1"></i> Bobot Awal (%)
                            </label>
                            <input type="number" 
                                   name="bobot" 
                                   id="bobot" 
                                   min="0" 
                                   max="100" 
                                   class="form-control form-control-premium" 
                                   placeholder="0 - 100" 
                                   value="{{ old('bobot', 0) }}" 
                                   required>
                        </div>

                        <div class="col-md-6 d-flex flex-column gap-2">
                            <label for="jenis" class="fw-bold" style="color: var(--color-text-primary); font-size: 14px;">
                                <i class="ti ti-adjustments text-info me-1"></i> Jenis Kriteria (Tipe)
                            </label>
                            <select name="jenis" id="jenis" class="form-select form-select-premium" required>
                                <option value="benefit" {{ old('jenis') == 'benefit' ? 'selected' : '' }}>BENEFIT (Keuntungan)</option>
                                <option value="cost" {{ old('jenis') == 'cost' ? 'selected' : '' }}>COST (Biaya/Beban)</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4 d-flex flex-column gap-3">
                <div class="glass-card-premium" style="border-left: 4px solid var(--accent-cyan); padding: 22px;">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-text-primary); font-size: 15px;">
                        <i class="ti ti-info-circle text-info"></i> Aturan Parameter SPK
                    </h5>
                    <div class="d-flex flex-column gap-3" style="color: var(--color-text-secondary); font-size: 12px; line-height: 1.5;">
                        <div>
                            <strong class="text-white d-block mb-1">1. Tipe Benefit:</strong>
                            Nilai indikator variabel yang jika <span class="text-success fw-bold">semakin besar</span> angkanya, maka alternatif aset tersebut akan dinilai semakin direkomendasikan.
                        </div>
                        <div>
                            <strong class="text-white d-block mb-1">2. Tipe Cost:</strong>
                            Nilai indikator pengeluaran yang jika <span class="text-danger fw-bold">semakin kecil</span> angkanya, maka alternatif aset tersebut justru akan dinilai semakin ideal untuk dipilih.
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <button type="submit" class="btn-save-premium">
                        <i class="ti ti-device-floppy"></i> Simpan Parameter
                    </button>
                    <a href="{{ route('kriteria.index') }}" class="btn text-center text-decoration-none py-2 border-0" style="color: var(--color-text-secondary); font-size: 13px; font-weight: 500; background: rgba(255,255,255,0.02); border-radius: 10px;">
                        Batal & Kembali
                    </a>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const htmlElement = document.documentElement;
        const bodyElement = document.body;
        const themeIcon = document.getElementById('themeIcon');
        const themeToggleBtn = document.getElementById('themeToggle');

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