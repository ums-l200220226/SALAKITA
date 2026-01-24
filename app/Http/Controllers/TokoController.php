<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TokoController extends Controller
{
    //==========PEMBELI======//
    // Halaman daftar semua toko
    public function index()
    {
        $tokos = Toko::with('user')
                 ->withCount('products')
                 ->whereHas('user', function($query) {
                     $query->where('status_aktif', 'aktif')  // ← Sesuaiin nama kolomnya
                           ->where('role', 'petani');         // ← Hanya petani
                 })
                 ->latest()
                 ->paginate(12);

        return view('pembeli.toko', compact('tokos'));
    }

    // Halaman detail toko
    public function show($id)
    {
        $toko = Toko::with('user')->findOrFail($id);

        // Ambil produk berdasarkan toko_id, dan status aktif
        $products = Product::where('toko_id', $toko->id)  // ← Pakai toko_id
                      ->where('status', 'aktif')      // ← HANYA PRODUK AKTIF
                      ->latest()
                      ->paginate(12);


        // ✅ HITUNG RATING UNTUK SETIAP PRODUK
        foreach ($products as $product) {
            $product->avg_rating = $product->getAvgRating();
            $product->review_count = $product->getReviewCount();
        }

        return view('pembeli.katalog', compact('toko', 'products'));
    }

    // ========== UNTUK ADMIN ==========

    // Halaman daftar toko (admin) - method baru
    public function adminIndex()
    {
        $tokos = Toko::with('user')
                     ->withCount('products')
                     ->whereHas('user', function($query) {
                         $query->where('status_aktif', 'aktif')  // ← Sesuaikan nama kolom
                               ->where('role', 'petani');
                     })
                     ->latest()
                     ->paginate(12);

        return view('superAdmin.tokoPetani', compact('tokos'));
        }

        // Halaman detail toko (admin) - method baru
        public function adminShow($id)
        {
            $toko = Toko::with('user')->findOrFail($id);

            $products = Product::where('toko_id', $id)
                          ->where('status', 'aktif')
                          ->latest()
                          ->paginate(12);

            // ✅ HITUNG RATING UNTUK SETIAP PRODUK (UNTUK ADMIN JUGA)
            foreach ($products as $product) {
                $product->avg_rating = $product->getAvgRating();
                $product->review_count = $product->getReviewCount();
            }

            return view('superAdmin.katalogPetani', compact('toko', 'products'));
        }

    public function update(Request $request)
    {
        // 1️⃣ Validasi (NAMA FIELD DISAMAKAN DENGAN DB)
        $request->validate([
            'nama_toko'       => 'required|string|max:255',
            'alamat_toko'     => 'required|string',
            'no_telp_toko'    => 'required|string|max:20',
            'deskripsi_toko'  => 'nullable|string',
            'logo_toko'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2️⃣ Ambil user login
        $user = Auth::user();

        // 3️⃣ Cari atau buat toko (RELASI SUDAH BENAR → petani_id)
        $toko = $user->toko()->firstOrCreate(
            [], // kondisi kosong → otomatis pakai petani_id
            [
                'nama_toko'       => $request->nama_toko,
                'deskripsi_toko'  => $request->deskripsi_toko,
                'alamat_toko'     => $request->alamat_toko,
                'no_telp_toko'    => $request->no_telp_toko,
                'is_active'       => true,
            ]
        );

        // 4️⃣ Update data toko (PAKAI KOLOM YANG ADA DI DB)
        $toko->nama_toko       = $request->nama_toko;
        $toko->alamat_toko     = $request->alamat_toko;
        $toko->no_telp_toko    = $request->no_telp_toko;
        $toko->deskripsi_toko  = $request->deskripsi_toko;

        // 5️⃣ Handle upload logo
        if ($request->hasFile('logo_toko')) {

            // Hapus logo lama jika ada
            if ($toko->logo_toko && Storage::exists('public/' . $toko->logo_toko)) {
                Storage::delete('public/' . $toko->logo_toko);
            }

            // Upload logo baru
            $path = $request->file('logo_toko')->store('toko', 'public');
            $toko->logo_toko = $path;
        }

        // 6️⃣ Simpan perubahan
        $toko->save();

        return redirect()->back()->with('success', 'Data toko berhasil diperbarui!');
    }
}
