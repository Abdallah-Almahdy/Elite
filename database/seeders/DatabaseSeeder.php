<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [
            'product.create',
            'product.delete',
            'product.edit',
            'section.create',
            'section.delete',
            'section.edit',
            'showOrdersSidebar',
            'showPermessionsSidebar',
            'showProductsSidebar',
            'showQntOption',
            'showSectionsSidebar',
            'user.create',
            'config.update',
            'showStatics',
            'showDelevary',
            'showAdds',
            'showNotifications',
            'showPromoCode',
            'showKitchen',
            'showPrinters',
            'showStock',
            'showCashier',
            'showRecips',
            'showUnits',
            'showGredients',
            'showSuppliers',
            'showClients',
            'showAboutUs',
            'showReviews',
            'showComplaints'



        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => trim($permission), // ← للتأكد لو في مسافات زيادة
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('✅ Permissions seeded successfully!');
    }
}
