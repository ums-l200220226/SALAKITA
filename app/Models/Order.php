<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'toko_id',
        'metode_penerimaan',
        'province_id',
        'city_id',
        'alamat_lengkap',
        'tanggal_pengambilan',
        'jam_pengambilan',
        'ongkir',
        'metode_pembayaran',
        'status_pembayaran',
        'subtotal',
        'total',
        'catatan',
        'status',
        'qris_id',
        'qris_url',
        'qris_status',
        'qris_expired_at',
        'rating',
        'review',
        'reviewed_at'
    ];

    protected $casts = [
        'ongkir' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    // Relasi ke User (Pembeli)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Toko
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    // Relasi ke Order Items
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke Province (Laravolt Indonesia)
    public function province()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'province_id');
    }

    // Relasi ke City (Laravolt Indonesia)
    public function city()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'city_id');
    }

    // Generate Order Number Otomatis
    public static function generateOrderNumber()
    {
        $date = now()->format('Ymd'); // 20250103
        $lastOrder = self::whereDate('created_at', now())->latest()->first();
        $increment = $lastOrder ? (int) substr($lastOrder->order_number, -3) + 1 : 1;

        return 'ORD-' . $date . '-' . str_pad($increment, 3, '0', STR_PAD_LEFT);
    }
}
