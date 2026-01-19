<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardPetaniController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $toko = $user->toko;

        // Jika user belum punya toko
        if (!$toko) {
            return view('petani.dashboardPetani', [
                'stats' => [
                    'total' => 0,
                    'aktif' => 0,
                    'nonaktif' => 0,
                ],
                'jumlahPenjualan' => 0,
                'totalPendapatan' => 0,
                'pesananSelesai' => 0,
                'chartData' => null,
                'products' => collect()
            ]);
        }

        // ✅ CARD 1: Jumlah Penjualan (kg) dari order selesai
        // Ambil semua order selesai di toko ini
        $jumlahPenjualan = OrderItem::whereHas('order', function($query) use ($toko) {
                $query->where('toko_id', $toko->id)
                      ->where('status', 'selesai');
            })
            ->sum('quantity'); // Total quantity dalam kg

        // ✅ CARD 2: Total Pendapatan dari order selesai (atau semua order paid)
        // Opsi 1: Hanya dari order selesai
        $totalPendapatan = Order::where('toko_id', $toko->id)
            ->where('status', 'selesai')
            ->sum('total');
        // ✅ CARD 4: Jumlah pesanan selesai
        $pesananSelesai = Order::where('toko_id', $toko->id)
            ->where('status', 'selesai')
            ->count();

        // CARD 3: Statistik produk
        // Hitung statistik produk
        $stats = [
            'total' => Auth::user()->products()->count(),
            'aktif' => Auth::user()->products()->where('status', 'aktif')->count(),
            'nonaktif' => Auth::user()->products()->where('status', 'nonaktif')->count(),
        ];

        // ✅ DATA GRAFIK: Penjualan & Pendapatan per Bulan (12 bulan terakhir)
        $chartData = $this->getChartData($toko->id);

        // ✅ Daftar produk untuk filter
        $products = Product::where('user_id', $user->id)
            ->where('status', 'aktif')
            ->orderBy('nama_produk')
            ->get();

        return view('petani.dashboardPetani', compact('stats', 'jumlahPenjualan', 'totalPendapatan', 'pesananSelesai', 'chartData', 'products'));
    }

    // ✅ Fungsi untuk generate data chart
    private function getChartData($tokoId, $productId = null)
    {
        // 12 bulan terakhir
        $months = [];
        $penjualanData = [];
        $pendapatanData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            $monthName = $date->locale('id')->isoFormat('MMM YYYY');

            $months[] = $monthName;

            // Query penjualan (kg) per bulan
            $penjualanQuery = OrderItem::whereHas('order', function($query) use ($tokoId, $year, $month) {
                $query->where('toko_id', $tokoId)
                      ->where('status', 'selesai')
                      ->whereYear('created_at', $year)
                      ->whereMonth('created_at', $month);
            });

            // Filter per produk jika ada
            if ($productId) {
                $penjualanQuery->where('product_id', $productId);
            }

            $penjualanData[] = $penjualanQuery->sum('quantity') ?? 0;

            // Query pendapatan per bulan
            if ($productId) {
                // Jika filter per produk, hitung total dari order items
                $pendapatanData[] = OrderItem::whereHas('order', function($query) use ($tokoId, $year, $month) {
                    $query->where('toko_id', $tokoId)
                          ->where('status', 'selesai')
                          ->whereYear('created_at', $year)
                          ->whereMonth('created_at', $month);
                })->where('product_id', $productId)->sum('subtotal') ?? 0;
            } else {
                // Semua produk
                $pendapatanData[] = Order::where('toko_id', $tokoId)
                    ->where('status', 'selesai')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->sum('total') ?? 0;
            }
        }

        return [
            'months' => $months,
            'penjualan' => $penjualanData,
            'pendapatan' => $pendapatanData
        ];
    }

    // ✅ AJAX Endpoint untuk filter produk
    public function getChartByProduct(Request $request)
    {
        $toko = Auth::user()->toko;

        if (!$toko) {
            return response()->json([
                'months' => [],
                'penjualan' => [],
                'pendapatan' => []
            ]);
        }

        $productId = $request->product_id ?: null;
        $chartData = $this->getChartData($toko->id, $productId);

        return response()->json($chartData);
    }
}
