<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductUnits;
use App\Models\Barcode;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SimpleProductsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
        ini_set('max_execution_time', 300);
        DB::beginTransaction();

        try {

            // 1️⃣ تحقق لو المنتج موجود بالاسم
            $product = Product::firstOrCreate(
                ['name' => $row['name']], // شرط البحث
                [
                    'description' => 'منتج',
                    'section_id' => 3,
                    'is_stock' => 1,
                    'is_weight' => 0,
                    'active' => 1,
                    'offer_rate' => 0,
                    'qtn' => 0,
                ]
            );

            // 2️⃣ تحقق لو Unit موجود للمنتج
            $unit = ProductUnits::firstOrNew(
                ['product_id' => $product->id, 'unit_id' => 22]
            );

            // حدث البيانات أو عينها لأول مرة
            $unit->conversion_factor = 1;
            $unit->price = $row['price'];
            $unit->sallprice = $row['sall_price'];
            $unit->is_base = true;
            $unit->code = ltrim($row['barcode'], "'") ?? null;
            $unit->save();

            // 3️⃣ تحقق لو Barcode موجود
            if (!empty($row['barcode'])) {
                $barcode = Barcode::firstOrNew([
                    'product_unit_id' => $unit->id,
                    'code' => ltrim($row['barcode'], "'")
                ]);

                // لو موجود حدث الكود (عادة الكود نفسه هو المفتاح، لكن لو حابب تحدث خصائص اخرى)
                $barcode->code = ltrim($row['barcode'], "'");
                $barcode->save();
            }

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }
    public function chunkSize(): int
    {
        return 200;
    }
}
