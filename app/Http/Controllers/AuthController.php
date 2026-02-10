<?php

namespace App\Http\Controllers; // Lokasi file Controller

use Illuminate\Http\Request; // Untuk menangani HTTP request (data dari form, URL, dll)
use Illuminate\Support\Facades\Auth; // Untuk autentikasi (login, logout, cek user yang sedang login)
use Illuminate\Support\Facades\DB; // Untuk query database langsung (DB::table, DB::select, dll)
use App\Models\User; // Untuk mengakses model User (data pengguna)
use App\Models\Toko; // Untuk mengakses model Toko (data toko)

class AuthController extends Controller
{
    // Function (wadah yang berisi perintah-perintah)
    // Untuk menampilkan halaman form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Untuk proses login
    public function login(Request $request)
    {
        // Untuk validasi supaya email dan password tidak kosong
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'

        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',

        ]);

        // Mengambil hanya email dan password dari form
        $credentials = $request->only('email', 'password');

        // Mengecek apakah email dan password cocok di database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Email tidak ditemukan
            return back()->with('error', 'Email tidak terdaftar');
        }

        // Mengecek apakah passwordnya cocok
        if (!Auth::attempt($credentials)) {
            // Email ada, tapi password salah
            return back()->with('error', 'Password yang Anda masukkan salah!');
        }

        // Mengambil data user yang sedang login
        $user = Auth::user();

        // Mengecek status petani
        // Jika petani dan status masih pending, tidak bisa login
        if ($user->role == 'petani' && $user->status_aktif == 'pending') {
            Auth::logout();
            return back()->with('error', 'Akun Anda belum disetujui oleh SuperAdmin.');
        }

        // Mengarahkan user ke dashboard sesuai role
        if ($user->role == 'superadmin') {
            return redirect()->route('superAdmin.dashboardSuperAdmin');
        }

        if ($user->role == 'petani') {
            return redirect()->route('petani.dashboardPetani');
        }

        if ($user->role == 'pembeli') {
            return redirect()->route('pembeli.dashboardPembeli');
        }
    }

    // Untuk fitur lupa password saat login
    public function forgotPassword()
    {
        // Hanya menampilkan halaman lupa password
        return view('auth.forgot-password');
    }

    // Untuk menampilkan halaman form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Untuk proses register
    public function register(Request $request)
    {
        // Memvalidasi inputnyaa harus sesuai ketentuan di database
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:pembeli,petani',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'nama_toko' => 'required_if:role,petani|string|max:255|nullable', // Ini yang petani-petani aja wkwk
        ], [
            // Pesan-pesan error
            'name.required' => 'Nama lengkap wajib diisi!',
            'name.max' => 'Nama maksimal 100 karakter.',

            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah terdaftar. Gunakan email lain!',

            'password.required' => 'Password wajib diisi!',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok!',
            'nama_toko.required_if' => 'Nama toko wajib diisi untuk petani!', // Pesan errornya kalau nama tokonya ga diisi oleh petani (gabisa kosong)
        ]);

        try {
            // Mulai transaksi database (semua atau tidak sama sekali)
            DB::beginTransaction(); // Memulai transaksi

            // Buat user baru
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role = $request->role;
            $user->alamat = $request->alamat;
            $user->no_hp = $request->no_hp;

            // Setting statusnya berdasarkan role
            if ($request->role == 'petani') {
                // Petani harus disetujui superAdmin
                $user->status_aktif = 'pending';
            } else {
                // Untuk Pembeli langsung aktif
                $user->status_aktif = 'aktif';
            }

            $user->save(); // Menyimpan user ke database

            // Membuat toko otomatis untuk Petaninya
            if ($request->role == 'petani') {
                $namaToko = $request->nama_toko ?? 'Toko ' . $request->name;

                Toko::create([
                    'petani_id' => $user->id,
                    'nama_toko' => $namaToko,
                    'deskripsi_toko' => 'Selamat datang di toko kami! Kami menyediakan produk segar berkualitas.',
                    'alamat_toko' => $request->alamat,
                    'no_telp_toko' => $request->no_hp,
                    'is_active' => true,
                ]);
            }

            DB::commit(); // Menyimpan semua perubahan di database

            // Redirect dengan pesan sesuai role
            if ($user->role == 'petani') {
                return redirect()->route('login')->with('message', 'Akun petani berhasil dibuat. Toko Anda telah disiapkan. Tunggu persetujuan SuperAdmin untuk mulai berjualan.');
            }

            return redirect()->route('login')->with('message', 'Akun pembeli berhasil dibuat. Silakan login.');

        } catch (\Exception $e) {
            // Rollback jika ada error, semua perubahan dibatalkan ngga disimpan
            DB::rollBack();

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
        }
    }

    // Untuk Logout
    public function logout(Request $request)
    {
        Auth::logout(); // Logout user
        $request->session()->invalidate(); // Hapus session
        $request->session()->regenerateToken(); // Generate CSRF token baru (keamanan)

        return redirect('/login');
    }
}
