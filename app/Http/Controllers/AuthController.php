<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Toko;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ✅ Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ✅ Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Email atau password salah.');
        }

        $user = Auth::user();

        // PETANI HARUS MENUNGGU DISETUJUI
        if ($user->role == 'petani' && $user->status_aktif == 'pending') {
            Auth::logout();
            return back()->with('error', 'Akun Anda belum disetujui oleh SuperAdmin.');
        }

        // Direct user ke dashboard sesuai role
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

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    // ✅ Menampilkan form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // ✅ Proses register (UPDATED - dengan Toko)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:pembeli,petani',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'nama_toko' => 'required_if:role,petani|string|max:255|nullable', // Tambahan untuk petani
        ], [
            'nama_toko.required_if' => 'Nama toko wajib diisi untuk petani',
        ]);

        try {
            DB::beginTransaction();

            // Buat user
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role = $request->role;
            $user->alamat = $request->alamat;
            $user->no_hp = $request->no_hp;

            if ($request->role == 'petani') {
                // harus disetujui superadmin
                $user->status_aktif = 'pending';
            } else {
                // pembeli langsung aktif
                $user->status_aktif = 'aktif';
            }

            $user->save();

            // ✅ TAMBAHAN: Jika role petani, buat toko otomatis
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

            DB::commit();

            // Redirect dengan pesan sesuai role
            if ($user->role == 'petani') {
                return redirect()->route('login')->with('message', 'Akun petani berhasil dibuat. Toko Anda telah disiapkan. Tunggu persetujuan SuperAdmin untuk mulai berjualan.');
            }

            return redirect()->route('login')->with('message', 'Akun pembeli berhasil dibuat. Silakan login.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
        }
    }

    // ✅ Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
