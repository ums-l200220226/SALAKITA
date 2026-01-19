<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     * Tampilkan semua produk milik petani yang login
     */
    public function index(Request $request)
    {
        $query = Auth::user()->products();

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search berdasarkan nama produk
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Ambil data produk dengan pagination
        $products = $query->latest()->paginate(12);

        // Hitung statistik
        $stats = [
            'total' => Auth::user()->products()->count(),
            'aktif' => Auth::user()->products()->where('status', 'aktif')->count(),
            'stok_menipis' => Auth::user()->products()->stokMenipis()->count(),
            'stok_habis' => Auth::user()->products()->stokHabis()->count(),
        ];

        // Tambahkan Toko
        $toko = Toko::where('petani_id', Auth::id())->first();

        return view('petani.katalogSaya', compact('products', 'stats', 'toko'));
    }

    /**
     * Show the form for creating a new resource.
     * (Optional: jika mau pakai halaman terpisah untuk form)
     */
    public function create()
    {
        return view('petani.katalog.create');
    }

    /**
     * Store a newly created resource in storage.
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|in:sayuran,buah,beras,rempah,lainnya',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // TAMBAHKAN INI - Ambil toko petani
        $toko = Auth::user()->toko;

        if (!$toko) {
            return redirect()->back()
                   ->with('error', 'Toko tidak ditemukan!')
                   ->withInput();
        }

        // Tambahkan relasi
        $validated['user_id'] = Auth::id();
        $validated['toko_id'] = $toko->id;

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('products', 'public');
            $validated['gambar'] = $path;
        }

        // Tambahkan user_id (petani yang login)
        $validated['user_id'] = Auth::id();

        // Simpan ke database
        Product::create($validated);

        return redirect()->route('petani.katalogSaya')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    //Tampilkan detail produk
    public function show(Product $product)
    {
        // Pastikan produk milik petani yang login
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Jika request AJAX, return JSON
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($product);
        }

        // Jika request biasa, return view
        return view('petani.katalog.show', compact('product'));
    }

    // (Optional: jika mau pakai halaman terpisah untuk edit)
    public function edit(Product $product)
    {
        // Pastikan produk milik petani yang login
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Kalau request AJAX, return JSON
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($product);
        }

        return view('petani.katalog.edit', compact('product'));
    }

    // Update produk
    public function update(Request $request, Product $product)
    {
        // Pastikan produk milik petani yang login
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi input
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|in:sayuran,buah,beras,rempah,lainnya',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }

            $path = $request->file('gambar')->store('products', 'public');
            $validated['gambar'] = $path;
        }

        // Update produk
        $product->update($validated);

        return redirect()->route('petani.katalogSaya')
            ->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     * Hapus produk
     */
    public function destroy(Product $product)
    {
        // Pastikan produk milik petani yang login
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus gambar jika ada
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        // Hapus produk
        $product->delete();

        return redirect()->route('petani.katalogSaya')
            ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Toggle status produk (aktif/nonaktif)
     */
    public function toggleStatus(Product $product)
    {
        // Pastikan produk milik petani yang login
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $product->update([
            'status' => $product->status === 'aktif' ? 'nonaktif' : 'aktif'
        ]);

        return back()->with('success', 'Status produk berhasil diubah!');
    }
}
