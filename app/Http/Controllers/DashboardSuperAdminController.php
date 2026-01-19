<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Order;

class DashboardSuperAdminController extends Controller
{
    public function index()
    {
        $totalTransaksi = 0; // nanti diganti real dari DB

        // Hitung petani aktif & pending
        $petaniAktif = User::where('role', 'petani')
                            ->where('status_aktif', 'aktif')
                            ->count();

        $petaniPending = User::where('role', 'petani')
                             ->where('status_aktif', 'pending')
                             ->count();

        $petaniDitolak = User::where('role', 'petani')
                            ->where('status_aktif', 'ditolak')
                            ->count();

        // Petani baru mendaftar (≤ 2x24 jam)
        $petaniBaru = User::where('role', 'petani')
                            ->where('status_aktif', 'pending')
                            ->where('created_at', '>=', Carbon::now()->subHours(48))
                            ->orderBy('created_at', 'desc')
                            ->get();

        $bulan = ["Jan", "Feb", "Mar", "Apr"];
        $jumlah = [0, 0, 0, 0]; // nanti diganti data asli

        // Total produk aktif
        $totalProdukAktif = Product::where('status', 'aktif')->count();

        // Total Transaksi SELESAI ✅
        $totalTransaksi = Order::where('status', 'selesai')->count();

        // Total Pendapatan Keseluruhan (orders selesai)
        $totalPendapatan = Order::where('status', 'selesai')->sum('total');

        // Data Grafik Transaksi per Bulan (12 bulan terakhir)
        $bulan = [];
        $jumlah = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $bulan[] = $date->locale('id')->isoFormat('MMM YYYY'); // Jan 2026
            $jumlah[] = $count;
        }

        return view('superAdmin.dashboardSuperAdmin', compact('totalTransaksi', 'petaniAktif', 'petaniPending', 'petaniDitolak', 'bulan', 'jumlah', 'petaniBaru', 'totalProdukAktif', 'totalPendapatan'));
    }
}
