<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddsOn extends Model
{
    use HasFactory;

    protected $table = 'adds_ons';

    protected $fillable = [
        'name',
        'active',
        'price',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_adds_on', 'adds_on_id', 'product_id');
    }

    public function orderProductAddsOns()
    {
        return $this->hasMany(OrderProductAddsOn::class, 'adds_on_id');
    }

    
}
