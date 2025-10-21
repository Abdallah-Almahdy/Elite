<?php

namespace App\Models;

use App\Models\AddsOn;
use App\Models\orderProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProductAddsOn extends Model
{
    use HasFactory;

    protected $table = 'order_product_adds_on';

    protected $fillable = [
        'order_product_id',
        'adds_on_id',
        'active',
        "quantity"
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function addsOn()
    {
        return $this->belongsTo(AddsOn::class, 'adds_on_id');
    }
}
