@extends('layouts.app')

@section('title', 'Tambah Aset Digital')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap');

    :root {
        --bg-primary:    #0a0f1d;
        --bg-secondary:  #0f172a;
        --bg-card:       #111827;
        --text-primary:  #f1f5f9;
        --text-muted:    #64748b;
        --border:        rgba(0, 212, 255, 0.10);
        --border-hover:  rgba(0, 212, 255, 0.35);
        --accent:        #00d4ff;
        --accent-dim:    rgba(0, 212, 255, 0.08);
        --accent-glow:   0 0 18px rgba(0, 212, 255, 0.18);
        --success:       #22c55e;
        --warning:       #f59e0b;
        --danger:        #ef4444;
    }
    [data-theme="light"], .light {
        --bg-primary:   #f1f5f9;
        --bg-secondary: #ffffff;
        --bg-card:      #f8fafc;
        --text-primary: #0f172a;
        --text-muted:   #64748b;
        --border:       rgba(15, 23, 42, 0.10);
        --border-hover: rgba(2, 132, 199, 0.35);
        --accent:       #0284c7;
        --accent-dim:   rgba(2, 132, 199, 0.06);
    }

    body, html, main, .main-content, .content-wrapper, #app, .wrapper, .page-wrapper {
        background-color: var(--bg-primary) !important;
    }

    .create-wrap {
        font-family: 'Space Grotesk', sans-serif;
        color: var(--text-primary);
        padding: 28px 24px;
        max-width: 900px;
        margin: 0 auto;
    }

    /* ── TOPBAR ── */
    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px 20px;
        margin-bottom: 28px;
    }
    .topbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .topbar-icon {
        width: 42px; height: 42px;
        background: var(--accent-dim);
        border: 1px solid var(--border-hover);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: var(--accent);
        font-size: 20px;
    }
    .topbar-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: 0.3px;
    }
    .topbar-sub {
        font-size: 11.5px;
        color: var(--text-muted);
        margin-top: 1px;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-back:hover {
        border-color: var(--border-hover);
        color: var(--text-primary);
        background: var(--accent-dim);
    }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 28px 28px;
        margin-bottom: 20px;
    }

    .section-label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border);
    }
    .section-label-icon {
        width: 34px; height: 34px;
        background: var(--accent-dim);
        border: 1px solid var(--border-hover);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: var(--accent);
        font-size: 16px;
        flex-shrink: 0;
    }
    .section-label-text {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
    }
    .section-label-sub {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 1px;
    }

    /* ── FIELD ── */
    .field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .field-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .field-input {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 13.5px;
        font-family: 'Space Grotesk', sans-serif;
        padding: 11px 14px;
        width: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }
    .field-input::placeholder { color: var(--text-muted); opacity: 0.6; }
    .field-input:focus {
        border-color: var(--accent);
        box-shadow: var(--accent-glow);
        background: var(--bg-secondary);
    }
    .field-input.is-invalid {
        border-color: var(--danger);
        box-shadow: 0 0 12px rgba(239,68,68,0.15);
    }
    .invalid-feedback {
        font-size: 11px;
        color: var(--danger);
        margin-top: 4px;
    }

    /* ── KRITERIA GRID ── */
    .kriteria-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    @media (max-width: 640px) {
        .kriteria-grid { grid-template-columns: 1fr; }
    }

    .kriteria-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px 18px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .kriteria-card:focus-within {
        border-color: var(--border-hover);
        box-shadow: var(--accent-glow);
    }
    .kriteria-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .kriteria-code {
        font-size: 10px;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
        color: var(--accent);
        background: var(--accent-dim);
        border: 1px solid rgba(0,212,255,0.2);
        border-radius: 6px;
        padding: 3px 8px;
        letter-spacing: 0.5px;
    }
    .kriteria-badge {
        font-size: 9.5px;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 6px;
        letter-spacing: 0.5px;
    }
    .badge-benefit {
        background: rgba(34,197,94,0.10);
        color: #22c55e;
        border: 1px solid rgba(34,197,94,0.2);
    }
    .badge-cost {
        background: rgba(239,68,68,0.10);
        color: #ef4444;
        border: 1px solid rgba(239,68,68,0.2);
    }
    .kriteria-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 10px;
        line-height: 1.4;
    }

    /* number input — hide arrows */
    .field-input[type=number]::-webkit-inner-spin-button,
    .field-input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; }
    .field-input[type=number] { -moz-appearance: textfield; }

    /* ── FOOTER ACTIONS ── */
    .form-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        margin-top: 4px;
    }
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 22px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 600;
        font-family: 'Space Grotesk', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-cancel:hover {
        border-color: var(--border-hover);
        color: var(--text-primary);
        background: var(--accent-dim);
    }
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 28px;
        border-radius: 10px;
        border: none;
        background: var(--accent);
        color: #0a0f1d;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Space Grotesk', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 0.3px;
    }
    .btn-submit:hover {
        opacity: 0.88;
        box-shadow: 0 4px 18px rgba(0, 212, 255, 0.3);
        transform: translateY(-1px);
    }
    .btn-submit:active { transform: translateY(0); }

    /* ── ALERT ── */
    .alert-error {
        background: rgba(239,68,68,0.08);
        border: 1px solid rgba(239,68,68,0.25);
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        font-size: 12.5px;
        color: #fca5a5;
    }
    .alert-error ul { margin: 6px 0 0 16px; padding: 0; }

    /* theme toggle */
    .icon-btn {
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border);
        border-radius: 10px; cursor: pointer;
        color: var(--text-muted); background: transparent;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .icon-btn:hover { color: var(--accent); border-color: var(--border-hover); background: var(--accent-dim); }
