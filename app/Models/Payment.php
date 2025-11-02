<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'paymentsPaymop';

    protected $fillable = [
        'order_id',
        'provider',
        'provider_payment_id',
        'currency',
        'amount',
        'status',
        'response_data',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
