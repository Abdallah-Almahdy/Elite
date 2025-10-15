<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'totalPrice',
        'address',
        'phoneNumber',
        'status',
        'payment_method',
        'promo_code_id',
        'order_type',
        'temp_address'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function hasSuccessPayment()
    {
        return $this->payments()->where('status', 'paid')->exists();
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class,'address','id');

    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
            ->withPivot(['totalCount', 'totalPrice'])
            ->withTimestamps();
    }

    public function orderProducts(): HasMany
    {
        return $this->HasMany(OrderProduct::class);
    }


    public function orderTracking(): HasMany
    {
        return $this->HasMany(OrderTracking::class);
    }


    public function user_info(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
