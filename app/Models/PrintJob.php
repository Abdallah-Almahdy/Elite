<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    use HasFactory;

    protected $table = 'print_jobs';

    protected $fillable = [
        'order_id',
        'printer_id',
        'pdf_url',
        'status',
    ];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
