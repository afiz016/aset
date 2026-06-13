@extends('layouts.app')

@section('title', 'Buat Password Baru')

@section('content')
<style>
    .glass-card { background: rgba(26, 33, 62, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; box-shadow: 0 15px 35px rgba(0,0,0,0.5); }
    .form-control-custom { background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; }
    .form-control-custom:focus { background-color: rgba(255, 255, 255, 0.1); border-color: #00d4ff; color: #fff; box-shadow: 0 0 0 0.25rem rgba(0, 212, 255, 0.25); }
    .form-control-custom::placeholder { color: rgba(255, 255, 255, 0.5); }
</style>

<div class="container-fluid d-flex flex-column flex-grow-1 justify-content-center align-items-center" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    <div class="col-12 col-md-8 col-lg-5 col-xl-4 z-index-1 my-5">

        <div class="card glass-card text-white">
            <div class="card-body p-5">
                
                <h3 class="fw-bold mb-2 text-center text-info">Buat Password Baru</h3>
                <p class="text-secondary text-center mb-4" style="font-size: 0.9rem;">Pastikan password baru Anda kuat dan mudah diingat.</p>

                @if ($errors->any())
                    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 mb-4 rounded-3 small">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/reset-password" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label class="form-label text-light">Alamat Email</label>
                        <input type="email" class="form-control form-control-custom py-2" name="email" value="{{ request()->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light">Password Baru</label>
                        <input type="password" class="form-control form-control-custom py-2" name="password" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light">Ulangi Password</label>
                        <input type="password" class="form-control form-control-custom py-2" name="password_confirmation" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-info btn-lg fw-bold text-dark rounded-3" style="transition: all 0.3s ease;">
                            Simpan Password
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection