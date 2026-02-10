@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('pembeli.layout')

@section('content')
<div class="max-w-6xl mx-auto py-1 px-3 sm:px-4 lg:py-4">
    <!-- Header - Lebih Compact -->
    <div class="text-center mb-3 lg:mb-4">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#2d5016] mb-1">Pembayaran QRIS</h1>
        <p class="text-xs sm:text-sm text-gray-600">Scan QR Code untuk melanjutkan pembayaran</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-3 sm:p-4 lg:p-6 border-2 border-[#7cb342]">
        <!-- Order Number - Lebih Compact -->
        <div class="text-center mb-3 lg:mb-4">
            <p class="text-xs text-gray-600">Nomor Pesanan</p>
            <p class="text-sm lg:text-base font-bold text-[#2d5016]">{{ $order->order_number }}</p>
        </div>

        <!-- Layout 2 Kolom untuk Desktop, 1 Kolom untuk Mobile -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-3 lg:mb-4">

            <!-- KOLOM KIRI: QR Code -->
            <div class="flex flex-col items-center justify-start">
                <!-- QR Code - Ukuran Lebih Kecil untuk Desktop -->
                <div class="p-2 sm:p-3 bg-white rounded-xl shadow-md mb-3">
                    @if(str_starts_with($order->qris_url ?? '', 'http'))
                        <!-- Kalau URL gambar real (production) -->
                        <img src="{{ $order->qris_url }}" alt="QR Code QRIS"
                             class="w-48 h-48 sm:w-56 sm:h-56 lg:w-52 lg:h-52" id="qrCodeImage">
                    @else
                        <!-- Generate QR Code dari string (sandbox) -->
                        <div class="flex justify-center items-center" id="qrCodeSvg">
                            {!! QrCode::size(208)->generate($order->qris_url ?? 'QRIS-ERROR') !!}
                        </div>
                    @endif
                </div>

                <!-- Tombol Download QR Code -->
                <button type="button" onclick="downloadQRCode()"
                    class="bg-[#7cb342] hover:bg-[#689f38] text-white px-4 py-2 lg:py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition shadow-lg">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Download QR Code
                </button>
            </div>

            <!-- KOLOM KANAN: Informasi & Instruksi -->
            <div class="flex flex-col justify-start">

                <!-- Total Pembayaran - Lebih Compact -->
                <div class="bg-[#f5f1e8] rounded-xl p-3 lg:p-4 mb-3 lg:mb-4 text-center">
                    <p class="text-xs text-gray-600 mb-1">Total Pembayaran</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#2d5016]">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </p>
                    <div class="mt-1.5 lg:mt-2 text-xs text-gray-600">
                        <p>Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                        <p>Ongkir: Rp {{ number_format($order->ongkir, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Instruksi Pembayaran - Lebih Compact -->
                <div>
                    <h3 class="font-bold text-[#2d5016] mb-2 flex items-center gap-2 text-xs sm:text-sm">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        Cara Pembayaran:
                    </h3>
                    <ol class="space-y-1.5 lg:space-y-2">
                        <li class="flex gap-2">
                            <span class="flex-shrink-0 w-5 h-5 lg:w-6 lg:h-6 bg-[#7cb342] text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            <span class="text-xs text-gray-700">Buka aplikasi <strong>GoPay / OVO / Dana / Mobile Banking</strong> kamu</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="flex-shrink-0 w-5 h-5 lg:w-6 lg:h-6 bg-[#7cb342] text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <span class="text-xs text-gray-700">Pilih menu <strong>"Scan QR"</strong></span>
                        </li>
                        <li class="flex gap-2">
                            <span class="flex-shrink-0 w-5 h-5 lg:w-6 lg:h-6 bg-[#7cb342] text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <span class="text-xs text-gray-700">Download QR / scan langsung ke QR Code di atas</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="flex-shrink-0 w-5 h-5 lg:w-6 lg:h-6 bg-[#7cb342] text-white rounded-full flex items-center justify-center text-xs font-bold">4</span>
                            <span class="text-xs text-gray-700">Konfirmasi dan selesaikan pembayaran</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Status Pembayaran (Hidden, muncul kalau sukses) -->
        <div id="paymentStatus" class="hidden mb-3 lg:mb-4">
            <div class="bg-green-50 border-2 border-green-500 rounded-xl p-3 lg:p-4 text-center">
                <div class="text-3xl lg:text-4xl mb-2">✅</div>
                <p class="text-base lg:text-lg font-bold text-green-800 mb-1">Pembayaran Berhasil!</p>
                <p class="text-xs text-green-600">Pesanan Anda sedang diproses oleh penjual</p>
            </div>
        </div>

        <!-- Mode Testing - Tombol Simulasi - Lebih Compact -->
        @if(config('xendit.is_sandbox'))
        <div class="mt-3 lg:mt-4 p-3 lg:p-4 bg-yellow-50 border-2 border-yellow-400 rounded-xl">
            <div class="flex items-start gap-2 mb-2 lg:mb-3">
                <div class="text-xl lg:text-2xl">⚠️</div>
                <div class="flex-1">
                    <p class="font-bold text-yellow-900 mb-1 text-xs sm:text-sm">MODE TESTING (SANDBOX)</p>
                    <p class="text-xs text-yellow-800 mb-2">
                        QR Code ini hanya untuk testing. Untuk demo, gunakan tombol simulasi di bawah ini.
                    </p>
                </div>
            </div>
            <button type="button" onclick="simulatePayment()"
                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 lg:py-3 rounded-xl font-bold text-xs sm:text-sm transition shadow-lg">
                PEMBAYARAN - TESTING
            </button>
        </div>
        @endif

        <!-- Tombol Kembali & Info - Lebih Compact -->
        <div class="mt-3 lg:mt-4 text-center space-y-1.5">
            <a href="{{ route('pembeli.riwayatPesanan') }}" class="text-[#4a7c2c] hover:text-[#2d5016] font-semibold text-xs inline-block">
                ← Lihat Riwayat Pesanan
            </a>
            <p class="text-xs text-gray-500">
                Halaman ini akan otomatis refresh setelah pembayaran berhasil
            </p>
        </div>
    </div>
</div>

<script>
// Download QR Code
function downloadQRCode() {
    const qrImage = document.getElementById('qrCodeImage');
    const qrSvg = document.getElementById('qrCodeSvg');

    if (qrImage) {
        // Kalau pakai URL gambar (production)
        fetch(qrImage.src)
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'QRIS-{{ $order->order_number }}.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            });
    } else if (qrSvg) {
        // Kalau pakai SVG (sandbox)
        const svg = qrSvg.querySelector('svg');
        const svgData = new XMLSerializer().serializeToString(svg);
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();

        canvas.width = 208;
        canvas.height = 208;

        img.onload = function() {
            ctx.drawImage(img, 0, 0);
            canvas.toBlob(function(blob) {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'QRIS-{{ $order->order_number }}.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            });
        };

        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
    }
}

// Simulasi payment untuk testing
function simulatePayment() {
    // Show loading
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '⏳ Memproses...';

    fetch("{{ route('payment.simulate', $order->id) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            document.getElementById('paymentStatus').classList.remove('hidden');

            // Redirect after 2 seconds
            setTimeout(() => {
                window.location.href = "{{ route('pembeli.riwayatPesanan') }}";
            }, 2000);
        } else {
            alert('Gagal: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = 'PEMBAYARAN - TESTING';
        }
    })
    .catch(error => {
        alert('Error: ' + error);
        btn.disabled = false;
        btn.innerHTML = 'PEMBAYARAN - TESTING';
    });
}

// Auto check payment status (untuk production/real payment)
let checkInterval = setInterval(() => {
    fetch("{{ route('payment.check', $order->id) }}")
        .then(response => response.json())
        .then(data => {
            if (data.status === 'paid') {
                clearInterval(checkInterval);
                document.getElementById('paymentStatus').classList.remove('hidden');
                setTimeout(() => {
                    window.location.href = "{{ route('pembeli.riwayatPesanan') }}";
                }, 2000);
            }
        })
        .catch(error => console.log('Check status error:', error));
}, 5000); // Check setiap 5 detik

// Initialize Lucide icons
lucide.createIcons();
</script>
@endsection
