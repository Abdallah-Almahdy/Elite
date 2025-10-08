<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drawer extends Model
{
    protected $fillable = [
        'cashier_id',
        'safe_id',
        'cash_amount',
        'status',
        'opened_at',
        'closed_at'
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }
}
