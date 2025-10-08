<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'cashier_id',
        'total',
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

    public function products()
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
