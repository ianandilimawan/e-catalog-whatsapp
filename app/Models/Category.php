<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'store_id', 'name', 'slug'
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
                        $builder->whereRaw('1 = 0'); // No store = see nothing
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

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'category_id');
    }
}
