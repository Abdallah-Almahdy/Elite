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
        DB::beginTransaction();

        try {

            // 1️⃣ Create Product
            $product = Product::create([
                'name' => $row['name'],
                'description' => 'منتج',
                'section_id' => 3,
                'is_stock' => 1,
                'is_weight' => 0,
                'active' => 1,
                'offer_rate' => 0,
                'qtn' => 0,
            ]);

            // 2️⃣ Create Unit
            $unit = ProductUnits::create([
                'product_id' => $product->id,
                'unit_id' => 22,
                'conversion_factor' => 1,
                'price' => $row['price'],
                'sallprice' => $row['sall_price'],
                'is_base' => true,
                'code' => ltrim($row['barcode'], "'") ?? null
            ]);


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
