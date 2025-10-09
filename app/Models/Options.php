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
    ];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_options', 'option_id', 'product_id')
            ->withPivot('option_name', 'price_adjustment', 'option_value')
            ->withTimestamps();
    }


    public function values()
    {
        return $this->hasMany(OptionsValues::class, 'option_id', 'id');
    }

    public function orderProductOptions()
    {
        return $this->hasMany(OrderProductOption::class, 'option_id');
    }
    
}
