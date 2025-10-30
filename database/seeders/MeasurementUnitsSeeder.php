<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementUnitsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('measurement_units')->truncate();

        
        DB::table('measurement_units')->insert([
            ['id' => 1, 'name' => 'جرام', 'base_measurement_unit_id' => null, 'conversion_factor' => 1, 'is_base_unit' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'مللي لتر', 'base_measurement_unit_id' => null, 'conversion_factor' => 1, 'is_base_unit' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'سنتيمتر', 'base_measurement_unit_id' => null, 'conversion_factor' => 1, 'is_base_unit' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'قطعة', 'base_measurement_unit_id' => null, 'conversion_factor' => 1, 'is_base_unit' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'سنتيمتر مربع', 'base_measurement_unit_id' => null, 'conversion_factor' => 1, 'is_base_unit' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2️⃣ المرحلة الثانية — إدخال الوحدات الأكبر اللي تعتمد على الأصغر
        DB::table('measurement_units')->insert([
            // الوزن
            ['name' => 'كيلوجرام', 'base_measurement_unit_id' => 1, 'conversion_factor' => 1000, 'is_base_unit' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'طن', 'base_measurement_unit_id' => 1, 'conversion_factor' => 1000000, 'is_base_unit' => false, 'created_at' => now(), 'updated_at' => now()],

            // الحجم
            ['name' => 'لتر', 'base_measurement_unit_id' => 2, 'conversion_factor' => 1000, 'is_base_unit' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'جالون', 'base_measurement_unit_id' => 2, 'conversion_factor' => 3785, 'is_base_unit' => false, 'created_at' => now(), 'updated_at' => now()],

            // الطول
            ['name' => 'متر', 'base_measurement_unit_id' => 3, 'conversion_factor' => 100, 'is_base_unit' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'كيلومتر', 'base_measurement_unit_id' => 3, 'conversion_factor' => 100000, 'is_base_unit' => false, 'created_at' => now(), 'updated_at' => now()],

            // العدد
            ['name' => 'صندوق', 'base_measurement_unit_id' => 4, 'conversion_factor' => 24, 'is_base_unit' => false, 'created_at' => now(), 'updated_at' => now()],

            // المساحة
            ['name' => 'متر مربع', 'base_measurement_unit_id' => 5, 'conversion_factor' => 10000, 'is_base_unit' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'كيلومتر مربع', 'base_measurement_unit_id' => 5, 'conversion_factor' => 10000000000, 'is_base_unit' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);


    }
}
