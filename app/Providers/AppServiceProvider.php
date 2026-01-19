<?php

namespace App\Providers;

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share ke semua view
        // tempat setup sekali, pakai di mana-mana!
        // Cart/Keranjang dan Notifikasi
        View::composer('*', function ($view) {
            $newOrdersCount = 0;
            $cartCount = 0;

            if (Auth::check()) {
                // Untuk Pembeli - hitung cart
                if (Auth::user()->role === 'pembeli') {
                    $cartCount = Cart::where('user_id', Auth::id())->count();
                }

                // Untuk Petani - hitung order baru
                if (Auth::user()->role === 'petani') {
                    $newOrdersCount = Order::whereHas('toko', function($query) {
                        $query->where('petani_id', Auth::id());
                    })
                    ->where('status', 'dikonfirmasi')
                    ->count();
                }
            }

            $view->with([
                'newOrdersCount' => $newOrdersCount,
                'cartCount' => $cartCount
            ]);
        });
    }
}
