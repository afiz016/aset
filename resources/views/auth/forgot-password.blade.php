@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<style>
    .glass-card {
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
    .form-control-custom::placeholder { color: rgba(255, 255, 255, 0.5); }
</style>

<div class="container-fluid d-flex flex-column flex-grow-1 justify-content-center align-items-center" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    <div class="col-12 col-md-8 col-lg-5 col-xl-4 z-index-1 my-5">
        
        <div class="mb-4 text-center">
            <a href="/login" class="text-decoration-none text-light fw-semibold">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>

        <div class="card glass-card text-white">
            <div class="card-body p-5">
                
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock text-info" style="font-size: 3rem;"></i>
                </div>
                <h3 class="fw-bold mb-2 text-center text-info">Lupa Password?</h3>
                <p class="text-secondary text-center mb-4" style="font-size: 0.9rem;">Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk mereset password Anda.</p>

                @if (session('status'))
                    <div class="alert alert-success bg-success bg-opacity-10 text-success border-success border-opacity-25 mb-4 rounded-3 text-center small">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 mb-4 rounded-3 small">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/forgot-password" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label text-light">Alamat Email</label>
                        <input type="email" class="form-control form-control-custom py-2" id="email" name="email" placeholder="nama@email.com" required autofocus>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-info btn-lg fw-bold text-dark rounded-3" style="transition: all 0.3s ease;">
                            Kirim Link Reset
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection