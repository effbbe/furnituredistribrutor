<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'po_date',
        'supplier_id',
        'supplier_address',
        'supplier_phone',
        'supplier_npwp',
        'contact_name',
        'contact_phone',
        'total_amount',
        'created_by'
    ];
    
   public function po_number_purchase_order_detail(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'po_number', 'po_number');
    }

    public function user_id_po(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
