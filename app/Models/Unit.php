<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

  
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_units', 'unit_id', 'product_id')
                    ->withPivot('conversion_factor', 'price', 'sallprice', 'is_base')
                    ->withTimestamps();
    }


}
