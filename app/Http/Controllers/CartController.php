<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Tampilkan halaman keranjang
    public function index()
    {

        $cartItems = Cart::with(['product.user', 'product.toko'])
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->harga;
        });

        return view('pembeli.cart', compact('cartItems', 'total'));
    }

    // Tambah produk ke keranjang
    public function add(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($productId);

        // Cek stok
        if ($request->quantity > $product->stok) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        // Cek apakah produk sudah ada di keranjang
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $request->quantity;

            if ($newQuantity > $product->stok) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Tambah item baru
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Update quantity
    public function update(Request $request, $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('id', $cartId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->quantity > $cartItem->product->stok) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang berhasil diupdate!');
    }

    // Hapus item dari keranjang
    public function remove($cartId)
    {
        Cart::where('id', $cartId)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    // Get jumlah item di keranjang (untuk badge notifikasi)
    public function count()
    {
        return Cart::where('user_id', Auth::id())->count();
    }
}
