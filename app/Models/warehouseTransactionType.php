<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseTransactionType extends Model
{
    use HasFactory;

    const STOCKTAKE = 1;
    const STOCK_ISSUE = 2;
    const STOCK_RECEIPT = 3;
    const STOCK_TRANSFER = 4;




    protected $table = 'warehouse_transactions_types';

    protected $fillable = [
        'name',
    ];


    public static function getTypesArray(): array
    {
        return [
            self::STOCKTAKE => 'جرد',
            self::STOCK_ISSUE => 'صرف',
            self::STOCK_RECEIPT => 'إضافة',
            self::STOCK_TRANSFER => 'نقل'
        ];
    }

    // 🔥 GET ARABIC NAME
    public static function getArabicName(int $typeId): string
    {
        return match ($typeId) {
            self::STOCKTAKE => 'جرد',
            self::STOCK_ISSUE => 'صرف',
            self::STOCK_RECEIPT => 'إضافة',
            self::STOCK_TRANSFER => 'نقل',
            default => 'غير معروف'
        };
    }

    // 🔥 VALIDATE TYPE
    public static function isValidType(int $typeId): bool
    {
        return array_key_exists($typeId, self::getTypesArray());
    }
}
