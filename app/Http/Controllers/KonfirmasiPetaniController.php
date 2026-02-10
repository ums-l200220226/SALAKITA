<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PetaniApproved;
use App\Mail\PetaniRejected;

class KonfirmasiPetaniController extends Controller
{
    // TAMPIL DATA PETANI PENDING
    public function index()
    {
        $petaniPending = User::where('role', 'petani')
                             ->where('status_aktif', 'pending')
                             ->with('toko')
                             ->get();

        $riwayatPetani = User::where('role', 'petani')
                            ->whereIn('status_aktif', ['aktif', 'ditolak'])
                            ->orderBy('updated_at', 'desc')
                            ->get();

        return view('superAdmin.konfirmasiPetani', compact('petaniPending', 'riwayatPetani'));
    }

    // SETUJUI PETANI
    public function approve($id)
    {
        // Mencari petani berdasarkan ID
        $petani = User::findOrFail($id);

        // Update status jadi aktif
        $petani->update([
        'status_aktif' => 'aktif'

        ]);

        // Kirim email approval
        Mail::to($petani->email)->send(new PetaniApproved($petani));

        return back()->with('success', 'Petani berhasil disetujui dan email telah dikirim!');
    }

    // TOLAK PETANI
    public function reject(Request $request, $id)
    {
        // Validasi input alasan
        $request->validate([
            'reason' => 'required|string|min:10'
        ], [
            'reason.required' => 'Alasan penolakan harus diisi',
            'reason.min' => 'Alasan penolakan minimal 10 karakter'
        ]);

        // Mencari petani berdasarkan ID
        $petani = User::findOrFail($id);

        // Update status jadi ditolak
        $petani->update([
            'status_aktif' => 'ditolak'
        ]);

        // Kirim email rejection dengan alasan
        Mail::to($petani->email)->send(new PetaniRejected($petani, $request->reason));

        return back()->with('success', 'Petani ditolak dan email telah dikirim!');
    }
}
