<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'product_name',
        'slug',
        'description',
        'unit_price',
        'current_stock',
        'unit',
        'category'        
    ];
}

