<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductViewLog extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'product_id'];
}
