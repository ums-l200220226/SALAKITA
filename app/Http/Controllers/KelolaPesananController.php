<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KelolaPesananController extends Controller
{
    public function index(Request $request)
    {
        $toko = Auth::user()->toko;

        if (!$toko) {
            return redirect()->route('petani.dashboardPetani')
                ->with('error', 'Anda belum memiliki toko.');
        }

        // Base query untuk pesanan yang valid
        $baseQuery = function($query) use ($toko) {
            return $query->where('toko_id', $toko->id)
                ->where(function($q) {
                    $q->where('metode_pembayaran', 'cod')
                        ->orWhere(function($q2) {
                            $q2->where('metode_pembayaran', 'qris')
                                ->where('status_pembayaran', 'paid');
                        });
                });
        };

        // ✅ HITUNG TOTAL SEMUA PESANAN (tidak terfilter status)
        $totalPesananQuery = Order::query();
        $baseQuery($totalPesananQuery);
        $totalPesanan = $totalPesananQuery->count();

        // ✅ HITUNG JUMLAH PER STATUS (termasuk pending)
        $statusCountsQuery = Order::query();
        $baseQuery($statusCountsQuery);

        $statusCounts = $statusCountsQuery
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->map(fn($count) => (int) $count)
            ->toArray();

        // Query pesanan untuk tampilan (dengan pagination)
        $query = Order::with(['user', 'items.product', 'province', 'city']);
        $baseQuery($query);

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Urutkan dari yang terbaru
        $pesanan = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('petani.kelolaPesanan', compact('pesanan', 'statusCounts', 'totalPesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validasi kepemilikan pesanan
        if ($order->toko_id != Auth::user()->toko->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $request->validate([
            'status' => 'required|in:dikonfirmasi,diproses,dikirim,selesai,dibatalkan'
        ]);

        $newStatus = $request->status;
        $oldStatus = $order->status;

        // Validasi flow status
        $allowedTransitions = [
            'pending' => ['dikonfirmasi', 'dibatalkan'],
            'dikonfirmasi' => ['diproses', 'dibatalkan'],
            'diproses' => ['dikirim', 'selesai','dibatalkan'],
            'dikirim' => [],
            'selesai' => [],
            'dibatalkan' => []
        ];

        // MODIFIKASI: Untuk metode DIAMBIL, dari "diproses" langsung ke "selesai" (skip "dikirim")
        if ($order->metode_penerimaan == 'diambil') {
            $allowedTransitions['diproses'] = ['selesai', 'dibatalkan']; // Hapus "dikirim"
        }

        // MODIFIKASI: Untuk metode DIKIRIM, dari "diproses" harus ke "dikirim" dulu (tidak bisa langsung "selesai")
        if ($order->metode_penerimaan == 'dikirim') {
            $allowedTransitions['diproses'] = ['dikirim', 'dibatalkan']; // Hapus "selesai"
        }

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            return back()->with('error', 'Perubahan status tidak valid.');
        }

        DB::beginTransaction();
        try {
            // ✅✅✅ RESTORE STOK JIKA PESANAN DIBATALKAN ✅✅✅
            if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
                foreach ($order->items as $item) {
                    // Kembalikan stok produk
                    $item->product->increment('stok', $item->quantity);
                }
            }

            // Khusus untuk metode "diambil", hanya petani yang bisa set selesai
            if ($newStatus == 'selesai' && $order->metode_penerimaan == 'diambil') {
            }

            // Khusus untuk metode "dikirim", HANYA PEMBELI yang bisa set selesai
            if ($newStatus == 'selesai' && $order->metode_penerimaan == 'dikirim') {
                return back()->with('error', 'Pesanan dengan metode "dikirim" hanya bisa diselesaikan oleh pembeli setelah menerima barang.');
            }

            $order->status = $newStatus;

            // ✅✅✅ JIKA STATUS JADI SELESAI DAN METODE COD, UPDATE STATUS PEMBAYARAN ✅✅✅
            if ($newStatus === 'selesai' && $order->metode_pembayaran === 'cod') {
                $order->status_pembayaran = 'paid';
            }

            $order->save();

            DB::commit();

            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product', 'toko'])
            ->findOrFail($id);

        // Validasi kepemilikan
        if ($order->toko_id != Auth::user()->toko->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        return view('petani.pesananDetail', compact('order'));
    }
}
