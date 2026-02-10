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

        // ✅ Ambil daftar tahun yang tersedia
        $tahunTersedia = [];
        $tahunPertama = Order::where('toko_id', $toko->id)
            ->where('status', 'selesai')
            ->min(DB::raw('YEAR(created_at)'));
        $tahunSekarang = now()->year;

        if ($tahunPertama) {
            for ($year = $tahunPertama; $year <= $tahunSekarang; $year++) {
                $tahunTersedia[] = $year;
            }
        } else {
            $tahunTersedia[] = $tahunSekarang;
        }

        // Tahun yang dipilih (default: tahun sekarang)
        $tahunDipilih = request('tahun', $tahunSekarang);

        // ✅ DATA GRAFIK: Penjualan & Pendapatan per Bulan (untuk tahun yang dipilih)
        $chartData = $this->getChartData($toko->id, null, $tahunDipilih);

        // ✅ Daftar produk untuk filter
        $products = Product::where('user_id', $user->id)
            ->orderBy('status', 'desc')  // Aktif duluan, baru non-aktif
            ->orderBy('nama_produk')
            ->get();

        return view('petani.dashboardPetani', compact('stats', 'jumlahPenjualan', 'totalPendapatan', 'pesananSelesai', 'chartData', 'products', 'tahunTersedia', 'tahunDipilih'));
    }

    // ✅ Fungsi untuk generate data chart
    private function getChartData($tokoId, $productId = null, $tahun = null)
    {
        // Jika tahun tidak ditentukan, pakai tahun sekarang
        if (!$tahun) {
            $tahun = now()->year;
        }

        $months = [];
        $penjualanData = [];
        $pendapatanData = [];

        // Loop untuk 12 bulan (Jan - Des) pada tahun yang dipilih
        for ($i = 1; $i <= 12; $i++) {
            $date = Carbon::create($tahun, $i, 1);
            $monthName = $date->locale('id')->isoFormat('MMM YYYY');

            $months[] = $monthName;

            // Query penjualan (kg) per bulan
            $penjualanQuery = OrderItem::whereHas('order', function($query) use ($tokoId, $tahun, $i) {
                $query->where('toko_id', $tokoId)
                      ->where('status', 'selesai')
                      ->whereYear('created_at', $tahun)
                      ->whereMonth('created_at', $i);
            });

            // Filter per produk jika ada
            if ($productId) {
                $penjualanQuery->where('product_id', $productId);
            }

            $penjualanData[] = $penjualanQuery->sum('quantity') ?? 0;

            // Query pendapatan per bulan
            if ($productId) {
                // Jika filter per produk, hitung total dari order items
                $pendapatanData[] = OrderItem::whereHas('order', function($query) use ($tokoId, $tahun, $i) {
                    $query->where('toko_id', $tokoId)
                          ->where('status', 'selesai')
                          ->whereYear('created_at', $tahun)
                          ->whereMonth('created_at', $i);
                })->where('product_id', $productId)->sum('subtotal') ?? 0;
            } else {
                // Semua produk
                $pendapatanData[] = Order::where('toko_id', $tokoId)
                    ->where('status', 'selesai')
                    ->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $i)
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
        $tahun = $request->tahun ?: now()->year;
        $chartData = $this->getChartData($toko->id, $productId, $tahun);

        return response()->json($chartData);
    }
}
