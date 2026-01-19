<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Tampilkan halaman payment QRIS
     */
    public function showQRIS(Order $order)
    {
        // Pastikan order punya QRIS
        if (!$order->qris_url) {
            return redirect()->route('pembeli.riwayat')->with('error', 'Order tidak memiliki QRIS');
        }

        // Pastikan user yang buat order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('pembeli.paymentQRIS', compact('order'));
    }

    /**
     * Simulate payment (hanya untuk testing/sandbox)
     */
    public function simulatePayment(Order $order)
    {
        // Cek apakah sandbox mode
        if (!config('xendit.is_sandbox')) {
            return response()->json([
                'success' => false,
                'message' => 'Simulate payment hanya untuk sandbox mode'
            ], 403);
        }

        // Pastikan user yang buat order
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Update order status
        $order->update([
            'qris_status' => 'paid',
            'status_pembayaran' => 'paid',
            'status' => 'dikonfirmasi',
        ]);

        // TAMBAH INI: Hapus cart setelah payment sukses
        \App\Models\Cart::where('user_id', Auth::id())
            ->whereHas('product', function($q) use ($order) {
                $q->where('toko_id', $order->toko_id);
            })
            ->delete();

        Log::info('Payment simulated', ['order_id' => $order->id]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil disimulasi'
        ]);
    }

    /**
     * Check payment status (untuk auto-refresh)
     */
    public function checkStatus(Order $order)
    {
        try {
            // Cek status di database dulu
            if ($order->qris_status === 'paid') {
                return response()->json([
                    'status' => 'paid',
                    'message' => 'Pembayaran berhasil'
                ]);
            }

            // Kalau belum paid, cek ke Xendit
            if ($order->qris_id) {
                $status = $this->xenditService->getQRISStatus($order->qris_id);

                // Jika status dari Xendit adalah COMPLETED
                if (isset($status['status']) && $status['status'] === 'COMPLETED') {
                    // Update order
                    $order->update([
                        'qris_status' => 'paid',
                        'status_pembayaran' => 'paid',
                        'status' => 'dikonfirmasi',
                    ]);

                    return response()->json([
                        'status' => 'paid',
                        'message' => 'Pembayaran berhasil'
                    ]);
                }
            }

            return response()->json([
                'status' => 'pending',
                'message' => 'Menunggu pembayaran'
            ]);

        } catch (\Exception $e) {
            Log::error('Check payment status error', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengecek status'
            ], 500);
        }
    }

    /**
     * Webhook callback dari Xendit (dipanggil otomatis oleh Xendit)
     */
    public function xenditCallback(Request $request)
    {
        try {
            Log::info('Xendit Callback Received', $request->all());

            // Ambil data dari callback
            $externalId = $request->external_id; // Format: order-123-1234567890
            $status = $request->status; // COMPLETED, PENDING, EXPIRED

            // Extract order ID dari external_id
            preg_match('/order-(\d+)-/', $externalId, $matches);
            $orderId = $matches[1] ?? null;

            if (!$orderId) {
                Log::error('Invalid external_id', ['external_id' => $externalId]);
                return response()->json(['message' => 'Invalid external_id'], 400);
            }

            // Cari order
            $order = Order::find($orderId);

            if (!$order) {
                Log::error('Order not found', ['order_id' => $orderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update order berdasarkan status
            if ($status === 'COMPLETED') {
                $order->update([
                    'qris_status' => 'paid',
                    'status_pembayaran' => 'paid',
                    'status' => 'dikonfirmasi',
                ]);

                // Hapus cart setelah payment sukses
                \App\Models\Cart::where('user_id', $order->user_id)
                    ->whereHas('product', function($q) use ($order) {
                        $q->where('toko_id', $order->toko_id);
                    })
                    ->delete();

                Log::info('Payment completed via webhook', ['order_id' => $order->id]);

            } elseif ($status === 'EXPIRED') {
                $order->update([
                    'qris_status' => 'expired',
                    'status_pembayaran' => 'failed',
                ]);

                Log::info('Payment expired', ['order_id' => $order->id]);
            }

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {
            Log::error('Xendit Callback Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
