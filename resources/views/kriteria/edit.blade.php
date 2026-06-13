@extends('layouts.app')

@section('title', 'Edit Kriteria')

@section('content')
<style>
    /* ====================================================
       TEMA & VARIABEL WARNA (DARK/LIGHT MODE)
       ==================================================== */
    :root {
        --bg-main: #0f172a; --text-main: #ffffff; --text-muted: #94a3b8;
        --card-bg: rgba(255, 255, 255, 0.02); --card-border: rgba(255, 255, 255, 0.05);
        --input-bg: rgba(255, 255, 255, 0.03);
        --panel-bg: rgba(15, 23, 42, 0.6); 
    }

    [data-theme="light"] {
        --bg-main: #f8fafc; --text-main: #0f172a; --text-muted: #64748b;
        --card-bg: #ffffff; --card-border: rgba(0, 0, 0, 0.08);
        --input-bg: rgba(0, 0, 0, 0.02);
        --panel-bg: rgba(255, 255, 255, 0.8);
    }

    .page-wrapper {
        background-color: var(--bg-main); color: var(--text-main);
        transition: background-color 0.5s ease, color 0.5s ease;
        min-height: 100vh; position: relative; overflow-x: hidden;
    }

    /* --- AMBIENT GLOW EFFECT --- */
    .ambient-glow {
        position: absolute; top: 30%; left: 50%; transform: translate(-50%, -50%);
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(255, 193, 7, 0.05) 0%, transparent 65%);
        border-radius: 50%; z-index: 0; pointer-events: none;
    }

    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
    .animate-entrance { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; z-index: 1; position: relative; }

    /* --- DATA PANEL GLASSMORPHISM --- */
    .form-panel {
        background: var(--panel-bg);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--card-border); border-radius: 24px; padding: 35px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }

    /* --- PREMIUM INPUT FIELD --- */
    .form-control-modern {
        background: var(--input-bg); border: 1px solid var(--card-border); color: var(--text-main) !important;
        border-radius: 14px; padding: 12px 18px; transition: all 0.3s ease;
    }
    .form-control-modern:focus {
        background: rgba(255, 255, 255, 0.01); border-color: #ffc107; 
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.2); outline: none;
    }

    /* --- INTERACTIVE RADIO CARDS --- */
    .attribute-card {
        background: var(--input-bg); border: 1px solid var(--card-border);
        border-radius: 16px; padding: 16px; cursor: pointer; transition: all 0.3s ease;
        display: flex; align-items: center; position: relative;
    }
    .attribute-card:hover { transform: translateY(-2px); background: rgba(255,255,255,0.05); }
    
    .radio-input { position: absolute; opacity: 0; width: 0; height: 0; }
    
    /* State Terpilih - Benefit */
    .radio-input[value="benefit"]:checked + .attribute-card {
        border-color: #20c997; background: rgba(32, 201, 151, 0.08);
        box-shadow: 0 0 15px rgba(32, 201, 151, 0.15);
    }
    /* State Terpilih - Cost */
    .radio-input[value="cost"]:checked + .attribute-card {
        border-color: #ff6b6b; background: rgba(255, 107, 107, 0.08);
        box-shadow: 0 0 15px rgba(255, 107, 107, 0.15);
    }

    .circle-indicator {
        width: 20px; height: 20px; border: 2px solid var(--text-muted);
        border-radius: 50%; margin-right: 15px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;
    }
    .radio-input:checked + .attribute-card .circle-indicator { border-color: currentColor; background: currentColor; }

    /* --- BUTTONS GRADIENT WARNING (EDIT STYLE) --- */
    .btn-gradient-warning {
        background: linear-gradient(135deg, #ffc107, #ff922b); color: #000;
        border: none; border-radius: 12px; font-weight: 700; padding: 12px 30px; transition: all 0.3s ease; text-decoration: none;
    }
    .btn-gradient-warning:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255, 146, 43, 0.4); color: #000; }

    .btn-outline-modern {
        background: transparent; border: 1px solid var(--card-border); color: var(--text-muted);
        border-radius: 12px; padding: 12px 24px; transition: all 0.2s ease; text-decoration: none;
    }
    .btn-outline-modern:hover { background: rgba(255, 255, 255, 0.05); color: var(--text-main); border-color: var(--text-muted); }

    .theme-toggle-btn { background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-main); transition: all 0.2s ease; }
</style>

