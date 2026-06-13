<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Models\User; // Memanggil model User bawaan Laravel
use Illuminate\Support\Facades\Hash; // Untuk enkripsi password
use Illuminate\Support\Facades\Auth; // Untuk fungsi login otomatis

class AuthController extends Controller
{
    // ==========================================
    // BAGIAN REGISTER
    // ==========================================
    
    // Menampilkan halaman form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Memproses data pendaftaran
    public function register(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
            'password.min' => 'Password minimal harus 8 karakter.'
        ]);

        // 2. Simpan Data ke Tabel Users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
        ]);

        // 3. Otomatis Login setelah berhasil mendaftar
        Auth::login($user);

        // 4. Arahkan pengguna kembali ke dashboard
        return redirect('/dashboard'); 
    }

    // ==========================================
    // BAGIAN LOGIN
    // ==========================================

    // Menampilkan halaman form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Memproses data login
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.'
        ]);

        // 2. Cek apakah ada checkbox "Remember Me" yang dicentang
        $remember = $request->has('remember') ? true : false;

        // 3. Coba melakukan login
        if (Auth::attempt($credentials, $remember)) {
            // Jika berhasil, buat sesi baru
            $request->session()->regenerate();

            // Arahkan ke dashboard
            return redirect()->intended('/dashboard');
        }

        // 4. Jika gagal, kembalikan ke halaman login bawa pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // ==========================================
    // BAGIAN LOGOUT
    // ==========================================

    // Fungsi untuk Logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Kembali ke halaman utama
    }

    // Menampilkan halaman Lupa Password
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }
    // Memproses permintaan pengiriman link reset password
    public function sendResetLinkEmail(Request $request)
    {
        // 1. Validasi pastikan email diisi dan formatnya benar
        $request->validate(['email' => 'required|email']);

        // 2. Laravel akan mencari email tersebut dan mengirimkan link (jika ada)
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 3. Cek hasilnya. Jika berhasil dikirim, kembalikan dengan pesan sukses.
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        // 4. Jika gagal (misal: email tidak ditemukan), kembalikan dengan pesan error.
        return back()->withErrors(['email' => __($status)]);
    }
    
    // Menampilkan halaman Buat Password Baru
    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // Memproses pergantian password
    public function resetPassword(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // 2. Coba atur ulang password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Jika token valid, ganti password lama dengan yang baru (jangan lupa di-hash!)
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        // 3. Jika sukses, arahkan ke login
        if ($status == Password::PASSWORD_RESET) {
            return redirect('/login')->with('status', __($status));
        }

        // 4. Jika gagal (token kadaluarsa/email salah), kembali ke form
        return back()->withErrors(['email' => __($status)]);
    }
}