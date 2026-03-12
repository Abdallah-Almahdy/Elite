<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Barcode;
use App\Models\ProductUnit;
use App\Models\SubSection;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination; // ✅ إضافة التريت

class Balance extends Component
{
    use WithPagination;
    // الفلاتر
    public $warehouse_id = null;
    public $barcode = '';
    public $search = '';
    public $selectedProduct = null;
    public $showProductModal = false;
    public $section = null;



    public function showProductDetails($productId)
    {

        $this->selectedProduct = Product::with([
            'productUnits.unit',
            'warehouseProducts.warehouse' // تحميل المخازن المرتبطة
        ])->findOrFail($productId);

        $this->showProductModal = true;
    }

    public function closeProductModal()
    {
        $this->showProductModal = false;
        $this->selectedProduct = null;
    }
    public function mount()
    {
        // تعيين المخزن الافتراضي إذا لم يتم اختياره
        if (!$this->warehouse_id) {
            $defaultWarehouse = Warehouse::where('is_default', true)->first();
            $this->warehouse_id = $defaultWarehouse?->id;
        }
    }

    public function updatedBarcode()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedWarehouseId()
    {
        $this->resetPage();
    }

    public function render()
    {
        // استعلام المنتجات
        $query = Product::query()
            ->with([
                // تحميل الوحدات (product_units) مع الوحدة الأساسية
                'productUnits' => function ($q) {
                    $q->with('unit') // تحميل الوحدة المرتبطة
                        ->where(function ($q) {
                            $q->where('is_base', true)
                                ->orWhere('conversion_factor', 1);
                        })
                        ->limit(1); // نحتاج فقط الوحدة الأساسية
                },
                'productUnits.barcodes', // تحميل الباركود المرتبط بالوحدة
                'warehouseProducts' => function ($q) {
                    if ($this->warehouse_id) {
                        $q->where('warehouse_id', $this->warehouse_id);
                    }
                }
            ])
            // حساب الرصيد في المخزن المحدد
            ->addSelect([
                'stock_in_warehouse' => DB::table('warehouse_products')
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('product_id', 'products.id')
                    ->when($this->warehouse_id, fn($q) => $q->where('warehouse_id', $this->warehouse_id))
            ]);

        // فلتر الباركود: البحث في جدول barcodes عبر product_units
        if ($this->barcode) {
            $query->whereHas('productUnits.barcodes', fn($q) => $q->where('code', 'like', '%' . $this->barcode . '%'));
        }

        // فلتر البحث بالاسم
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if($this->section)
        {
            $query->where('section_id', $this->section);
        }

        $products = $query->paginate(20);

        // تحويل النتائج إلى تنسيق مناسب للجدول
        $items = $products->getCollection()->transform(function ($product) {
            // الحصول على الوحدة الأساسية (productUnit)
            $productUnit = $product->productUnits->first();

            // الحصول على الباركود (أول باركود للوحدة الأساسية، أو أي باركود للمنتج)
            $barcode = $productUnit?->barcodes->first()?->code ?? '--';

            // اسم الوحدة الأساسية
            $unitName = $productUnit?->unit?->name ?? '--';

            // الرصيد في المخزن المحدد
            $stock = (float) $product->stock_in_warehouse;

            // التكلفة وسعر البيع (من productUnit، أو من product نفسه إذا لم يوجد)
            $cost = $productUnit?->price ?? $product->cost ?? 0;
            $price = $productUnit?->sallprice ?? $product->price ?? 0;

            return (object) [
                'id'          => $product->id,
                'barcode'     => $barcode,
                'name'        => $product->name,
                'unit'        => $unitName,
                'stock'       => $stock,
                'cost'        => $cost,
                'price'       => $price,
                'total_cost'  => $stock * $cost,
                'total_price' => $stock * $price,
            ];
        });
        $products->setCollection($items);
        $warehouses = Warehouse::where('is_active', true)->get();
        $sections = SubSection::where('active', true)->get();

        return view('livewire.products.balance', [
            'products'   => $products,
            'warehouses' => $warehouses,
            'sections'   => $sections,

        ]);
    }
}
