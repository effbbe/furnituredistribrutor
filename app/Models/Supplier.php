<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'phone',
        'npwp',
        'contact_name',
        'contact_phone',
    ];

    public function supplier(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
