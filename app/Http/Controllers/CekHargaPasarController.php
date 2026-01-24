<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CekHargaPasarController extends Controller
{
    public function cekHargaPasar()
    {
        $products = Product::where('user_id', '!=', Auth::id())
                            ->where('status', 'aktif')
                            ->with('user', 'toko')
                            ->latest()
                            ->paginate(20);

        return view('petani.cekHargaPasar', compact('products'));
    }
}
