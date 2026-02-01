<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePayments extends Model
{
    protected $fillable = [
        'invoice_id',
        'payment_method',
        'amount'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
