<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviceConfig extends Model
{
    protected $table = 'invice_config';

    protected $fillable = [
        'printerName',
        'password',
        'taxValue',
        'defaultPaymentMethod',
        'defaultInvoiceType',
        'applyTax',
        'taxTypes',
        'user_id',
        'allowedPaymentMethods',
        'allowedInvoiceTypes',

    ];


    protected $casts = [
        'allowedPaymentMethods' => 'array',
        'allowedInvoiceTypes'   => 'array',
    ];
}
