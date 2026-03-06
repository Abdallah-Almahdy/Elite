<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePayments extends Model
{
    protected $fillable = [
        'invoice_id',
        'payment_method',
        'amount',
        'invoice_return_id',
        'type'
    ];
    protected $table = 'Invoice_payments';

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }


    public function invoiceReturn()
    {
        return $this->belongsTo(InvoiceReturn::class, 'invoice_return_id');
    }
}
