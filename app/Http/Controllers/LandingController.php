<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingPageSetting;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil data dari database
        $settings = LandingPageSetting::getSettings();

        // ✅ Ambil produk terlaris dengan rating dan jumlah terjual
        $produkTerlaris = Product::select('products.*')
            ->selectSub(function($query) {
                $query->from('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereColumn('order_items.product_id', 'products.id')
                    ->where('orders.status', 'selesai')
                    ->selectRaw('COALESCE(SUM(order_items.quantity), 0)');
            }, 'jumlah_terjual')
            ->where('products.status', 'aktif')  // ✅ Tetap filter produk aktif
            ->orderByDesc('jumlah_terjual')      // ✅ Sort di database, bukan di PHP
            ->limit(3)
            ->get()
            ->map(function($product) {
                // Hitung rating per produk
                $ratings = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.product_id', $product->id)
                    ->whereNotNull('orders.rating')
                    ->pluck('orders.rating');

                $product->average_rating = $ratings->avg() ?? 0;
                $product->total_reviews = $ratings->count();

                // jumlah_terjual sudah dihitung di query utama
                return $product;
            });

        // Hitung transaksi sukses (status selesai)
        $transaksiSukses = Order::where('status', 'selesai')->count();

        // Hitung jumlah produk aktif
        $produkAktif = Product::where('status', 'aktif')->count();

        // Hitung rata-rata rating dari semua order yang sudah diberi rating
        $ratingRataRata = Order::whereNotNull('rating')
            ->avg('rating');
        // Format rating jadi 1 desimal (misal: 4.5)
        $ratingRataRata = $ratingRataRata ? number_format($ratingRataRata, 1) : 0;

        // Kirim data ke view
        $stats = [
            'transaksi' => $transaksiSukses,
            'produk' => $produkAktif,
            'rating' => $ratingRataRata
        ];

        return view('landingPage', compact('settings', 'stats', 'produkTerlaris'));
    }
}
