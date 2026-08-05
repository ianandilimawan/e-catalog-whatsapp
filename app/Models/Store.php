<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id', 'name', 'slug', 'wa_number', 'theme_color', 'welcome_message', 'logo', 'banner', 'button_rounded', 'dark_mode', 'is_active', 'wa_checkout_clicks',
        'google_analytics_id', 'meta_pixel_id', 'google_search_console_code', 'seo_title', 'seo_description', 'cta_button_text'
    ];
    protected $casts = [
        'button_rounded' => 'boolean',
        'dark_mode' => 'boolean',
        'is_active' => 'boolean'
    ];

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            if (auth()->check() && (request()->is('admin/*') || request()->is('app/*') || request()->is('app'))) {
                // If they are not platform admin, they only see their own store
                if (!auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
                    $builder->where('user_id', auth()->id());
                }
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && (request()->is('admin/*') || request()->is('app/*') || request()->is('app'))) {
                if (empty($model->user_id)) {
                    $model->user_id = auth()->id();
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function visitors()
    {
        return $this->hasMany(\App\Models\StoreVisitor::class, 'store_id');
    }
}
