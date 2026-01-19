<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        User::where('id', $id)->update([
            'status_aktif' => 'aktif'
        ]);

        return back()->with('success', 'Petani berhasil disetujui!');
    }

    // TOLAK PETANI
    public function reject($id)
    {
        User::where('id', $id)->update([
            'status_aktif' => 'ditolak'
        ]);

        return back()->with('success', 'Petani ditolak.');
    }
}
