<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi mass assignment
     */
    protected $fillable = [
        'user_id',
        'toko_id',
        'nama_produk',
        'kategori',
        'harga',
        'satuan',
        'stok',
        'deskripsi',
        'gambar',
        'status',
        'total_terjual',
    ];

    // Tambahkan accessor ini agar bisa pakai $product->product_name
    public function getProductNameAttribute()
    {
        return $this->nama_produk;
    }

    /**
     * Relasi ke User (Petani)
     * Satu produk dimiliki oleh satu petani
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Format harga ke Rupiah
     */
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Scope: Hanya produk yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope: Filter berdasarkan kategori
     */
    public function scopeKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    /**
     * Scope: Stok menipis (kurang dari 10)
     */
    public function scopeStokMenipis($query)
    {
        return $query->where('stok', '>', 0)->where('stok', '<', 10);
    }

    /**
     * Scope: Stok habis
     */
    public function scopeStokHabis($query)
    {
        return $query->where('stok', 0);
    }

    /**
     * Check apakah stok menipis
     */
    public function isStokMenipis()
    {
        return $this->stok > 0 && $this->stok < 10;
    }

    /**
     * Check apakah stok habis
     */
    public function isStokHabis()
    {
        return $this->stok == 0;
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // Accessor: Hitung rata-rata rating dari orders
    // Rating ada di tabel orders, product_id ada di order_items

    public function getAverageRatingAttribute()
    {
        return $this->orderItem()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotNull('orders.rating')
            ->avg('orders.rating') ?? 0;
    }

    // Accessor: Hitung jumlah total rating/review
    public function getTotalRatingsAttribute()
    {
        return $this->orderItem()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotNull('orders.rating')
            ->count();
    }
}
