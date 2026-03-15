<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\SubSection;
use Illuminate\Support\Facades\DB;

class SalesDuringPerid extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    // الفلاتر
    public $fromDate = '';
    public $toDate = '';
    public $warehouse_id = null;
    public $section_id = '';

    // خصائص مودال تفاصيل المنتج
    public $selectedProduct = null;
    public $showProductModal = false;

    protected $queryString = [
        'fromDate' => ['except' => ''],
        'toDate' => ['except' => ''],
        'warehouse_id' => ['except' => ''],
        'section_id' => ['except' => ''],
    ];

    public function mount()
    {
        // تعيين تاريخ اليوم كقيمة افتراضية للـ toDate (اختياري)
        if (!$this->toDate) {
            $this->toDate = now()->format('Y-m-d');
        }
        if (!$this->fromDate) {
            // افتراضيًا آخر 30 يومًا
            $this->fromDate = now()->subDays(30)->format('Y-m-d');
        }
    }

    public function updatedFromDate()
    {
        $this->resetPage();
    }

    public function updatedToDate()
    {
        $this->resetPage();
    }

    public function updatedWarehouseId()
    {
        $this->resetPage();
    }

    public function updatedSectionId()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['fromDate', 'toDate', 'warehouse_id', 'section_id']);
        $this->mount(); // لإعادة تعيين التواريخ الافتراضية
        $this->resetPage();
    }

    /**
     * عرض تفاصيل المنتج في مودال
     */
    public function showProductDetails($productId)
    {
        $this->selectedProduct = Product::with([
            'productUnits.unit',
            'productUnits.barcodes',
            'warehouseProducts.warehouse'
        ])->findOrFail($productId);
        $this->showProductModal = true;
    }

    public function closeProductModal()
    {
        $this->showProductModal = false;
        $this->selectedProduct = null;
    }

    public function render()
    {
        // جلب جميع المخازن النشطة للفلتر
        $warehouses = Warehouse::where('is_active', true)->get();
        $sections = SubSection::all(); // جميع الأقسام

        // استعلام المنتجات مع الحسابات
        $query = Product::query()
            ->with([
                'productUnits' => function ($q) {
                    $q->with('unit')
                        ->where(function ($q) {
                            $q->where('is_base', true)
                                ->orWhere('conversion_factor', 1);
                        })
                        ->limit(1);
                },
                'productUnits.barcodes',
            ])
            ->addSelect([
                // إجمالي الكمية المباعة (بالوحدة الأساسية)
                'total_sold_quantity' => DB::table('invoice_products')
                    ->join('invoices', 'invoices.id', '=', 'invoice_products.invoice_id')
                    ->join('shifts', 'shifts.id', '=', 'invoices.shift_id')
                    ->join('safes', 'safes.id', '=', 'shifts.safe_id')
                    ->whereColumn('invoice_products.product_id', 'products.id')
                    ->when($this->fromDate && $this->toDate, function ($q) {
                        $q->whereBetween('invoices.created_at', [
                            $this->fromDate . ' 00:00:00',
                            $this->toDate . ' 23:59:59'
                        ]);
                    })
                    ->selectRaw('COALESCE(SUM(invoice_products.quantity * invoice_products.unit_conversion_factor), 0)'),

                // إجمالي قيمة المبيعات
                'total_sold_value' => DB::table('invoice_products')
                    ->join('invoices', 'invoices.id', '=', 'invoice_products.invoice_id')
                    ->join('shifts', 'shifts.id', '=', 'invoices.shift_id')
                    ->join('safes', 'safes.id', '=', 'shifts.safe_id')
                    ->whereColumn('invoice_products.product_id', 'products.id')

                    ->when($this->fromDate && $this->toDate, function ($q) {
                        $q->whereBetween('invoices.created_at', [
                            $this->fromDate . ' 00:00:00',
                            $this->toDate . ' 23:59:59'
                        ]);
                    })
                    ->selectRaw('COALESCE(SUM(invoice_products.subtotal), 0)'),

                // إجمالي الكمية المرتجعة (بالوحدة الأساسية)
                'total_returned_quantity' => DB::table('invoice_return_items')
                    ->join('invoice_returns', 'invoice_returns.id', '=', 'invoice_return_items.invoice_return_id')
                    ->join('invoices', 'invoices.id', '=', 'invoice_returns.invoice_id')
                    ->join('shifts', 'shifts.id', '=', 'invoices.shift_id')
                    ->join('safes', 'safes.id', '=', 'shifts.safe_id')
                    ->join('invoice_products', 'invoice_products.id', '=', 'invoice_return_items.invoice_products_id')
                    ->whereColumn('invoice_products.product_id', 'products.id')

                    ->when($this->fromDate && $this->toDate, function ($q) {
                        $q->whereBetween('invoice_returns.created_at', [
                            $this->fromDate . ' 00:00:00',
                            $this->toDate . ' 23:59:59'
                        ]);
                    })
                    ->selectRaw('COALESCE(SUM(invoice_return_items.quantity * invoice_products.unit_conversion_factor), 0)'),

                // إجمالي قيمة المرتجعات
                'total_returned_value' => DB::table('invoice_return_items')
                    ->join('invoice_returns', 'invoice_returns.id', '=', 'invoice_return_items.invoice_return_id')
                    ->join('invoices', 'invoices.id', '=', 'invoice_returns.invoice_id')
                    ->join('shifts', 'shifts.id', '=', 'invoices.shift_id')
                    ->join('safes', 'safes.id', '=', 'shifts.safe_id')
                    ->whereColumn('invoice_return_items.invoice_products_id', 'invoice_products.id') // ربط مع invoice_products
                    ->join('invoice_products', 'invoice_products.id', '=', 'invoice_return_items.invoice_products_id')
                    ->whereColumn('invoice_products.product_id', 'products.id')

                    ->when($this->fromDate && $this->toDate, function ($q) {
                        $q->whereBetween('invoice_returns.created_at', [
                            $this->fromDate . ' 00:00:00',
                            $this->toDate . ' 23:59:59'
                        ]);
                    })
                    ->selectRaw('COALESCE(SUM(invoice_return_items.total), 0)'),
            ]);

        // فلتر القسم
        if ($this->section_id) {
            $query->where('section_id', $this->section_id);
        }

        // تنفيذ الاستعلام مع التصفح
        $products = $query->paginate(20);

        // تحويل النتائج إلى تنسيق مناسب للجدول
        $products->getCollection()->transform(function ($product) {
            $productUnit = $product->productUnits->first();
            $barcode = $productUnit?->barcodes->first()?->code ?? '--';
            $unitName = $productUnit?->unit?->name ?? '--';

            $soldQty = (float) $product->total_sold_quantity;
            $returnedQty = (float) $product->total_returned_quantity;
            $netQty = $soldQty - $returnedQty;

            $soldValue = (float) $product->total_sold_value;
            $returnedValue = (float) $product->total_returned_value;
            $netValue = $soldValue - $returnedValue;

            return (object) [
                'id'                => $product->id,
                'barcode'           => $barcode,
                'name'              => $product->name,
                'unit'              => $unitName,
                'sold_quantity'     => $soldQty,
                'returned_quantity' => $returnedQty,
                'net_quantity'      => $netQty,
                'net_value'         => $netValue,
            ];
        });

        return view('livewire.report.sales-during-perid', [
            'products'   => $products,
            'warehouses' => $warehouses,
            'sections'   => $sections,
        ]);
    }
}
