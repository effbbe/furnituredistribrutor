<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone',
        'npwp',
        'contact_name',
        'contact_phone'
    ];
    
    /**
     * Get all of the invoice for the Customers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoice(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
