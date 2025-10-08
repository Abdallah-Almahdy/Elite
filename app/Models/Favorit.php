<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorit extends Model
{
    use HasFactory;


protected $table = 'favorites';
    protected $fillable = [
        'user_id',
        'product_id',
    ];



    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
