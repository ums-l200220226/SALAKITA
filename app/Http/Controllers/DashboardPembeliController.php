<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardPembeliController extends Controller
{
    public function index()
    {
        // Ambil 4 produk unggulan dengan rating (lebih efisien dengan subquery)
        $featuredProducts = Product::with(['user', 'toko'])
            ->addSelect([
                'products.*',
                'average_rating' => DB::table('orders')
                    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->whereColumn('order_items.product_id', 'products.id')
                    ->whereNotNull('orders.rating')
                    ->selectRaw('AVG(orders.rating)'),
                'total_reviews' => DB::table('orders')
                    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->whereColumn('order_items.product_id', 'products.id')
                    ->whereNotNull('orders.rating')
                    ->selectRaw('COUNT(*)')
            ])
            ->where('status', 'aktif')
            ->orderBy('total_terjual', 'desc')
            ->orderBy('created_at', 'asc')
            ->take(4)
            ->get();

        // Hitung jumlah produk aktif
        $jumlahProduk = Product::where('status', 'aktif')->count();

        // Total Transaksi yang Selesai
        $totalTransaksi = Order::where('status', 'selesai')->count();

        // Ambil 3 testimoni terbaru dengan rating 5 bintang
        $testimonials = Order::whereNotNull('review')
            ->whereNotNull('reviewed_at')
            ->where('rating', 5)
            ->orderBy('reviewed_at', 'desc')
            ->limit(3)
            ->get();

        return view('pembeli.dashboardPembeli', compact('featuredProducts', 'jumlahProduk', 'totalTransaksi', 'testimonials'));
    }
}