<div class="page-wrapper p-4 p-lg-5 flex-grow-1">
    <div class="ambient-glow"></div>

    <div class="container z-index-1 position-relative" style="max-width: 850px;">
        
        <div class="d-flex justify-content-end mb-4 animate-entrance" style="animation-delay: 0.05s;">
            <button id="themeToggle" class="btn theme-toggle-btn rounded-pill px-4 py-2 shadow-sm fw-bold">
                <i class="bi bi-sun-fill text-warning me-2" id="themeIcon"></i> <span id="themeText">Mode Terang</span>
            </button>
        </div>

        <div class="d-flex align-items-center mb-5 animate-entrance" style="animation-delay: 0.1s;">
            <div class="bg-warning bg-opacity-10 p-3 rounded-4 me-4 border border-warning border-opacity-25 shadow-sm d-flex justify-content-center align-items-center" style="width: 70px; height: 70px;">
                <i class="bi bi-pencil-square text-warning" style="font-size: 2.3rem; text-shadow: 0 0 20px rgba(255, 193, 7, 0.8);"></i>
            </div>
            <div>
                <h1 class="fw-bolder mb-1" style="color: var(--text-main);">Edit Kriteria</h1>
                <p class="mb-0 fs-6" style="color: var(--text-muted);">Perbarui konfigurasi kode, nama, bobot, atau atribut kriteria SPK TOPSIS.</p>
            </div>
        </div>

        <div class="form-panel animate-entrance" style="animation-delay: 0.2s;">
            
            @if ($errors->any())
                <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-3 mb-4">
                    <ul class="mb-0 fw-semibold">
                        @foreach ($errors->all() as $error)
                            <li><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kriteria.update', $kriteria->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase tracking-wider" style="color: var(--text-muted);">Kode Kriteria</label>
                        <input type="text" name="kode_kriteria" class="form-control form-control-modern fw-bold text-info" value="{{ old('kode_kriteria', $kriteria->kode_kriteria) }}" placeholder="Contoh: C1" required autocomplete="off">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold small text-uppercase tracking-wider" style="color: var(--text-muted);">Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control form-control-modern" value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}" placeholder="Contoh: Market Sentiment" required autocomplete="off">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-uppercase tracking-wider" style="color: var(--text-muted);">Bobot Kriteria (W)</label>
                    <input type="number" step="any" name="bobot" class="form-control form-control-modern font-mono" value="{{ old('bobot', $kriteria->bobot) }}" placeholder="Masukkan nilai bobot" required autocomplete="off">
                    <div class="form-text opacity-50 mt-1" style="color: var(--text-muted); font-size: 0.8rem;">*Bobot ini menentukan persentase signifikansi kriteria dalam kalkulasi TOPSIS.</div>
                </div>

                <div class="mb-5">
                    <label class="form-label fw-bold small text-uppercase tracking-wider d-block mb-3" style="color: var(--text-muted);">Jenis / Atribut Kriteria</label>
                    <div class="row g-3">
                        
                        <div class="col-6">
                            <input type="radio" name="jenis" id="type_benefit" value="benefit" class="radio-input" {{ old('jenis', strtolower($kriteria->jenis)) == 'benefit' ? 'checked' : '' }}>
                            <label for="type_benefit" class="attribute-card text-success">
                                <div class="circle-indicator"></div>
                                <div>
                                    <h6 class="fw-bold mb-1"><i class="bi bi-arrow-up-circle-fill me-1"></i> Benefit</h6>
                                    <small class="text-muted d-none d-sm-block">Nilai kriteria semakin tinggi semakin menguntungkan.</small>
                                </div>
                            </label>
                        </div>

                        <div class="col-6">
                            <input type="radio" name="jenis" id="type_cost" value="cost" class="radio-input" {{ old('jenis', strtolower($kriteria->jenis)) == 'cost' ? 'checked' : '' }}>
                            <label for="type_cost" class="attribute-card text-danger">
                                <div class="circle-indicator"></div>
                                <div>
                                    <h6 class="fw-bold mb-1"><i class="bi bi-arrow-down-circle-fill me-1"></i> Cost</h6>
                                    <small class="text-muted d-none d-sm-block">Nilai kriteria semakin rendah semakin menguntungkan.</small>
                                </div>
                            </label>
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 pt-3 border-top border-secondary border-opacity-10">
                    <a href="{{ route('kriteria.index') }}" class="btn btn-outline-modern fw-bold">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-gradient-warning">
                        <i class="bi bi-save-fill me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Kontrol Tema Dark/Light Terintegrasi
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
            } else {
                themeIcon.className = 'bi bi-sun-fill text-warning me-2';
                themeText.innerText = 'Mode Terang';
            }
        }
    });
</script>
@endsection