<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // Scope & helper methods tetap ada...
    public function getProductNameAttribute()
    {
        return $this->nama_produk;
    }

    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    public function scopeStokMenipis($query)
    {
        return $query->where('stok', '>', 0)->where('stok', '<', 10);
    }

    public function scopeStokHabis($query)
    {
        return $query->where('stok', 0);
    }

    public function isStokMenipis()
    {
        return $this->stok > 0 && $this->stok < 10;
    }

    public function isStokHabis()
    {
        return $this->stok == 0;
    }
}
