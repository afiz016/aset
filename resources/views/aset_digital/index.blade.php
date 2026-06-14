@extends('layouts.app')

@section('title', 'Daftar Aset Digital')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="card text-white rounded-3 overflow-hidden shadow-lg" style="background: linear-gradient(135deg, rgba(0, 212, 255, 0.08) 0%, rgba(0, 100, 150, 0.04) 100%); border: 1px solid rgba(0, 212, 255, 0.2) !important;">
        <div class="card-header p-4 fw-bold" style="background: linear-gradient(90deg, rgba(0, 212, 255, 0.15), rgba(0, 100, 150, 0.08)); border: none; border-bottom: 2px solid rgba(0, 212, 255, 0.3);">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0 text-info"><i class="bi bi-box-seam-fill me-2"></i>Daftar Alternatif Aset Digital</h3>
                <div class="d-flex gap-2">
                    <button id="btnSyncApi" class="btn btn-outline-info fw-bold" style="padding: 0.6rem 1.5rem; border-radius: 8px; transition: all 0.3s;">
                        <i class="bi bi-arrow-clockwise me-1" id="iconSync"></i> Sync Data API
                    </button>
                    <a href="{{ route('aset-digital.create') }}" class="btn fw-bold" style="background: linear-gradient(90deg, #00d4ff, #4dabf7); color: #000; padding: 0.6rem 1.5rem; border: none; border-radius: 8px;"><i class="bi bi-plus-lg me-1"></i> Tambah Aset</a>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="background: linear-gradient(90deg, rgba(0, 255, 0, 0.1), rgba(0, 150, 0, 0.05)); border: 1px solid rgba(0, 255, 0, 0.3); border-radius: 8px;">
                    <i class="bi bi-check-circle-fill me-2"></i> <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive" style="border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead style="background: linear-gradient(90deg, #00d4ff, #4dabf7); color: #000;">
                        <tr class="fw-bold">
                            <th class="ps-3">Nama Aset</th>
                            <th>Jenis Aset</th>
                            @foreach($kriterias as $k)
                                <th class="text-center">{{ $k->kode_kriteria }}</th>
                            @endforeach
                            <th class="text-center pe-3" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asetDigitals as $aset)
                        <tr style="border-bottom: 1px solid rgba(0, 212, 255, 0.1); transition: all 0.3s;" onmouseover="this.style.background='rgba(0, 212, 255, 0.05)'" onmouseout="this.style.background='transparent'">
                            <td class="ps-3 fw-semibold" style="color: #00d4ff;">{{ $aset->nama_aset }}</td>
                            <td><span class="badge" style="background: rgba(0, 212, 255, 0.2); color: #4dabf7; border: 1px solid rgba(0, 212, 255, 0.3);">{{ $aset->jenis_aset }}</span></td>
                            @foreach($kriterias as $k)
                                @php 
                                    $p = $aset->penilaians->where('kriteria_id', $k->id)->first(); 
                                    $nilai = $p ? $p->nilai : 0;
                                @endphp
                                <td class="text-center fw-mono">
                                    <span class="badge" style="background: {{ $nilai > 0 ? 'linear-gradient(90deg, #ffc107, #ff9800)' : 'rgba(255, 255, 255, 0.1)' }}; color: {{ $nilai > 0 ? '#000' : '#fff' }};  padding: 0.4rem 0.6rem;">{{ $nilai }}</span>
                                </td>
                            @endforeach
                            <td class="text-center pe-3">
                                <div class="btn-group btn-group-sm gap-1" role="group">
                                    <a href="{{ route('aset-digital.show', $aset->id) }}" class="btn btn-sm" style="background: rgba(0, 212, 255, 0.2); color: #00d4ff; border: 1px solid rgba(0, 212, 255, 0.3); border-radius: 6px; transition: all 0.3s;" title="Lihat detail" onmouseover="this.style.background='rgba(0, 212, 255, 0.4)'; this.style.boxShadow='0 0 10px rgba(0, 212, 255, 0.3)'" onmouseout="this.style.background='rgba(0, 212, 255, 0.2)'; this.style.boxShadow='none'">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('aset-digital.edit', $aset->id) }}" class="btn btn-sm" style="background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 6px; transition: all 0.3s;" title="Edit data" onmouseover="this.style.background='rgba(255, 193, 7, 0.4)'; this.style.boxShadow='0 0 10px rgba(255, 193, 7, 0.3)'" onmouseout="this.style.background='rgba(255, 193, 7, 0.2)'; this.style.boxShadow='none'">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('aset-digital.destroy', $aset->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?')" style="display:inline;">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background: rgba(255, 68, 68, 0.2); color: #ff4444; border: 1px solid rgba(255, 68, 68, 0.3); border-radius: 6px; transition: all 0.3s;" title="Hapus data" onmouseover="this.style.background='rgba(255, 68, 68, 0.4)'; this.style.boxShadow='0 0 10px rgba(255, 68, 68, 0.3)'" onmouseout="this.style.background='rgba(255, 68, 68, 0.2)'; this.style.boxShadow='none'">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($kriterias) + 3 }}" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                <p class="mb-0">Belum ada data alternatif aset digital. <a href="{{ route('aset-digital.create') }}" class="text-info">Tambah aset sekarang</a></p>
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
document.getElementById('btnSyncApi').addEventListener('click', function() {
    const btn = this;
    
    // 1. Ubah visual tombol menjadi efek loading
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Menghubungkan API...`;
    
    // 2. Eksekusi request ke route sync
    fetch("{{ route('aset-digital.sync') }}")
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('✓ Sukses: ' + data.message);
                window.location.reload();
            } else {
                alert('❌ Gagal menyinkronkan data.');
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan jaringan saat fetch data.');
            window.location.reload();
        });
});
</script>
@endsection