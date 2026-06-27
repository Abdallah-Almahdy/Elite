<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invicePrinter extends Model
{
    protected $table = 'invice_printers';

    protected $fillable = [
        'invoice_printer_setting_id',
        'formName',
        'printerName',
        'permssionName',
        'numOfCopies',
        'isActive',
    ];

    public function invicePrinterSetting()
    {
        return $this->belongsTo(InvicePrinterSettings::class, 'invoice_printer_setting_id');
    }
}
