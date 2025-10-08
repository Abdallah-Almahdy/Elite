<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'cashier_id',
        'start_cash',
        'end_cash',
        'start_time',
        'end_time',
        'safe_id'
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
