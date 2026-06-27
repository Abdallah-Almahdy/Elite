<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvicePrinterSettings extends Model
{
    protected $table = 'invice_printer_settings';

    protected $fillable = [
        'cashierPrinterName',
        'allowSaveWithoutPrint',
        'barcodePrinterName',
        'reportPrinterName',
        'user_id',
        'type'
    ];

    public function invicePrinters()
    {
        return $this->hasMany(invicePrinter::class, 'invoice_printer_setting_id');
    }

}
