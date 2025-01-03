<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
      
        protected $fillable = [
            'invoice_number',
            'invoice_date',
            'subtotal',
            'tax',
            'total',
            'customer_id'
        ];

        /**
       * Get all of the invoicedetails for the Invoice
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function invoice_detail(): HasMany
      {
          return $this->hasMany(InvoiceDetail::class);
      }

      /**
       * Get the customer that owns the Invoice
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function customer(): BelongsTo
      {
          return $this->belongsTo(Customer::class);
      }
}
