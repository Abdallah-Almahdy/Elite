<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseProductTransaction extends Model
{
    use HasFactory;

    protected $table = 'warehouse_products_transactions';

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'target_warehouse_id',
        'quantity',
        'type_id',
        'user_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function type()
    {
        return $this->belongsTo(WarehouseTransactionType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function target_warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
