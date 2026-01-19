<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Toko extends Model
{
    use HasFactory;

    protected $fillable = [
        'petani_id',
        'nama_toko',
        'deskripsi_toko',
        'logo_toko',
        'alamat_toko',
        'no_telp_toko',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Petani (User)
     */
    public function petani()
    {
        return $this->belongsTo(User::class, 'petani_id');
    }

    /**
     * Relasi ke Products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'toko_id')
                ->where('status', 'aktif');
    }

    /**
     * Get active products only
     */
    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('status', 'aktif');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'petani_id', 'id');
    }
}
