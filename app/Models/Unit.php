<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'unit_symbols',
        'description'
    ];

    public function unit_po_detail(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }
}
