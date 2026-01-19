@extends('superAdmin.layout')

@section('content')

{{-- Header Section --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#2d5016] mb-1">Konfirmasi Petani</h2>
        <p class="text-sm text-[#6d4c41]">Kelola pendaftaran petani yang menunggu persetujuan</p>
    </div>

    <div class="flex items-center gap-4">
        <div class="bg-white px-4 py-2 rounded-lg shadow-sm text-center">
            <p class="text-xs text-[#6d4c41]">Total Pending</p>
            <p class="text-2xl font-bold text-[#ff8f00]">{{ $petaniPending->count() }}</p>
        </div>
    </div>
</div>

{{-- Modal Riwayat Konfirmasi --}}
<div id="historyModal"
     class="hidden fixed inset-0 bg-[#8d6e63] bg-opacity-20 backdrop-blur-sm z-50
            flex items-center justify-center p-4">

    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">

        {{-- Header --}}
        <div class="bg-[#2d5016] px-6 py-4 sticky top-0 flex justify-center items-center">
            <h3 class="text-xl font-bold text-white">Riwayat Konfirmasi Petani</h3>
        </div>

        {{-- Body --}}
        <div class="p-6">
            <p class="text-sm text-gray-500 mb-4">
                Daftar petani yang telah disetujui atau ditolak.
            </p>

            {{-- TABLE (NANTI ISI DATA) --}}
            <div class="overflow-x-auto">
                <table class="w-full border">
                    <thead class="bg-[#f5f1e8]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-[#2d5016]">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-[#2d5016]">Email</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-[#2d5016]">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-[#2d5016]">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($riwayatPetani as $p)
                        <tr class="hover:bg-[#f5f1e8]">
                            <td class="px-4 py-3 text-sm text-[#2d5016] font-medium">
                                {{ $p->name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-[#6d4c41]">
                                {{ $p->email }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($p->status_aktif === 'aktif')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            bg-green-100 text-green-700">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            bg-red-100 text-red-700">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">
                                {{ $p->updated_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                Belum ada riwayat konfirmasi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 px-6 py-4 flex justify-end">
            <button onclick="closeHistoryModal()"
                class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Tutup
            </button>
        </div>

    </div>
</div>

{{-- Table Card --}}
<div class="bg-white rounded-xl shadow-md overflow-hidden">

    {{-- Table Header --}}
    <div class="bg-[#2d5016] px-6 py-4 text-center">
        <h3 class="text-lg font-semibold text-white">Daftar Petani Menunggu Konfirmasi</h3>
    </div>

    {{-- Table Content --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-[#f5f1e8] border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#2d5016] uppercase tracking-wider">
                        No
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#2d5016] uppercase tracking-wider">
                        Nama Petani
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#2d5016] uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#2d5016] uppercase tracking-wider">
                        Tanggal Daftar
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-[#2d5016] uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse($petaniPending as $index => $p)
                <tr class="hover:bg-[#f5f1e8] transition-colors">
                    {{-- Nomor --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-[#2d5016]">{{ $index + 1 }}</span>
                    </td>

                    {{-- Nama --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#4a7c2c] rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr($p->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#2d5016]">{{ $p->name }}</p>
                                <p class="text-xs text-[#6d4c41]">Petani Baru</p>
                            </div>
                        </div>
                    </td>

                    {{-- Email --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4 text-[#6d4c41]"></i>
                            <span class="text-sm text-[#6d4c41]">{{ $p->email }}</span>
                        </div>
                    </td>

                    {{-- Tanggal Daftar --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-[#6d4c41]"></i>
                            <span class="text-sm text-[#6d4c41]">{{ $p->created_at->format('d M Y') }}</span>
                        </div>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Tombol Detail --}}
                            <button type="button"
                                    onclick="showDetailModal({
                                        name: @js($p->name),
                                        email: @js($p->email),
                                        nama_toko: @js(optional($p->toko)->nama_toko),
                                        no_hp: @js($p->no_hp),
                                        alamat: @js($p->alamat),
                                        created_at: @js($p->created_at)
                                    })"
                                    class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                Detail
                            </button>

                            {{-- Tombol Setujui --}}
                            <button type="button"
                                    onclick="showApproveModal({{ $p->id }}, '{{ $p->name }}')"
                                    class="inline-flex items-center gap-2 px-3 py-2 bg-[#4a7c2c] text-white text-sm font-medium rounded-lg hover:bg-[#2d5016] transition-all duration-200 shadow-sm hover:shadow-md">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                Setujui
                            </button>

                            {{-- Tombol Tolak --}}
                            <button type="button"
                                    onclick="showRejectModal({{ $p->id }}, '{{ $p->name }}')"
                                    class="inline-flex items-center gap-2 px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>

                @empty
                {{-- Empty State --}}
                <tr>
                    <td colspan="5" class="px-6 py-12">
                        <div class="flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 bg-[#f5f1e8] rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="inbox" class="w-10 h-10 text-[#6d4c41]"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-[#2d5016] mb-2">Tidak Ada Data</h3>
                            <p class="text-sm text-[#6d4c41]">Tidak ada petani yang menunggu konfirmasi saat ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Info Card --}}
@if($petaniPending->count() > 0)
<div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
    <div class="flex items-start gap-3">
        <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5"></i>
        <div>
            <p class="text-sm font-semibold text-blue-900">Informasi</p>
            <p class="text-sm text-blue-800 mt-1">
                Pastikan Anda memeriksa data petani dengan teliti sebelum memberikan persetujuan. Klik tombol "Detail" untuk melihat informasi lengkap.
            </p>
        </div>
    </div>
</div>
@endif

{{-- Modal Detail --}}
<div id="detailModal"
     class="hidden fixed inset-0 bg-[#8d6e63] bg-opacity-20 backdrop-blur-sm z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

        {{-- Modal Header --}}
        <div class="bg-[#2d5016] px-6 py-4 sticky top-0 flex items-center justify-center">
            <h3 class="text-xl font-bold text-white">Detail Data Petani</h3>
        </div>

        {{-- Modal Body --}}
        <div class="p-6">
            <div class="space-y-4">

                {{-- Nama --}}
                <div class="flex items-start gap-3 pb-3 border-b">
                    <div class="w-10 h-10 bg-[#4a7c2c]/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5 text-[#4a7c2c]"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Nama Lengkap</p>
                        <p id="detail-name" class="text-sm font-semibold text-[#2d5016]">-</p>
                    </div>
                </div>

                {{-- Email --}}
                <div class="flex items-start gap-3 pb-3 border-b">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="mail" class="w-5 h-5 text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Email</p>
                        <p id="detail-email" class="text-sm font-semibold text-[#2d5016]">-</p>
                    </div>
                </div>

                {{-- Nama Toko --}}
                <div class="flex items-start gap-3 pb-3 border-b">
                    <div class="w-10 h-10 bg-[#6d4c41]/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="store" class="w-5 h-5 text-[#6d4c41]"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Nama Toko</p>
                        <p id="detail-store" class="text-sm font-semibold text-[#2d5016]">-</p>
                    </div>
                </div>

                {{-- No Telepon --}}
                <div class="flex items-start gap-3 pb-3 border-b">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="phone" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">No. Telepon</p>
                        <p id="detail-phone" class="text-sm font-semibold text-[#2d5016]">-</p>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="flex items-start gap-3 pb-3 border-b">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="map-pin" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Alamat</p>
                        <p id="detail-address" class="text-sm font-semibold text-[#2d5016]">-</p>
                    </div>
                </div>

                {{-- Tanggal Daftar --}}
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="calendar" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal Pendaftaran</p>
                        <p id="detail-date" class="text-sm font-semibold text-[#2d5016]">-</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="bg-gray-50 px-6 py-4 flex justify-end">
            <button onclick="closeDetailModal()"
                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                Tutup
            </button>
        </div>

    </div>
</div>

{{-- Modal Konfirmasi Setujui --}}
<div id="approveModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-circle" class="w-8 h-8 text-green-600"></i>
            </div>
            <h3 class="text-xl font-bold text-center text-[#2d5016] mb-2">Setujui Petani?</h3>
            <p class="text-center text-gray-600 mb-6">
                Apakah Anda yakin ingin menyetujui pendaftaran petani <strong id="approve-name"></strong>?
            </p>
            <div class="flex gap-3">
                <button onclick="closeApproveModal()"
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Batal
                </button>
                <form id="approveForm" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full px-4 py-3 bg-[#4a7c2c] text-white rounded-lg hover:bg-[#2d5016] transition font-medium">
                        Ya, Setujui
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Tolak --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="x-circle" class="w-8 h-8 text-red-600"></i>
            </div>
            <h3 class="text-xl font-bold text-center text-[#2d5016] mb-2">Tolak Petani?</h3>
            <p class="text-center text-gray-600 mb-6">
                Apakah Anda yakin ingin menolak pendaftaran petani <strong id="reject-name"></strong>?
            </p>
            <div class="flex gap-3">
                <button onclick="closeRejectModal()"
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Batal
                </button>
                <form id="rejectForm" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        Ya, Tolak
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- RIWAYAT KONFIRMASI --}}
<div class="flex justify-end mt-4">
    <button onclick="showHistoryModal()"
        class="bg-white px-4 py-3 rounded-lg shadow-sm
               flex items-center gap-3
               text-[#2d5016] hover:bg-[#f5f1e8]
               transition">
        <div class="w-8 h-8 rounded-full bg-[#f5f1e8] flex items-center justify-center">
            <i data-lucide="history" class="w-4 h-4 text-[#2d5016]"></i>
        </div>

        <div class="text-left">
            <p class="text-sm font-semibold leading-none">Riwayat Konfirmasi</p>
        </div>
    </button>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();

    // Modal Riwayat
    function showHistoryModal() {
        document.getElementById('historyModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeHistoryModal() {
        document.getElementById('historyModal').classList.add('hidden');
    }

    // Detail Modal Functions
    function showDetailModal(petani) {
        document.getElementById('detail-name').textContent = petani.name || '-';
        document.getElementById('detail-email').textContent = petani.email || '-';
        document.getElementById('detail-store').textContent = petani.nama_toko || '-';
        document.getElementById('detail-phone').textContent = petani.no_hp || '-';
        document.getElementById('detail-address').textContent = petani.alamat || '-';

        // Format tanggal
        const date = new Date(petani.created_at);
        const formattedDate = date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('detail-date').textContent = formattedDate;

        document.getElementById('detailModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Approve Modal Functions
    function showApproveModal(id, name) {
        document.getElementById('approve-name').textContent = name;
        document.getElementById('approveForm').action =
            "{{ route('superAdmin.approvePetani', ':id') }}".replace(':id', id);
        document.getElementById('approveModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
    }

    // Reject Modal Functions
    function showRejectModal(id, name) {
        document.getElementById('reject-name').textContent = name;
        document.getElementById('rejectForm').action =
            "{{ route('superAdmin.rejectPetani', ':id') }}".replace(':id', id);
        document.getElementById('rejectModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const detailModal = document.getElementById('detailModal');
        const approveModal = document.getElementById('approveModal');
        const rejectModal = document.getElementById('rejectModal');

        if (event.target === detailModal) {
            closeDetailModal();
        }
        if (event.target === approveModal) {
            closeApproveModal();
        }
        if (event.target === rejectModal) {
            closeRejectModal();
        }
    }

    // Close modals with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetailModal();
            closeApproveModal();
            closeRejectModal();
        }
    });
</script>

@endsection
