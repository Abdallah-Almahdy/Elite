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

            // شاشه البيع
            'showProductsSidebar',
            'showGenralProducts',
            'product.create',
            'product.delete',
            'product.edit',

            // الاقسام
            'showSectionsSidebar',
            'section.create',
            'section.delete',
            'section.edit',

            //الطلبيات

            'showOrdersSidebar',
            'order.show',
            'order.cancel',
            'order.prepare',
            'order.shipment',
            'order.finish',

            //التقارير
            'reports.show',

            // شاشه  البيع
            'pos.show',
            'pos.priceChangeAuth',
            'pos.changeDiscount',
            'pos.deleteProdWithPass ',
            'pos.InvoiceTypeChangeAuth',
            'pos.paymentMethodChangeAuth',
            'pos.saveNoPrintAuth',
            'pos.editDate',
            'pos.chooseClient',
            'pos.InviceFreeze',
            'pos.InviceCall',
            'pos.priceChange',
            'pos.changeTax',
            'pos.InviceCancel',
            'pos.shiiftClose',

            // شاشه المستخدمين
            'user.create',


            //شاشه الدليفري
            'showDelevary',
            'delevary.addArea',
            'delevary.freeDelevary',
            'delivery.edit',
            'delivery.delete',


            //شاشه الاعلانات
            'showAds',


            //شاشه الاشعارات

            'showNotifications',


            //شاشه البروموكود

            'showPromoCodes',

            //شاشه المخازن

            'warehouse.show',
            'warehouse.edit',
            'warehouse.delete',

            'showUnits',
            'showSuppliers',
            'showCustomers',
            'showAboutUs',

            'showClientsVote',
            'showCustomersMessages',
            'config.update',
            'showKitchen',
            'showPrinters',


            // شاشه الإحصائيات

            'showStatistics',
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
