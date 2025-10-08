<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::create([
            'exact_blocked_version' => '5.2.7',
            'min_supported_version' => '5.3.1',
            'maintenance_mode'      => false,
            'maintenance_message'   => 'التطبيق تحت الصيانة حالياً، الرجاء المحاولة لاحقًا.',
            'blocked_versions'      => ['5.2.7'],
        ]);
    }
}
