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
        'user_id', 'name', 'slug', 'wa_number', 'theme_color', 'welcome_message', 'logo', 'banner', 'button_rounded', 'dark_mode', 'wa_checkout_clicks'
    ];
    protected $casts = [
        'button_rounded' => 'boolean',
        'dark_mode' => 'boolean'
    ];

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            if (auth()->check() && request()->is('admin/*')) {
                // If they are not admin, they only see their own store
                if (!auth()->user()->hasRole('admin')) {
                    $builder->where('user_id', auth()->id());
                }
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && request()->is('admin/*')) {
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
