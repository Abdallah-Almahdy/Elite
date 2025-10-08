<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseTransaction extends Model
{
    use HasFactory;

    protected $table = 'warehouse_transactions';

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'type_id',
        'quantity',
        'user_id',
        'notes',
    ];

    public function type()
    {
        return $this->belongsTo(WarehouseTransactionType::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function target_warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function product()
    {
        return $this->belongsTo(product::class);
    }
}
