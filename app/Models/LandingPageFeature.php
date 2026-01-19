<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'landing_page_setting_id',
        'title',
        'description',
        'icon_type',
        'icon_svg',
        'icon_image',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Relasi ke LandingPageSetting
     */
    public function landingPageSetting()
    {
        return $this->belongsTo(LandingPageSetting::class);
    }
}