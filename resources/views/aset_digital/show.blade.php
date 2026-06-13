@extends('layouts.app')

@section('title', 'Detail Aset Digital')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card bg-secondary bg-opacity-10 border-secondary border-opacity-25 text-white rounded-3 p-4 shadow">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold text-info"><i class="bi bi-eye-fill me-2"></i>Detail Aset Digital</h3>
                    <a href="{{ route('aset-digital.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-black bg-opacity-20 border border-secondary border-opacity-25">
                            <p class="text-muted small mb-1">Nama Aset</p>
                            <p class="fw-bold text-info fs-5">{{ $aset->nama_aset }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-black bg-opacity-20 border border-secondary border-opacity-25">
                            <p class="text-muted small mb-1">Jenis Aset</p>
                            <p class="fw-bold text-warning fs-5">{{ $aset->jenis_aset }}</p>
                        </div>
                    </div>
                </div>

                <h4 class="fw-bold my-4 text-warning border-bottom border-secondary pb-2">
                    <i class="bi bi-sliders me-2"></i>Matriks Penilaian Kriteria
                </h4>

                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle border border-secondary border-opacity-50">
                        <thead>
                            <tr class="table-dark border-bottom border-secondary">
                                <th class="ps-3">Kode Kriteria</th>
                                <th>Nama Kriteria</th>
                                <th class="text-center">Sifat</th>
                                <th class="text-center pe-3">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aset->penilaians as $penilaian)
                            <tr>
                                <td class="ps-3 fw-semibold">{{ $penilaian->kriteria->kode_kriteria }}</td>
                                <td>{{ $penilaian->kriteria->nama_kriteria }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ ucfirst($penilaian->kriteria->jenis) }}</span>
                                </td>
                                <td class="text-center fw-mono pe-3">{{ $penilaian->nilai }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Belum ada penilaian</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <a href="{{ route('aset-digital.edit', $aset->id) }}" class="btn btn-warning px-4 fw-medium">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                    <form action="{{ route('aset-digital.destroy', $aset->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?')" style="display:inline;">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 fw-medium">
                            <i class="bi bi-trash3-fill me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
