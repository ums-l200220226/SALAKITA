<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Ambil daftar tahun yang tersedia (dari tahun pertama transaksi sampai tahun sekarang)
        $tahunTersedia = [];
        $tahunPertama = Order::min(DB::raw('YEAR(created_at)'));
        $tahunSekarang = now()->year;

        if ($tahunPertama) {
            for ($year = $tahunPertama; $year <= $tahunSekarang; $year++) {
                $tahunTersedia[] = $year;
            }
        } else {
            // Kalau belum ada transaksi, default tahun sekarang aja
            $tahunTersedia[] = $tahunSekarang;
        }

        // Tahun yang dipilih (default: tahun sekarang)
        $tahunDipilih = request('tahun', $tahunSekarang);

        // Data Grafik Transaksi per Bulan (12 bulan terakhir)
        $bulan = [];
        $jumlah = [];

        for ($i = 1; $i <= 12; $i++) {
            $count = Order::whereYear('created_at', $tahunDipilih)
                    ->whereMonth('created_at', $i)
                    ->where('status', 'selesai')
                    ->count();

            $bulan[] = \Carbon\Carbon::create($tahunDipilih, $i, 1)->locale('id')->isoFormat('MMM YYYY');
            $jumlah[] = $count;
        }

        return view('superAdmin.dashboardSuperAdmin', compact('totalTransaksi', 'petaniAktif', 'petaniPending', 'petaniDitolak', 'bulan', 'jumlah', 'petaniBaru', 'totalProdukAktif', 'totalPendapatan', 'tahunTersedia', 'tahunDipilih'));
    }
}
