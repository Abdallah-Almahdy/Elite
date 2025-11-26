<?php

namespace App\Models;

use App\Models\OrderProductOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Options extends Model
{
    use HasFactory;

    protected $table = 'options';

    protected $fillable = [
        'name',
        'active',
        'product_id'
    ];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_options', 'option_id', 'product_id');
    }


    public function values()
    {
        return $this->hasMany(OptionsValues::class, 'option_id', 'id');
    }

    public function orderValues()
    {
        return $this->belongsToMany(OrderProductOptionValue::class, 'order_product_option_value');
    }

    public function orderProductOptions()
    {
        return $this->hasMany(OrderProductOption::class, 'option_id');
    }









}
