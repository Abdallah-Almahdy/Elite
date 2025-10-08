<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProductOption extends Model
{
    use HasFactory;

    protected $table = 'order_product_options';

    protected $fillable = [
        'order_product_id',
        'option_id',
    ];

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function option()
    {
        return $this->belongsTo(Options::class);
    }

    public function values()
    {
        return $this->hasMany(OrderProductOptionValue::class, 'order_product_option_id');
    }
}
