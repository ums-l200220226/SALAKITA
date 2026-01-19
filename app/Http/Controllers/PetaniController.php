<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class PetaniController extends Controller
{
    public function index()
    {
        return "Dashboard petani";
    }

    public function profil()
    {
        return view('petani.profil');
    }

    // Update profil petani (nama, no_hp, alamat)
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $user->update($validated);

        return redirect()->route('petani.profil')
                         ->with('success', 'Profil berhasil diperbarui!');
    }

    // Update password petani
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        // Update password baru
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('petani.profil')
                         ->with('success', 'Password berhasil diubah!');
    }


}
