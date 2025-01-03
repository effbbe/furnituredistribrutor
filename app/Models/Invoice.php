<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
      /**
       * Get all of the invoicedetails for the Invoice
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
      public function invoice_edetails(): HasMany
      {
          return $this->hasMany(InvoiceDetails::class);
      }

      /**
       * Get the customer that owns the Invoice
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function customer(): BelongsTo
      {
          return $this->belongsTo(Customers::class);
      }
}
