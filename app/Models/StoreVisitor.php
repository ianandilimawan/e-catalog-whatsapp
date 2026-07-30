<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreVisitor extends Model
{
    protected $fillable = ['store_id', 'ip_address', 'user_agent', 'date'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
