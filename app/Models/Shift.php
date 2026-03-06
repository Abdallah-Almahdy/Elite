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
        'safe_id',
        'status'
    ];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'shift_id');
    }
}
