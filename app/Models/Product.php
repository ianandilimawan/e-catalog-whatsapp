<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'store_id', 'category_id', 'name', 'slug', 'description', 'price', 'image', 'views_count'
    ];
    

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            if (auth()->check() && (request()->is('admin/*') || request()->is('app/*') || request()->is('app'))) {
                if (!auth()->user()->hasRole('admin')) {
                    $store = auth()->user()->store;
                    if ($store) {
                        $builder->where('store_id', $store->id);
                    } else {
                        $builder->whereRaw('1 = 0');
                    }
                }
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && (request()->is('admin/*') || request()->is('app/*') || request()->is('app'))) {
                if (!auth()->user()->hasRole('admin')) {
                    $store = auth()->user()->store;
                    if ($store && empty($model->store_id)) {
                        $model->store_id = $store->id;
                    }
                }
            }
        });
    }

    public function store()
    {
        return $this->belongsTo(\App\Models\Store::class, 'store_id');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    public function viewLogs()
    {
        return $this->hasMany(ProductViewLog::class);
    }
}
