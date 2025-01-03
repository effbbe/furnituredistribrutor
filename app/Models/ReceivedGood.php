<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivedGood extends Model
{
    protected $fillable =[
        'received_date',
        'po_number',
        'company_name',
        'items'       
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
        ];
    }
}
