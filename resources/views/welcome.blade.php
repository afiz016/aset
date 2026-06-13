@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<style>
    /* Hover untuk Badge Kriteria */
    .kriteria-badge {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .kriteria-badge:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 212, 255, 0.3) !important;
        border-color: #00d4ff !important;
        background-color: #1a2942 !important;
    }

    /* Hover khusus untuk Tombol Login */
    .btn-login {
        transition: all 0.3s ease;
    }
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 212, 255, 0.5) !important;
        background-color: #00bceb !important;
        color: #000 !important;
    }

    /* Hover untuk Link Daftar */
    .link-daftar {
        transition: color 0.3s ease;
    }
    .link-daftar:hover {
        color: #ffffff !important;
        text-decoration: underline !important;
    }
</style>

<div class="container-fluid px-0 d-flex flex-column flex-grow-1">
    
    <div class="row align-items-center bg-dark text-white overflow-hidden position-relative m-0 flex-grow-1" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 2rem;">
        
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 80% 20%, rgba(0, 212, 255, 0.1) 0%, transparent 50%); pointer-events: none;"></div>

        <div class="col-lg-6 z-index-1">
            <span class="badge bg-primary px-3 py-2 mb-3 rounded-pill text-uppercase tracking-wider shadow-sm">SPK Berbasis Web</span>
            <h1 class="display-4 fw-bolder mb-4" style="line-height: 1.2;">
                Optimalkan Investasi <br> 
                <span class="text-info">Aset Digital In-Game</span> Anda.
            </h1>
            <p class="lead text-secondary mb-5 fs-5">
                Ambil keputusan investasi yang lebih objektif dan terukur pada pasar aset digital sekunder. Sistem kami menggunakan algoritma <strong>TOPSIS</strong> untuk menganalisis harga, kelangkaan, dan sentimen komunitas secara akurat.
            </p>
            
            <div class="d-flex flex-column align-items-start gap-2">
                <a href="/login" class="btn btn-info btn-login btn-lg px-5 fw-bold text-dark shadow-sm" style="border-radius: 12px;">
                    Login <i class="bi bi-box-arrow-in-right ms-2"></i>
                </a>
                <p class="mt-2 ms-2 fs-6 text-secondary">
                    Belum punya akun? <a href="/register" class="text-info text-decoration-none fw-bold link-daftar">Daftar sekarang</a>
                </p>
            </div>
        </div>

        <div class="col-lg-6 d-none d-lg-block text-center z-index-1 mt-5 mt-lg-0">
            <div class="p-5 bg-white bg-opacity-10 rounded-4 shadow-lg border border-white border-opacity-25" style="backdrop-filter: blur(10px);">
                <h4 class="text-white mb-4">Indikator Penilaian (Kriteria)</h4>
                
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <span class="badge bg-dark border border-secondary px-3 py-2 fs-6 kriteria-badge">💰 Harga Beli (Cost)</span>
                    <span class="badge bg-dark border border-secondary px-3 py-2 fs-6 kriteria-badge">📈 Volume (Benefit)</span>
                    <span class="badge bg-dark border border-secondary px-3 py-2 fs-6 kriteria-badge">💎 Rarity (Benefit)</span>
                    <span class="badge bg-dark border border-secondary px-3 py-2 fs-6 kriteria-badge">🔥 Sentiment (Benefit)</span>
                    <span class="badge bg-dark border border-secondary px-3 py-2 fs-6 kriteria-badge">💧 Likuiditas (Benefit)</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection