<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        // Hero Section
        'hero_title',
        'hero_description',
        'hero_image',
        'logo_image',
        
        // Features Section
        'features_title',
        
        // History Section
        'history_title',
        'history_content',
        
        // Products Section
        'products_title',
        'products_description',
        'show_products_section',
        
        // Stats
        'stats_transactions',
        'stats_products',
        'stats_rating',
        
        // Location Section
        'location_title',
        'location_address',
        'location_phone',
        'location_email',
        'location_hours',
        'location_map_url',
        
        // CTA Section
        'cta_title',
        'cta_description',
        
        // Footer
        'footer_copyright',
    ];

    protected $casts = [
        'show_products_section' => 'boolean',
        'stats_transactions' => 'integer',
        'stats_products' => 'integer',
        'stats_rating' => 'decimal:1',
    ];

    /**
     * Relasi ke LandingPageFeature
     */
    public function features()
    {
        return $this->hasMany(LandingPageFeature::class)->orderBy('order');
    }

    /**
     * Get the singleton instance of landing page settings
     */
    public static function getSettings()
    {
        $setting = self::with('features')->firstOrCreate(
            ['id' => 1],
            [
                'hero_title' => 'SalaKita',
                'hero_description' => 'Platform agribisnis digital yang menghubungkan petani salak dengan pembeli di seluruh Indonesia',
                'features_title' => 'Kenapa Memilih SalaKita?',
                'history_title' => 'Salak di Desa Panca Tunggal?',
                'history_content' => 'Buah salak (Salacca zalacca) adalah tanaman asli Indonesia yang telah dibudidayakan sejak ratusan tahun lalu. Salah satu varietas paling terkenal adalah Salak Pondoh dari Yogyakarta yang dikenal dengan rasa manis tanpa sepat. Desa Panca Tunggal merupakan salah satu desa yang terletak di Bangka Belitung yang memiliki wisata Agro yaitu salak pondoh.',
                'products_title' => 'Produk Terlaris Kami',
                'products_description' => 'Salak pilihan berkualitas tinggi langsung dari petani lokal',
                'show_products_section' => true,
                'stats_transactions' => 100,
                'stats_products' => 50,
                'stats_rating' => 4.8,
                'location_title' => 'Lokasi Kami',
                'location_address' => "Desa Panca Tunggal\nKecamatan Pulau Besar\nBangka Selatan, Kepulauan Bangka Belitung",
                'location_phone' => '+62 851-4112-4119',
                'location_email' => 'salakita@gmail.com',
                'location_hours' => 'Senin - Minggu: 06:00 - 17:00 WIB',
                'location_map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63741.89475058489!2d106.13476337832031!3d-2.5411968999999987!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e3d5f5b8b8b8b8b%3A0x5f5b8b8b8b8b8b8b!2sDesa%20Panca%20Tunggal%2C%20Bangka%20Belitung!5e0!3m2!1sid!2sid!4v1234567890',
                'cta_title' => 'Siap Mulai Belanja?',
                'cta_description' => 'Bergabunglah dengan ribuan pengguna yang sudah merasakan kemudahan berbelanja salak segar',
                'footer_copyright' => '2024 SalaKita - Platform Agribisnis Digital',
            ]
        );

        // Create default features if not exist
        if ($setting->features->isEmpty()) {
            self::createDefaultFeatures($setting->id);
            $setting->load('features');
        }

        return $setting;
    }

    /**
     * Create default features
     */
    private static function createDefaultFeatures($settingId)
    {
        $defaultFeatures = [
            [
                'title' => 'Langsung dari Petani',
                'description' => 'Beli langsung dari petani lokal tanpa perantara.',
                'icon_svg' => '<path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M7.5 12H6a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-1.5"/>',
                'order' => 1,
            ],
            [
                'title' => 'Harga Transparan',
                'description' => 'Harga jelas dan transparan langsung dari petani.',
                'icon_svg' => '<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="M12 7v10"/><path d="M15.4 10a4 4 0 1 0 0 4"/>',
                'order' => 2,
            ],
            [
                'title' => 'Kualitas Terjamin',
                'description' => 'Salak segar dan berkualitas premium.',
                'icon_svg' => '<path d="M16 16h6"/><path d="M16 8h6"/><path d="M2 12h6"/><path d="M9 2v20"/><path d="M17 6V4c0-1.1-.9-2-2-2h-2c-1.1 0-2 .9-2 2v2"/><path d="M17 20v-2c0-1.1-.9-2-2-2h-2c-1.1 0-2 .9-2 2v2"/>',
                'order' => 3,
            ],
            [
                'title' => 'Transaksi Aman',
                'description' => 'Pembayaran aman dan terpercaya.',
                'icon_svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/>',
                'order' => 4,
            ],
        ];

        foreach ($defaultFeatures as $feature) {
            LandingPageFeature::create([
                'landing_page_setting_id' => $settingId,
                'title' => $feature['title'],
                'description' => $feature['description'],
                'icon_type' => 'svg',
                'icon_svg' => $feature['icon_svg'],
                'order' => $feature['order'],
            ]);
        }
    }
}