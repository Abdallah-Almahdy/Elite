<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class promo_code_products extends Model
{
    protected $table = 'promo_code_products';

    protected $fillable = [
        'promo_code_id',
        'product_id',
    ];
    
     public function products(){
        return $this->belongsTo(Product::class);
     }

     public function promo_code()
     {
        return $this->belongsTo(PromoCode::class);

     }



}
