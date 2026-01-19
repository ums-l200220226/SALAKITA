@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('pembeli.layout')

@section('content')
<div class="max-w-2xl mx-auto py-2 px-4">
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-[#2d5016] mb-2">Pembayaran QRIS</h1>
        <p class="text-gray-600">Scan QR Code untuk melanjutkan pembayaran</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-8 border-2 border-[#7cb342]">
        <!-- Order Number -->
        <div class="text-center mb-4">
            <p class="text-sm text-gray-600">Nomor Pesanan</p>
            <p class="text-lg font-bold text-[#2d5016]">{{ $order->order_number }}</p>
        </div>

        <!-- QR Code -->
        <div class="flex justify-center mb-6">
            <div class="p-4 bg-white rounded-xl shadow-md">
                @if(str_starts_with($order->qris_url ?? '', 'http'))
                    <!-- Kalau URL gambar real (production) -->
                    <img src="{{ $order->qris_url }}" alt="QR Code QRIS" class="w-72 h-72" id="qrCodeImage">
                @else
                    <!-- Generate QR Code dari string (sandbox) -->
                    <div class="inline-block" id="qrCodeSvg">
                        {!! QrCode::size(288)->generate($order->qris_url ?? 'QRIS-ERROR') !!}
                    </div>
                @endif
            </div>
        </div>

        <!-- Tombol Download QR Code -->
        <div class="flex justify-center mb-6">
            <button type="button" onclick="downloadQRCode()"
                class="bg-[#7cb342] hover:bg-[#689f38] text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transition shadow-lg">
                <i data-lucide="download" class="w-5 h-5"></i>
                Download QR Code
            </button>
        </div>

        <!-- Total Pembayaran -->
        <div class="bg-[#f5f1e8] rounded-xl p-6 mb-6 text-center">
            <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
            <p class="text-4xl font-bold text-[#2d5016]">
                Rp {{ number_format($order->total, 0, ',', '.') }}
            </p>
            <div class="mt-3 text-xs text-gray-600">
                <p>Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                <p>Ongkir: Rp {{ number_format($order->ongkir, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Instruksi Pembayaran -->
        <div class="mb-6">
            <h3 class="font-bold text-[#2d5016] mb-3 flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5"></i>
                Cara Pembayaran:
            </h3>
            <ol class="space-y-3">
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-7 h-7 bg-[#7cb342] text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    <span class="text-sm text-gray-700">Buka aplikasi <strong>GoPay / OVO / Dana / Mobile Banking</strong> kamu</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-7 h-7 bg-[#7cb342] text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                    <span class="text-sm text-gray-700">Pilih menu <strong>"Scan QR"</strong></span>
                </li>
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-7 h-7 bg-[#7cb342] text-white rounded-full flex items-center justify-center text-sm font-bold">3</span>
                    <span class="text-sm text-gray-700">Download QR / scan langsung ke QR Code di atas</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-7 h-7 bg-[#7cb342] text-white rounded-full flex items-center justify-center text-sm font-bold">4</span>
                    <span class="text-sm text-gray-700">Konfirmasi dan selesaikan pembayaran</span>
                </li>
            </ol>
        </div>

        <!-- Status Pembayaran (Hidden, muncul kalau sukses) -->
        <div id="paymentStatus" class="hidden">
            <div class="bg-green-50 border-2 border-green-500 rounded-xl p-6 text-center">
                <div class="text-5xl mb-3">✅</div>
                <p class="text-xl font-bold text-green-800 mb-2">Pembayaran Berhasil!</p>
                <p class="text-sm text-green-600">Pesanan Anda sedang diproses oleh penjual</p>
            </div>
        </div>

        <!-- Mode Testing - Tombol Simulasi -->
        @if(config('xendit.is_sandbox'))
        <div class="mt-6 p-6 bg-yellow-50 border-2 border-yellow-400 rounded-xl">
            <div class="flex items-start gap-3 mb-4">
                <div class="text-3xl">⚠️</div>
                <div class="flex-1">
                    <p class="font-bold text-yellow-900 mb-1">MODE TESTING (SANDBOX)</p>
                    <p class="text-sm text-yellow-800 mb-3">
                        QR Code ini hanya untuk testing. Untuk demo, gunakan tombol simulasi di bawah ini.
                    </p>
                </div>
            </div>
            <button type="button" onclick="simulatePayment()"
                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-4 rounded-xl font-bold text-base transition shadow-lg">
                PEMBAYARAN - TESTING
            </button>
        </div>
        @endif

        <!-- Tombol Kembali -->
        <div class="mt-6 text-center">
            <a href="{{ route('pembeli.riwayatPesanan') }}" class="text-[#4a7c2c] hover:text-[#2d5016] font-semibold text-sm">
                ← Lihat Riwayat Pesanan
            </a>
        </div>

        <!-- Info Otomatis -->
        <div class="mt-4 text-center">
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

        canvas.width = 288;
        canvas.height = 288;

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
    // if (!confirm('Simulasi pembayaran berhasil untuk pesanan ini?')) return;

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
            btn.innerHTML = '💳 SIMULASI PEMBAYARAN - TESTING';
        }
    })
    .catch(error => {
        alert('Error: ' + error);
        btn.disabled = false;
        btn.innerHTML = '💳 SIMULASI PEMBAYARAN - TESTING';
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
