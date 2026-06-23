<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvicePrinterSettings extends Model
{
    protected $table = 'invice_printer_settings';

    protected $fillable = [
        'printerName',
        'formName',
        'permssionName',
        'numOfCopies',
    ];
}
