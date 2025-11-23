<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class productUintComponent extends Model
{
    protected $table = 'product_unit_components';
    protected $fillable =
    [
        'product_unit_id',
        'product_id',
        'component_unit_id',
        'quantity'
    ];

    public function productUnit()
    {
        return $this->belongsTo(ProductUnits::class, 'product_unit_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function componentUnit()
    {
        return $this->belongsTo(ProductUnits::class, 'component_unit_id');
    }

}
