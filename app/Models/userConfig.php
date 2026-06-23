<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userConfig extends Model
{
    protected $table = 'user_configs';
    protected $fillable = [
        'user_id',
        'CashierPrinterName',
        'AllowSaveWithoutPrint',
        'barcodePrinterName',
        'reportPrinterName',
    ];
}
