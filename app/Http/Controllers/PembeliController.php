<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Cart;

class PembeliController extends Controller
{
    public function index()
    {
        return view('pembeli.dashboardPembeli');
    }

    public function profil()
    {
        return view('pembeli.profil');
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;
        $user->save();

        return redirect()->route('pembeli.profil')->with('success', 'Profil berhasil diupdate!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('pembeli.profil')->with('success', 'Password berhasil diubah!');
    }

    public function riwayat(Request $request)
    {
        $query = Order::with(['toko', 'items.product', 'province', 'city'])
            ->where('user_id', Auth::id());

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != 'semua') {
            switch ($request->status) {
                case 'pending':
                    // Pesanan yang belum dibayar (status_pembayaran = pending)
                    $query->where('status_pembayaran', 'pending');
                    break;

                case 'dikonfirmasi': // ✅ TAMBAHKAN INI
                    $query->where('status', 'dikonfirmasi');
                    break;

                case 'diproses':
                    // Pesanan yang sedang diproses (status = 'diproses' di database)
                    $query->where('status', 'diproses');
                    break;

                case 'dikirim':
                    // Pesanan yang sedang dalam pengiriman
                    $query->where('status', 'dikirim');
                    break;

                case 'selesai':
                    // Pesanan yang sudah selesai
                    $query->where('status', 'selesai');
                    break;
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pembeli.riwayatPesanan', compact('orders'));
    }
    // ✅ FUNGSI UNTUK MENYELESAIKAN PESANAN (KHUSUS METODE DIKIRIM)
    public function selesaikanPesanan($id)
    {
        $order = Order::findOrFail($id);

        // Validasi kepemilikan
        if ($order->user_id != Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Validasi hanya untuk metode dikirim
        if ($order->metode_penerimaan != 'dikirim') {
            return back()->with('error', 'Hanya pesanan dengan metode dikirim yang bisa diselesaikan oleh pembeli.');
        }

        // Validasi status harus dikirim
        if ($order->status != 'dikirim') {
            return back()->with('error', 'Pesanan belum dalam status dikirim.');
        }

        $order->status = 'selesai';

        // ✅ Jika COD, update juga status pembayaran
        if ($order->metode_pembayaran === 'cod') {
            $order->status_pembayaran = 'paid';
        }

        $order->save();

        return back()->with('success', 'Pesanan berhasil diselesaikan! Silakan berikan rating.');
    }

    // ✅ FUNGSI UNTUK MEMBERIKAN RATING & REVIEW
    public function storeReview(Request $request, $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        $order = Order::findOrFail($orderId);

        // Validasi kepemilikan
        if ($order->user_id != Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Validasi status selesai
        if ($order->status != 'selesai') {
            return back()->with('error', 'Pesanan belum selesai.');
        }

        // Cek apakah sudah pernah review
        if ($order->rating != null) {
            return back()->with('error', 'Anda sudah memberikan review untuk pesanan ini.');
        }

        // Update order dengan rating dan review
        $order->rating = $request->rating;
        $order->review = $request->review;
        $order->reviewed_at = now();
        $order->save();

        return back()->with('success', 'Terima kasih! Review Anda telah disimpan.');
    }

    public function beliLagi($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        // Validasi kepemilikan
        if ($order->user_id != Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Validasi status harus selesai
        if ($order->status != 'selesai') {
            return back()->with('error', 'Hanya pesanan yang sudah selesai yang bisa dibeli lagi.');
        }

        $addedCount = 0;
        $outOfStockProducts = [];

        foreach ($order->items as $item) {
            $product = $item->product;

            // Cek apakah produk masih ada dan stoknya cukup
            if (!$product) {
                continue; // Skip jika produk sudah dihapus
            }

            if ($product->stok < $item->quantity) {
                $outOfStockProducts[] = $product->nama_produk;
                continue;
            }

            // Cek apakah produk sudah ada di keranjang
            $existingCart = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($existingCart) {
                // Update quantity jika sudah ada
                $newQuantity = $existingCart->quantity + $item->quantity;

                // Cek apakah stok cukup untuk quantity baru
                if ($product->stok >= $newQuantity) {
                    $existingCart->quantity = $newQuantity;
                    $existingCart->save();
                    $addedCount++;
                } else {
                    $outOfStockProducts[] = $product->nama_produk . ' (stok tidak cukup)';
                }
        } else {
            // Tambahkan ke keranjang jika belum ada
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $item->quantity,
            ]);
            $addedCount++;
        }
    }

    // Pesan response
    if ($addedCount > 0 && count($outOfStockProducts) > 0) {
        return redirect()->route('pembeli.cart')
            ->with('success', "$addedCount produk berhasil ditambahkan ke keranjang!")
            ->with('warning', 'Beberapa produk tidak ditambahkan: ' . implode(', ', $outOfStockProducts));
    } elseif ($addedCount > 0) {
        return redirect()->route('pembeli.cart')
            ->with('success', "$addedCount produk berhasil ditambahkan ke keranjang!");
    } else {
        return back()->with('error', 'Tidak ada produk yang bisa ditambahkan. ' .
            (count($outOfStockProducts) > 0 ? 'Produk: ' . implode(', ', $outOfStockProducts) : ''));
    }
}
}