</style>

<div class="create-wrap">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-icon"><i class="ti ti-circle-plus"></i></div>
            <div>
                <div class="topbar-title">Tambah Aset Digital</div>
                <div class="topbar-sub">Isi data aset dan nilai penilaian untuk setiap kriteria</div>
            </div>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <div class="icon-btn" id="themeToggle" title="Ubah Tema">
                <i class="ti ti-sun" id="themeIcon"></i>
            </div>
            <a href="{{ route('aset-digital.index') }}" class="btn-back">
                <i class="ti ti-arrow-left" style="font-size:14px;"></i> Kembali
            </a>
        </div>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
    <div class="alert-error">
        <strong><i class="ti ti-alert-circle me-1"></i>Terdapat kesalahan pada form:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('aset-digital.store') }}" method="POST" id="createForm">
        @csrf

        {{-- SECTION 1: INFO ASET --}}
        <div class="form-card">
            <div class="section-label">
                <div class="section-label-icon"><i class="ti ti-database"></i></div>
                <div>
                    <div class="section-label-text">Informasi Aset</div>
                    <div class="section-label-sub">Identitas dasar aset digital yang akan dinilai</div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div class="field-group">
                    <label class="field-label">Nama Aset / Item / Skin <span style="color:var(--danger)">*</span></label>
                    <input
                        type="text"
                        name="nama_aset"
                        class="field-input {{ $errors->has('nama_aset') ? 'is-invalid' : '' }}"
                        value="{{ old('nama_aset') }}"
                        placeholder="Contoh: AK-47 Asiimov, Bored Ape #7804"
                        required
                        autocomplete="off"
                    >
                    @error('nama_aset')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label">Jenis Aset <span style="color:var(--danger)">*</span></label>
                    <input
                        type="text"
                        name="jenis_aset"
                        class="field-input {{ $errors->has('jenis_aset') ? 'is-invalid' : '' }}"
                        value="{{ old('jenis_aset') }}"
                        placeholder="Contoh: CS2 Skin, Ethereum NFT, Steam Item"
                        required
                        autocomplete="off"
                    >
                    @error('jenis_aset')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- SECTION 2: PENILAIAN KRITERIA --}}
        <div class="form-card">
            <div class="section-label">
                <div class="section-label-icon"><i class="ti ti-adjustments-horizontal"></i></div>
                <div>
                    <div class="section-label-text">Penilaian Kriteria</div>
                    <div class="section-label-sub">Masukkan skor numerik untuk setiap kriteria (angka desimal diperbolehkan)</div>
                </div>
            </div>

            @if($kriterias->isEmpty())
                <div style="text-align:center;padding:40px 20px;color:var(--text-muted);">
                    <i class="ti ti-mood-empty" style="font-size:40px;display:block;margin-bottom:10px;"></i>
                    <p style="font-size:13px;">Belum ada kriteria. <a href="{{ route('kriteria.create') }}" style="color:var(--accent);">Tambah kriteria</a> terlebih dahulu.</p>
                </div>
            @else
            <div class="kriteria-grid">
                @foreach($kriterias as $k)
                <div class="kriteria-card">
                    <div class="kriteria-card-header">
                        <span class="kriteria-code">{{ $k->kode_kriteria }}</span>
                        <span class="kriteria-badge {{ strtolower($k->jenis) === 'benefit' ? 'badge-benefit' : 'badge-cost' }}">
                            {{ ucfirst($k->jenis) }}
                        </span>
                    </div>
                    <div class="kriteria-name">{{ $k->nama_kriteria }}</div>
                    <div class="field-group">
                        <label class="field-label">Nilai Skor <span style="color:var(--danger)">*</span></label>
                        <input
                            type="number"
                            step="any"
                            name="nilai[{{ $k->id }}]"
                            class="field-input {{ $errors->has('nilai.'.$k->id) ? 'is-invalid' : '' }}"
                            value="{{ old('nilai.'.$k->id) }}"
                            placeholder="0"
                            required
                        >
                        @error('nilai.'.$k->id)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- FORM FOOTER --}}
            <div class="form-footer">
                <a href="{{ route('aset-digital.index') }}" class="btn-cancel">
                    <i class="ti ti-x" style="font-size:13px;"></i> Batal
                </a>
                <button type="submit" class="btn-submit">
                    <i class="ti ti-device-floppy" style="font-size:15px;"></i> Simpan Aset
                </button>
            </div>
        </div>

    </form>
</div>

<script>
    // Theme toggle
    const btn   = document.getElementById('themeToggle');
    const icon  = document.getElementById('themeIcon');
    const html  = document.documentElement;

    function applyTheme(t) {
        html.setAttribute('data-theme', t);
        html.classList.toggle('light', t === 'light');
        icon.className = t === 'light' ? 'ti ti-moon' : 'ti ti-sun text-warning';
    }
    applyTheme(localStorage.getItem('theme') || 'dark');
    btn.addEventListener('click', () => {
        const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', next);
        applyTheme(next);
    });
</script>
@endsection
