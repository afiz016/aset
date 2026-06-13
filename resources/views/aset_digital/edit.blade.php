@extends('layouts.app')

@section('title', 'Edit Aset Digital')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card bg-secondary bg-opacity-10 border-secondary border-opacity-25 text-white rounded-3 p-4 shadow">
                <h3 class="fw-bold mb-4 text-info border-b pb-2"><i class="bi bi-pencil-square me-2"></i>Edit Aset & Perbarui Matriks Nilai</h3>
                
                <form action="{{ route('aset-digital.update', $aset->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-light">Nama Aset / Item / Skin</label>
                            <input type="text" name="nama_aset" class="form-control bg-dark border-secondary text-white py-2" placeholder="Contoh: AK-47 Ice Coaled, Bored Ape NFT" value="{{ old('nama_aset', $aset->nama_aset) }}" required>
                            @error('nama_aset')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-light">Jenis Aset</label>
                            <input type="text" name="jenis_aset" class="form-control bg-dark border-secondary text-white py-2" placeholder="Contoh: CSGO Skin, Ethereum NFT" value="{{ old('jenis_aset', $aset->jenis_aset) }}" required>
                            @error('jenis_aset')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <h4 class="fw-bold my-4 text-warning border-bottom border-secondary pb-2">
                        <i class="bi bi-sliders me-2"></i>Rating Kecocokan Kriteria
                    </h4>
                    
                    <div class="row g-3">
                        @foreach($kriterias as $k)
                        @php 
                            $penilaian = $aset->penilaians->where('kriteria_id', $k->id)->first();
                            $nilaiSaatIni = $penilaian ? $penilaian->nilai : '';
                        @endphp
                        <div class="col-md-6 mb-2">
                            <div class="p-3 rounded-3 bg-black bg-opacity-20 border border-secondary border-opacity-25">
                                <label class="form-label fw-bold text-info mb-1">{{ $k->kode_kriteria }} - {{ $k->nama_kriteria }}</label>
                                <span class="d-block text-muted small mb-2">Sifat: {{ ucfirst($k->jenis) }}</span>
                                <input type="number" step="any" name="nilai[{{ $k->id }}]" class="form-control bg-dark border-secondary text-white py-2" placeholder="Masukkan angka skor kecocokan" value="{{ old('nilai.' . $k->id, $nilaiSaatIni) }}" required>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top border-secondary border-opacity-25">
                        <a href="{{ route('aset-digital.index') }}" class="btn btn-outline-secondary px-4 fw-medium">Batal</a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold">Perbarui Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
