<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceReturnItem extends Model
{
    protected $fillable = [
        'invoice_return_id',
        'invoice_products_id',
        'quantity',
        'price',
        'total',
        'unit_conversion_factor',
    ];

    public function invoiceReturn()
    {
        return $this->belongsTo(InvoiceReturn::class, 'invoice_return_id');
    }

    public function invoiceProduct()
    {
        return $this->belongsTo(InvoiceProduct::class, 'invoice_products_id');
    }
}
