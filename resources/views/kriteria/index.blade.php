@extends('layouts.app')

@section('title', 'Pembobotan Kriteria')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');
    
    :root {
        --color-background-primary: #0a0f1d;
        --color-background-secondary: #0f172a;
        --color-text-primary: #ffffff;
        --color-text-secondary: #94a3b8;
        --color-border-secondary: rgba(0, 212, 255, 0.16);
        --color-border-tertiary: rgba(0, 212, 255, 0.06);
        --accent-cyan: #00d4ff;
    }

    .D-container {
        font-family: 'Space Grotesk', sans-serif;
        background: var(--color-background-primary);
        padding: 28px;
        display: flex;
        flex-direction: column;
        gap: 26px;
        min-height: 100vh;
    }

    .glass-card-premium {
        background: var(--color-background-secondary);
        border: 0.5px solid var(--color-border-tertiary);
        border-radius: 16px;
        padding: 24px;
        transition: border-color 0.3s;
    }
    .glass-card-premium:hover {
        border-color: rgba(0, 212, 255, 0.2);
    }

    /* ====================================================
       🔥 EFEK PREMIUM GLOW & ANIMASI TOMBOL TAMBAH KRITERIA
       ==================================================== */
    .btn-add-premium {
        background: transparent;
        color: var(--accent-cyan) !important;
        border: 1px solid var(--color-border-secondary) !important;
        border-radius: 12px;
        padding: 10px 22px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 0 0 rgba(0, 212, 255, 0);
    }
    .btn-add-premium:hover {
        background: rgba(0, 212, 255, 0.08) !important;
        border-color: var(--accent-cyan) !important;
        color: #ffffff !important;
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.35);
    }
    /* Mikro-interaksi untuk ikon tambah (+) agar berputar saat di-hover */
    .btn-add-premium i {
        display: inline-block;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .btn-add-premium:hover i {
        transform: rotate(90deg) scale(1.2);
    }

    /* Custom range styling */
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

    /* Tombol Hapus Kecil */
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

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h2 class="text-white fw-bold mb-1" style="font-size: 24px;">Langkah 1: Pembobotan Kriteria</h2>
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

    <form action="{{ route('kriteria.update-batch') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card-premium d-flex flex-column gap-4">
                    
                    @php $totalBobotAwal = 0; @endphp
                    @forelse($kriterias as $k)
                        @php 
                            $totalBobotAwal += $k->bobot;
                            
                            $icon = 'ti-coin';
                            $iconColor = '#ef4444';
                            $badgeStyle = 'background: rgba(239, 68, 68, 0.1); color: #ef4444;';
                            
                            if(stripos($k->nama_kriteria, 'volume') !== false || stripos($k->nama_kriteria, 'transaksi') !== false) {
                                $icon = 'ti-activity';
                                $iconColor = 'var(--accent-cyan)';
                                $badgeStyle = 'background: rgba(0, 212, 255, 0.1); color: var(--accent-cyan);';
                            } elseif(stripos($k->nama_kriteria, 'langka') !== false || stripos($k->nama_kriteria, 'rarity') !== false) {
                                $icon = 'ti-diamond';
                                $iconColor = '#6366f1';
                                $badgeStyle = 'background: rgba(99, 102, 241, 0.1); color: #6366f1;';
                            } elseif(stripos($k->nama_kriteria, 'sentiment') !== false || stripos($k->nama_kriteria, 'tren') !== false) {
                                $icon = 'ti-trending-up';
                                $iconColor = '#f59e0b';
                                $badgeStyle = 'background: rgba(245, 158, 11, 0.1); color: #f59e0b;';
                            } elseif(stripos($k->nama_kriteria, 'likuid') !== false) {
                                $icon = 'ti-droplet';
                                $iconColor = 'var(--accent-cyan)';
                                $badgeStyle = 'background: rgba(0, 212, 255, 0.1); color: var(--accent-cyan);';
                            }
                        @endphp

                        <div class="d-flex flex-column gap-2" id="row-{{ $k->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="text-white fw-bold d-flex align-items-center gap-2" style="font-size: 14px;">
                                    <div class="d-flex align-items-center justify-content-center rounded" style="width: 28px; height: 26px; background: rgba(255,255,255,0.02);">
                                        <i class="ti {{ $icon }}" style="color: {{ $iconColor }}; font-size: 14px;"></i>
                                    </div>
                                    <span class="text-info font-mono me-1">[{{ $k->kode_kriteria }}]</span> {{ $k->nama_kriteria }} 
                                    <span class="badge rounded-pill mx-1" style="{{ $badgeStyle }} font-size: 10px;">{{ ucfirst($k->jenis) }}</span>
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
                        <div class="text-center text-muted py-4">
                            <i class="ti ti-folder-off fs-3 mb-2"></i>
                            <p class="mb-0">Belum ada parameter kriteria. Silakan tambah kriteria baru.</p>
                        </div>
                    @endforelse

                </div>

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

            <div class="col-lg-4 d-flex flex-column gap-3">
                <div class="glass-card-premium" style="border-left: 4px solid var(--accent-cyan);">
                    <h5 class="text-white fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 15px;">
                        <i class="ti ti-info-circle text-info"></i> Mengapa Ini Penting?
                    </h5>
                    <p class="mb-3 text-white-50" style="font-size: 13px; line-height: 1.5;">
                        Metode <strong>TOPSIS</strong> menghitung jarak kedekatan relatif sebuah aset terhadap solusi ideal positif dan negatif.
                    </p>
                    <div class="d-flex flex-column gap-2 text-white-50" style="font-size: 12px; line-height: 1.4;">
                        <div class="d-flex gap-2">
                            <span class="text-info fw-bold">1.</span>
                            <span>Bobot kriteria menentukan "derajat pengaruh" variabel terhadap skor akhir.</span>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="text-info fw-bold">2.</span>
                            <span>Kriteria "Harga" bersifat cost (semakin kecil semakin baik).</span>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="text-info fw-bold">3.</span>
                            <span>Yield & Rarity bersifat benefit (semakin besar semakin baik).</span>
                        </div>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="btn-accent-premium">
                    Simpan & Lanjut ke Alternatif <i class="ti ti-arrow-right"></i>
                </button>
                <a href="/dashboard" class="btn text-center text-decoration-none py-2 text-white-50 border-0" style="font-size: 13px; font-weight: 500; background: rgba(255,255,255,0.02); border-radius: 10px;">
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