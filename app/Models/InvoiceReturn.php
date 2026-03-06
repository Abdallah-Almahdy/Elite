<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceReturn extends Model
{
    protected $fillable = [
        'invoice_id',
        'notes',
        'type',
        'user_id',
        'total',

    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayments::class, 'invoice_return_id');
    }
    public function returnItems()
    {
        return $this->hasMany(InvoiceReturnItem::class);
    }
}
