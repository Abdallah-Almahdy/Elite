<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class warehousePermissions extends Model
{

    protected $table = 'warehouse_permissions';
    protected $fillable = [
        'user_id',
        'warehouse_name',
        'warehouse_id',
        'is_default',
    ];
}
