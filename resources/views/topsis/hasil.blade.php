@extends('layouts.app')

@section('title', 'Hasil Perhitungan TOPSIS')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="mb-2">
        <h4 class="text-info fw-bold"><i class="bi bi-calculator-fill me-2"></i>Matriks Perhitungan TOPSIS</h4>
        <p class="text-muted small">Lihat detail kalkulasi untuk setiap tahap analisis TOPSIS</p>
    </div>
    
    <!-- Accordion untuk Matriks Perantara -->
    <div class="accordion mb-5" id="accordionMatriks" style="box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);">
        <!-- Matriks Keputusan Awal (X) -->
        <div class="accordion-item" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.05), rgba(255, 152, 0, 0.02)); border: 1px solid rgba(255, 193, 7, 0.2) !important;">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMatriksX" style="background: linear-gradient(90deg, rgba(255, 193, 7, 0.1), transparent); color: #ffc107; font-size: 1.1rem;">
                    <i class="bi bi-123 me-2" style="color: #ffc107; font-size: 1.3rem;"></i><span>Matriks Keputusan Awal (X)</span>
                    <span class="badge bg-warning text-dark ms-auto me-2">Step 1</span>
                </button>
            </h2>
            <div id="collapseMatriksX" class="accordion-collapse collapse" data-bs-parent="#accordionMatriks">
                <div class="accordion-body p-4" style="background: rgba(255, 193, 7, 0.02);">
                    <p class="text-muted small mb-3"><i class="bi bi-info-circle me-2"></i>Matriks keputusan adalah data penilaian awal untuk setiap alternatif terhadap setiap kriteria.</p>
                    <div class="table-responsive" style="border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);">
                        <table class="table table-dark table-sm table-striped align-middle mb-0">
                            <thead style="background: linear-gradient(90deg, #ffc107, #ff9800);">
                                <tr class="text-dark">
                                    <th class="text-center ps-3 fw-bold">Aset Digital</th>
                                    @foreach($hasilTopsis['kriterias'] as $k)
                                        <th class="text-center fw-bold">{{ $k->kode_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilTopsis['aset_digitals'] as $aset)
                                <tr style="border-bottom: 1px solid rgba(255, 193, 7, 0.2);">
                                    <td class="ps-3 fw-semibold" style="color: #ffc107;">{{ $aset->nama_aset }}</td>
                                    @foreach($hasilTopsis['kriterias'] as $k)
                                        <td class="text-center fw-mono text-warning">{{ number_format($hasilTopsis['matriks_x'][$aset->id][$k->id], 4) }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matriks Ternormalisasi (R) -->
        <div class="accordion-item bg-secondary bg-opacity-10 border-secondary border-opacity-25">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed text-white bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMatriksR">
                    <i class="bi bi-table me-2 text-info"></i><span class="fw-bold">Matriks Ternormalisasi (R)</span>
                </button>
            </h2>
            <div id="collapseMatriksR" class="accordion-collapse collapse" data-bs-parent="#accordionMatriks">
                <div class="accordion-body p-4">
                    <p class="text-muted small mb-3">Matriks ternormalisasi diperoleh dengan membagi setiap elemen dengan akar dari jumlah kuadrat elemen dalam kolom yang sama.</p>
                    <div class="table-responsive">
                        <table class="table table-dark table-sm table-striped align-middle border border-secondary border-opacity-50">
                            <thead>
                                <tr class="table-dark border-bottom border-secondary">
                                    <th class="text-center ps-3">Aset Digital</th>
                                    @foreach($hasilTopsis['kriterias'] as $k)
                                        <th class="text-center text-info">{{ $k->kode_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilTopsis['aset_digitals'] as $aset)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $aset->nama_aset }}</td>
                                    @foreach($hasilTopsis['kriterias'] as $k)
                                        <td class="text-center fw-mono text-success">{{ number_format($hasilTopsis['matriks_r'][$aset->id][$k->id], 6) }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matriks Ternormalisasi Terbobot (V) -->
        <div class="accordion-item bg-secondary bg-opacity-10 border-secondary border-opacity-25">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed text-white bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMatriksV">
                    <i class="bi bi-table me-2 text-info"></i><span class="fw-bold">Matriks Ternormalisasi Terbobot (V)</span>
                </button>
            </h2>
            <div id="collapseMatriksV" class="accordion-collapse collapse" data-bs-parent="#accordionMatriks">
                <div class="accordion-body p-4">
                    <p class="text-muted small mb-3">Matriks ternormalisasi terbobot diperoleh dengan mengalikan setiap elemen matriks ternormalisasi dengan bobot kriteria yang sesuai.</p>
                    <div class="table-responsive">
                        <table class="table table-dark table-sm table-striped align-middle border border-secondary border-opacity-50">
                            <thead>
                                <tr class="table-dark border-bottom border-secondary">
                                    <th class="text-center ps-3">Aset Digital</th>
                                    @foreach($hasilTopsis['kriterias'] as $k)
                                        <th class="text-center text-info">{{ $k->kode_kriteria }} <br><small class="text-warning">(w={{ number_format($k->bobot, 2) }})</small></th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilTopsis['aset_digitals'] as $aset)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $aset->nama_aset }}</td>
                                    @foreach($hasilTopsis['kriterias'] as $k)
                                        <td class="text-center fw-mono text-danger">{{ number_format($hasilTopsis['matriks_v'][$aset->id][$k->id], 6) }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solusi Ideal Positif & Negatif -->
        <div class="accordion-item bg-secondary bg-opacity-10 border-secondary border-opacity-25">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed text-white bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSolusiIdeal">
                    <i class="bi bi-graph-up-arrow me-2 text-info"></i><span class="fw-bold">Solusi Ideal Positif (A+) & Negatif (A-)</span>
                </button>
            </h2>
            <div id="collapseSolusiIdeal" class="accordion-collapse collapse" data-bs-parent="#accordionMatriks">
                <div class="accordion-body p-4">
                    <p class="text-muted small mb-3">Solusi ideal positif adalah nilai maksimum (untuk benefit) atau minimum (untuk cost) dari setiap kolom matriks V. Solusi ideal negatif adalah sebaliknya.</p>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="card bg-dark border-success border-opacity-50">
                                <div class="card-header bg-success bg-opacity-10 border-success border-opacity-25">
                                    <h6 class="mb-0 text-success fw-bold"><i class="bi bi-arrow-up-right me-2"></i>Solusi Ideal Positif (A+)</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <table class="table table-dark table-sm mb-0">
                                            <thead class="text-success">
                                                <tr>
                                                    <th>Kriteria</th>
                                                    <th class="text-center">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($hasilTopsis['kriterias'] as $k)
                                                <tr>
                                                    <td class="fw-semibold">{{ $k->kode_kriteria }}</td>
                                                    <td class="text-center fw-mono text-success">{{ number_format($hasilTopsis['solusi_ideal_positif'][$k->id], 6) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card bg-dark border-danger border-opacity-50">
                                <div class="card-header bg-danger bg-opacity-10 border-danger border-opacity-25">
                                    <h6 class="mb-0 text-danger fw-bold"><i class="bi bi-arrow-down-left me-2"></i>Solusi Ideal Negatif (A-)</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <table class="table table-dark table-sm mb-0">
                                            <thead class="text-danger">
                                                <tr>
                                                    <th>Kriteria</th>
                                                    <th class="text-center">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($hasilTopsis['kriterias'] as $k)
                                                <tr>
                                                    <td class="fw-semibold">{{ $k->kode_kriteria }}</td>
                                                    <td class="text-center fw-mono text-danger">{{ number_format($hasilTopsis['solusi_ideal_negatif'][$k->id], 6) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jarak Solusi Ideal -->
        <div class="accordion-item bg-secondary bg-opacity-10 border-secondary border-opacity-25">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed text-white bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJarakSolusi">
                    <i class="bi bi-ruler me-2 text-info"></i><span class="fw-bold">Jarak Solusi Ideal (D+ & D-)</span>
                </button>
            </h2>
            <div id="collapseJarakSolusi" class="accordion-collapse collapse" data-bs-parent="#accordionMatriks">
                <div class="accordion-body p-4">
                    <p class="text-muted small mb-3">Jarak solusi ideal adalah jarak Euclidean dari setiap alternatif terhadap solusi ideal positif dan negatif.</p>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover align-middle border border-secondary border-opacity-50">
                            <thead>
                                <tr class="table-dark border-bottom border-secondary text-center">
                                    <th class="ps-3 text-start">Aset Digital</th>
                                    <th style="width: 150px;">D+ (Ideal Positif)</th>
                                    <th style="width: 150px;">D- (Ideal Negatif)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilTopsis['aset_digitals'] as $aset)
                                <tr class="text-center">
                                    <td class="ps-3 fw-semibold text-info">{{ $aset->nama_aset }}</td>
                                    <td class="fw-mono text-success">{{ number_format($hasilTopsis['jarak_solusi_ideal_positif'][$aset->id], 6) }}</td>
                                    <td class="fw-mono text-danger">{{ number_format($hasilTopsis['jarak_solusi_ideal_negatif'][$aset->id], 6) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hasil Akhir / Peringkat -->
    <div class="card text-white rounded-3 shadow-lg overflow-hidden" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.08) 0%, rgba(255, 152, 0, 0.04) 100%); border: 1px solid rgba(255, 193, 7, 0.2) !important;">
        <div class="card-header p-4 fw-bold" style="background: linear-gradient(90deg, #ffc107, #ff9800); color: #000; border: none;">
            <h3 class="mb-0 text-dark"><i class="bi bi-podium-fill me-2"></i>Hasil Peringkat Rekomendasi Investasi (TOPSIS)</h3>
        </div>
        
        <div class="card-body p-4">
            <div class="table-responsive" style="border-radius: 8px; overflow: hidden;">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead style="background: linear-gradient(90deg, rgba(255, 193, 7, 0.15), rgba(255, 152, 0, 0.08));">
                        <tr class="text-center">
                            <th class="ps-3 fw-bold" style="width: 100px;">Ranking</th>
                            <th class="text-start fw-bold">Nama Aset</th>
                            <th class="text-start fw-bold">Jenis</th>
                            <th class="fw-bold">D+ (Ideal +)</th>
                            <th class="fw-bold">D- (Ideal -)</th>
                            <th class="pe-3 fw-bold" style="width: 200px;">Skor Preferensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hasilTopsis['hasil_akhir'] as $item)
                        <tr class="text-center align-middle" style="border-bottom: 1px solid rgba(255, 193, 7, 0.15); transition: all 0.3s;" onmouseover="this.style.background='rgba(255, 193, 7, 0.1)'" onmouseout="this.style.background='transparent'">
                            <td class="ps-3 fw-bold">
                                @if($loop->iteration == 1)
                                    <span class="badge" style="background: linear-gradient(90deg, #ffc107, #ff9800); color: #000; font-size: 0.9rem; padding: 0.6rem 0.8rem;"><i class="bi bi-trophy-fill me-1"></i>🥇 1st</span>
                                @elseif($loop->iteration == 2)
                                    <span class="badge" style="background: linear-gradient(90deg, #c0c0c0, #e8e8e8); color: #000; font-size: 0.9rem; padding: 0.6rem 0.8rem;"><i class="bi bi-award-fill me-1"></i>🥈 2nd</span>
                                @elseif($loop->iteration == 3)
                                    <span class="badge" style="background: linear-gradient(90deg, #cd7f32, #e5a872); color: #fff; font-size: 0.9rem; padding: 0.6rem 0.8rem;"><i class="bi bi-award me-1"></i>🥉 3rd</span>
                                @else
                                    <span class="text-warning fw-bold fs-5">{{ $loop->iteration }}</span>
                                @endif
                            </td>
                            <td class="text-start fw-bold" style="color: #4dabf7;">{{ $item['nama_aset'] }}</td>
                            <td class="text-start"><span class="badge" style="background: rgba(0, 212, 255, 0.2); color: #00d4ff; border: 1px solid rgba(0, 212, 255, 0.3);">{{ $item['jenis_aset'] }}</span></td>
                            <td class="fw-mono text-danger">{{ number_format($item['d_plus'], 4) }}</td>
                            <td class="fw-mono text-success">{{ number_format($item['d_minus'], 4) }}</td>
                            <td class="pe-3">
                                <div class="position-relative" style="padding: 0.5rem 1rem; border-radius: 8px; background: linear-gradient(90deg, rgba(255, 193, 7, 0.2), rgba(255, 152, 0, 0.1)); border: 2px solid rgba(255, 193, 7, 0.4); box-shadow: 0 0 15px rgba(255, 193, 7, 0.2);">
                                    <span class="fw-bold" style="font-size: 1.2rem; background: linear-gradient(90deg, #ffc107, #ff9800); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ number_format($item['preferensi'], 4) }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-calculator-lg fs-2 d-block mb-2 text-secondary"></i>
                                <p class="mb-0">Belum ada data matriks penilaian atau alternatif untuk dihitung.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection