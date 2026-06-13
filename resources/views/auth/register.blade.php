@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')

<style>
    /* Kustomisasi Tampilan Card Register */
    .register-card {
        background: rgba(26, 33, 62, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
    }
    
    .form-control-custom {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    
    .form-control-custom:focus {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: #00d4ff;
        color: #fff;
        box-shadow: 0 0 0 0.25rem rgba(0, 212, 255, 0.25);
    }
    
    .form-control-custom::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    /* MENGATASI WARNA PUTIH AUTOFILL BROWSER */
    .form-control-custom:-webkit-autofill,
    .form-control-custom:-webkit-autofill:hover, 
    .form-control-custom:-webkit-autofill:focus, 
    .form-control-custom:-webkit-autofill:active {
        /* Memaksa transisi warna sangat lambat agar warna putihnya tidak pernah muncul */
        transition: background-color 5000s ease-in-out 0s;
        -webkit-text-fill-color: #fff !important;
        font-family: inherit !important;
    }

    /* Hover khusus untuk Tombol Daftar */
    .btn-register {
        transition: all 0.3s ease;
    }
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 212, 255, 0.4) !important;
        background-color: #00bceb !important;
    }
</style>

<div class="container-fluid d-flex flex-column flex-grow-1 justify-content-center align-items-center py-5" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    
    <div class="col-12 col-md-8 col-lg-6 col-xl-5 z-index-1">
        
        <div class="mb-4 text-center">
            <a href="/" class="text-decoration-none text-light fw-semibold" style="transition: color 0.3s;" onmouseover="this.classList.replace('text-light', 'text-white')" onmouseout="this.classList.replace('text-white', 'text-light')">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

        <div class="card register-card text-white">
            <div class="card-body p-4 p-sm-5">
                
                <h2 class="fw-bold mb-2 text-center text-info">Buat Akun Baru</h2>
                <p class="text-secondary text-center mb-4">Bergabunglah untuk mulai menganalisis investasi aset digitalmu.</p>

                @if ($errors->any())
                    <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border-color: #dc3545; color: #ffb3b8; border-radius: 8px;">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/register" method="POST">
                    
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label text-light">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-custom py-2" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label text-light">Alamat Email</label>
                        <input type="email" class="form-control form-control-custom py-2" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label text-light">Password</label>
                            <input type="password" class="form-control form-control-custom py-2" id="password" name="password" placeholder="Minimal 8 karakter" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="password_confirmation" class="form-label text-light">Konfirmasi Password</label>
                            <input type="password" class="form-control form-control-custom py-2" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label text-secondary small" for="terms">
                            Saya setuju dengan <a href="#" class="text-info text-decoration-none">Syarat & Ketentuan</a> yang berlaku.
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-info btn-lg btn-register fw-bold text-dark" style="border-radius: 8px;">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>
                
            </div>
            
            <div class="card-footer bg-transparent border-top border-secondary text-center py-3">
                <p class="mb-0 text-secondary small">
                    Sudah punya akun? <a href="/login" class="text-info text-decoration-none fw-bold">Login di sini</a>
                </p>
            </div>
            
        </div>
    </div>
</div>
@endsection