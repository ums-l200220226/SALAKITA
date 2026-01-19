<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Product;
use App\Models\Toko;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'alamat',
        'no_hp',
        'status_aktif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Products
     * Satu petani bisa punya banyak produk
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope: Hanya user dengan role petani
     */
    public function scopePetani($query)
    {
        return $query->where('role', 'petani');
    }

    /**
     * Scope: Hanya user dengan role pembeli
     */
    public function scopePembeli($query)
    {
        return $query->where('role', 'pembeli');
    }

    /**
     * Check apakah user adalah petani
     */
    public function isPetani()
    {
        return $this->role === 'petani';
    }

    /**
     * Check apakah user adalah pembeli
     */
    public function isPembeli()
    {
        return $this->role === 'pembeli';
    }

    public function toko()
    {
        return $this->hasOne(Toko::class, 'petani_id');
    }
}
