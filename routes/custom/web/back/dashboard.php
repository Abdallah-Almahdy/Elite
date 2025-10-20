<?php

use App\Http\Controllers\Api\ConfigController;
use App\Models\SubSection;

// Models
use App\Livewire\Orders\OrderDetails;

// Backend Controllers
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Back\UnitController;
use App\Http\Controllers\Back\WarehouseController;
use App\Livewire\Orders\Index as OrdersIndex;
use App\Http\Controllers\Back\OrdersController;
use App\Http\Controllers\Back\BanaresController;
use App\Http\Controllers\Back\RecipeController;
use App\Http\Controllers\Back\IngredientController;
use App\Http\Controllers\Back\RatesController;
use App\Http\Controllers\Back\ContactUsController;

// Backend Statics
use App\Http\Controllers\Back\CompanisController;
use App\Http\Controllers\Back\CustomerController;
use App\Http\Controllers\Back\KitchensController;
use App\Http\Controllers\Back\PrintersController;
use App\Http\Controllers\Back\ProductsController;
use App\Http\Controllers\Back\SectionsController;
use App\Http\Controllers\Back\AboutUsController;
// Other Controllers
use App\Http\Controllers\Back\SupplierController;

// Livewire
use App\Livewire\Delivery\Index as DeliveryIndex;
use App\Http\Controllers\Back\PromocodesController;
use App\Http\Controllers\Back\PermissionsController;
use App\Http\Controllers\Back\RepresentativeController;
use App\Http\Controllers\Back\tools\NotificationsController;
use App\Http\Controllers\Back\Statics\UsersStaticsController;
use App\Http\Controllers\Back\Statics\StaticsOrdersController;
use App\Livewire\Statices\StaticesController as StaticesIndex;
use App\Http\Controllers\Back\Statics\RatingsStaticsController;
use App\Http\Controllers\Back\Statics\SectionsStaticsController;
use App\Http\Controllers\Back\WarehouseTransactionsController;
use App\Livewire\WarehouseTransactions\Create as WarehouseTransactionsCreate;
use App\Models\Config;

// use App\Livewire\Recipe\Create as CreateRecipe;
// use App\Livewire\Recipe\Index as IndexRecipe;
// use App\Livewire\Recipe\Edit as EditRecipe;
// use App\Livewire\Recipe\Show as ShowRecipe;
//use App\Models\AboutUS;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('dashboard')->group(function () {

    Route::get('home', function () {
        $data = SubSection::first();
        return view('/admin/dashboardHome', ['data' => $data]);
    })->name('dashboard');

    // CRUD
    Route::get('delivery', DeliveryIndex::class)->name('delivery.index');
    Route::resource('sections', SectionsController::class);
    Route::resource('companies', CompanisController::class);
    Route::resource('products', ProductsController::class);
    Route::resource('Permissions', PermissionsController::class);
    Route::resource('banares', BanaresController::class);
    Route::resource('notifications', NotificationsController::class);
    Route::resource('promocodes', PromocodesController::class);
    Route::resource('kitchens', KitchensController::class);
    Route::resource('printers', PrintersController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('representatives', RepresentativeController::class);
    Route::resource('units', UnitController::class);
    Route::resource('recipes', RecipeController::class);
    Route::resource('about', AboutUsController::class);
    Route::resource('ingredients', IngredientController::class);
    Route::resource('recipes', RecipeController::class)->except(['show']);
    Route::get('configs',[ConfigController::class,'update'])->name('config.update');
    Route::post('configs/edit',[ConfigController::class,'edit'])->name('admin.configs.edit');




    // Orders
    Route::get('orders', OrdersIndex::class)->name('orders.index');
    Route::get('orderDetails/{id}', OrderDetails::class)->name('orders.details');
    Route::get('orderDetailsPrint/{id}', [OrdersController::class, 'print'])->name('orders.print');

    // warehouses
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('warehouses_trasactions', WarehouseTransactionsController::class);
    Route::get('warehouse_transactions/new', WarehouseTransactionsCreate::class)->name('warehouseTransactions.livewire.create');

    // Statics
    Route::prefix('statices')->group(function () {
        Route::get('/', StaticesIndex::class)->name('statices.index');

        Route::prefix('users')->group(function () {
            Route::get('/', [UsersStaticsController::class, 'users'])->name('statices.users');
            Route::get('show/{id}', [UsersStaticsController::class, 'userInfo'])->name('users.show');
            Route::get('edit/{id}', [UsersStaticsController::class, 'editUserInfo'])->name('users.edit');
            Route::post('update/{id}', [UsersStaticsController::class, 'updateUserInfo'])->name('users.update');
            Route::delete('delete/{id}', [UsersStaticsController::class, 'deleteUser'])->name('users.delete');
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', [StaticsOrdersController::class, 'orders'])->name('statices.orders');
            Route::get('show/{id}', [StaticsOrdersController::class, 'orderInfo'])->name('orders.show');
            Route::get('successed', [StaticsOrdersController::class, 'successOrders'])->name('orders.successed');
            Route::get('faild', [StaticsOrdersController::class, 'faildOrders'])->name('orders.faild');
        });

        Route::prefix('sections')->group(function () {
            Route::get('mainsections', [SectionsStaticsController::class, 'mainSections'])->name('sections.main');
            Route::get('subsections', [SectionsStaticsController::class, 'subSections'])->name('sections.sub');
            Route::get('mainsection/{id}/subsections', [SectionsStaticsController::class, 'subSection'])->name('sectionsInfo');
        });

        Route::prefix('ratings')->group(function () {
            Route::get('/', [RatingsStaticsController::class, 'index'])->name('statics.ratings');
        });
    });


    // Route::get('recipes', IndexRecipe::class)->name('livewire.recipes.index');
    // Route::get('recipes/create', CreateRecipe::class)->name('livewire.recipes.create');
    // Route::get('recipes/{id}/edit', EditRecipe::class)->name('livewire.recipes.edit');
    // Route::get('recipes/{id}/show', ShowRecipe::class)->name('livewire.recipes.show');


    //AboutUs
    Route::delete('about/image/{id}', [AboutUsController::class, 'deleteImage'])->name('about.deleteImage');
    //rates
    Route::get('rates', [RatesController::class, 'index'])->name('rates.index');
    //ContactUs
    Route::get('contactUs', [ContactUsController::class, 'index'])->name('contactus.index');



});
