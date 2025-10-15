<?php

namespace App\Models;

use App\Models\OptionsValues;
use App\Models\OrderProductOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProductOptionValue extends Model
{
    use HasFactory;

    protected $table = 'order_product_option_value';

    protected $fillable = [
        'order_product_option_id',
        'option_value_id',
        'price',
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function orderProductOption()
    {
        return $this->belongsTo(OrderProductOption::class);
    }

    public function value()
    {
        return $this->belongsTo(OptionsValues::class, 'option_value_id');
    }


}
