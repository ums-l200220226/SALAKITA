<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use App\Services\XenditService;

class CheckoutController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    // Tampilkan form checkout
    public function index(Request $request)
    {
        // Ambil cart items yang dipilih
        $cartIds = $request->query('items'); // Format: ?items=1,2,3

        if (!$cartIds) {
            return redirect()->route('pembeli.cart')->with('error', 'Pilih produk yang ingin di-checkout!');
        }

        $cartItems = Cart::with(['product.toko'])
            ->whereIn('id', explode(',', $cartIds))
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('pembeli.cart')->with('error', 'Keranjang kosong!');
        }

        // Cek apakah semua produk dari toko yang sama
        $tokoIds = $cartItems->pluck('product.toko_id')->unique();
        if ($tokoIds->count() > 1) {
            return redirect()->route('pembeli.cart')->with('error', 'Checkout hanya bisa dilakukan untuk produk dari 1 toko!');
        }

        // Hitung subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->harga;
        });

        // Ambil data provinsi & kota
        $provinces = Province::orderBy('name')->get();

        // Ambil alamat user
        $user = Auth::user();

        return view('pembeli.checkout', compact('cartItems', 'subtotal', 'provinces', 'user'));
    }

    // Proses checkout
    public function process(Request $request)
    {
        $request->validate([
            'cart_ids' => 'required|string',
            'metode_penerimaan' => 'required|in:diambil,dikirim',
            // VALIDASI UNTUK DIKIRIM
            'province_code' => 'nullable|required_if:metode_penerimaan,dikirim',
            'city_id' => 'nullable|required_if:metode_penerimaan,dikirim',
            // VALIDASI UNTUK DIAMBIL
            'tanggal_pengambilan' => 'nullable|required_if:metode_penerimaan,diambil|date|after_or_equal:today',
            'jam_pengambilan' => 'nullable|required_if:metode_penerimaan,diambil',

            'metode_pembayaran' => 'required|in:cod,qris',
            'catatan' => 'nullable|string',
        ], [
            // VALIDASI ERROR
            'province_code.required_if' => 'Provinsi wajib dipilih untuk pengiriman',
            'city_id.required_if' => 'Kota wajib dipilih untuk pengiriman',
            'alamat_lengkap.required_if' => 'Alamat lengkap wajib diisi untuk pengiriman',
            'tanggal_pengambilan.required_if' => 'Tanggal pengambilan wajib diisi',
            'tanggal_pengambilan.after_or_equal' => 'Tanggal pengambilan tidak boleh di masa lalu',
            'jam_pengambilan.required_if' => 'Jam pengambilan wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            // Ambil cart items
            $cartIds = explode(',', $request->cart_ids);
            $cartItems = Cart::with('product.toko')
                ->whereIn('id', $cartIds)
                ->where('user_id', Auth::id())
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('pembeli.cart')->with('error', 'Keranjang kosong!');
            }

            // Hitung subtotal
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->harga;
            });

            // Hitung ongkir dan ambil data province
            $ongkir = 0;
            $province = null;
            $tanggalPengambilan = null;
            $jamPengambilan = null;
            $alamatLengkap = null;

            if ($request->metode_penerimaan === 'dikirim' && $request->province_code && $request->city_id) {
                // Ambil province berdasarkan code
                $province = Province::where('code', $request->province_code)->first();
                $ongkir = $this->calculateOngkir($request->province_code, $request->city_id);
                // Alamat dari input user
                $alamatLengkap = Auth::user()->alamat ?? 'Alamat tidak tersedia';

            } elseif ($request->metode_penerimaan === 'diambil') {
                // Ambil data pengambilan
                $tanggalPengambilan = $request->tanggal_pengambilan;
                $jamPengambilan = $request->jam_pengambilan;
                // Alamat dari toko (kolom alamat_toko)
                $toko = $cartItems->first()->product->toko;
                $alamatLengkap = 'Diambil di: ' . ($toko->nama_toko ?? 'Toko') . ' - ' . ($toko->alamat_toko ?? 'Alamat tidak tersedia');
            }

            $total = $subtotal + $ongkir;

            // Tentukan status berdasarkan metode pembayaran
            if ($request->metode_pembayaran === 'cod') {
                $statusPembayaran = 'pending';
                $status = 'dikonfirmasi';
            } else {
                $statusPembayaran = 'pending';
                $status = 'pending';
            }

            // Buat Order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'toko_id' => $cartItems->first()->product->toko_id,
                'metode_penerimaan' => $request->metode_penerimaan,
                'province_id' => $province ? $province->id : null,
                'city_id' => $request->city_id,
                'alamat_lengkap' => $alamatLengkap, // ✅ ISI OTOMATIS DARI TOKO ATAU USER
                'tanggal_pengambilan' => $tanggalPengambilan,
                'jam_pengambilan' => $jamPengambilan,
                'ongkir' => $ongkir,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => $statusPembayaran,
                'subtotal' => $subtotal,
                'total' => $total,
                'catatan' => $request->catatan,
                'status' => $status,
            ]);

            // Buat Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->nama_produk,
                    'price' => $item->product->harga,
                    'quantity' => $item->quantity,
                    'satuan' => $item->product->satuan,
                    'subtotal' => $item->quantity * $item->product->harga,
                ]);

                // Kurangi stok produk
                $item->product->decrement('stok', $item->quantity);
            }

            // Jika metode pembayaran QRIS
            if ($request->metode_pembayaran === 'qris') {
                try {
                    // Generate QRIS via Xendit
                    $qrisData = $this->xenditService->createQRIS($order);

                    // Update order dengan data QRIS
                    $order->update([
                        'qris_id' => $qrisData['id'],
                        'qris_url' => $qrisData['qr_string'],
                        'qris_status' => 'pending',
                    ]);

                    DB::commit();

                    // Redirect ke halaman payment QRIS
                    return redirect()->route('payment.qris', $order->id);

                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Gagal membuat QRIS: ' . $e->getMessage());
                }
            }

            // Jika COD (flow normal)
            // Hapus cart items yang sudah di-checkout
            Cart::whereIn('id', $cartIds)->delete();

            DB::commit();

            return redirect()->route('pembeli.riwayat')->with('success', 'Pesanan berhasil dibuat! Nomor pesanan: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Hitung ongkir berdasarkan provinsi & kota
    private function calculateOngkir($provinceCode, $cityId)
    {
        $province = Province::where('code', $provinceCode)->first();

        // Kalau province ga ketemu, return default
        if (!$province) {
            return 25000; // Default ongkir
        }

        // Contoh: Jawa Barat = 10rb, Luar Jawa = 25rb, dll
        $ongkirMap = [
            'JAWA BARAT' => 10000,
            'JAWA TENGAH' => 15000,
            'JAWA TIMUR' => 15000,
            'DKI JAKARTA' => 12000,
            'BANTEN' => 12000,
        ];

        $provinceName = strtoupper($province->name);

        return $ongkirMap[$provinceName] ?? 25000; // Default 25rb kalau luar Jawa
    }

    // API untuk ambil kota berdasarkan provinsi (untuk AJAX)
    public function getCities($provinceCode)
    {
        $cities = City::where('province_code', $provinceCode)
                ->orderBy('name')
                ->get();

        return response()->json($cities);
    }
}
