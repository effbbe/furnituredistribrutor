<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderDetail extends Model
{
    protected $fillable= [
        'po_number',
        'product_name',
        'quantity',
        'unit_price',
        'unit',
        'amount',
        'created_by'
    ];

    public function po_number_purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_number', 'po_number');
    }   

    public function unit_po_detail(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
    
    public function user_id_po_detail(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
