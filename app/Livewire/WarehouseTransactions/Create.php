<?php

namespace App\Livewire\WarehouseTransactions;

use App\Models\Barcode;
use App\Models\Product;
use App\Models\SubSection;
use Livewire\Component;
use App\Models\Warehouse;
use App\Models\WarehouseTransactionType;
use Livewire\Attributes\Layout;
use App\Services\Dashboard\WarehouseTransactionsService;

class Create extends Component
{
    public $products = [];
    public $searchResults = [];
    public $selectedProducts = [];
    public $selectAll = false;
    public $quantities = [];
    public $units = [];
    public $sections = [];
    public $bar_code;
    public $warehouse_id;
    public $new_warehouse_visability = false;
    public $transaction_type_id;
    public $new_warehouse_id;

    // خصائص البحث
    public $searchQuery = '';
    public $sectionId = '';
    public $showSearchModal = false;

    // إضافة خاصية public لـ selectedCount
    public $selectedCount = 0;

    public function changeNewWarehouseVisibility()
    {
        if ($this->transaction_type_id == 4) {
            $this->new_warehouse_visability = true;
        } else {
            $this->new_warehouse_visability = false;
        }
    }

    public function mount()
    {
        $this->sections = SubSection::all();
        $this->initializeSearchResults();
        $this->selectedCount = 0;
    }

    // تهيئة نتائج البحث
    public function initializeSearchResults()
    {
        $query = Product::with(['units', 'section']);

        if ($this->searchQuery) {
            $query->where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
        }

        if ($this->sectionId) {
            $query->where('section_id', $this->sectionId);
        }



        $this->searchResults = $query->limit(50)->get();

        // تهيئة الكميات والوحدات
        foreach ($this->searchResults as $product) {
            $this->quantities[$product->id] = 1;
            if ($product->units->isNotEmpty()) {
                $this->units[$product->id] = $product->units->first()->id;
            }
        }
    }

    // البحث عند تحديث الاستعلام
    public function updatedSearchQuery()
    {
        $this->initializeSearchResults();
        $this->updateSelectedCount();
    }

    public function updatedSectionId()
    {
        $this->initializeSearchResults();
        $this->updateSelectedCount();
    }

    // فتح/إغلاق نافذة البحث
    public function toggleSearchModal()
    {
        $this->showSearchModal = !$this->showSearchModal;
        if ($this->showSearchModal) {
            $this->initializeSearchResults();
        }
    }

    // تحديد/إلغاء تحديد الكل
    public function toggleSelectAll()
    {
        $this->selectAll = !$this->selectAll;

        if ($this->selectAll) {
            // تحديد كل المنتجات
            $selected = [];
            foreach ($this->searchResults as $product) {
                $selected[$product->id] = true;
            }
            $this->selectedProducts = $selected;
        } else {
            // إلغاء تحديد كل المنتجات
            $this->selectedProducts = [];
        }

        $this->updateSelectedCount();

        // إرسال حدث مع البيانات المحدثة
        $this->dispatch('selectAllToggled', isChecked: $this->selectAll);
    }

    // تبديل تحديد منتج
    public function toggleProduct($productId)
    {
        if (isset($this->selectedProducts[$productId])) {

            unset($this->selectedProducts[$productId]);
        } else {
            $this->selectedProducts[$productId] = true;
        }

        // تحديث حالة تحديد الكل
        $this->selectAll = count($this->selectedProducts) === count($this->searchResults);
        $this->updateSelectedCount();
    }

    // تحديث عداد المنتجات المحددة
    public function updateSelectedCount()
    {
        $this->selectedCount = count($this->selectedProducts);
    }

    // تحديث الكمية
    public function updateQuantity($productId, $quantity)
    {
        $quantity = (int)$quantity;
        $this->quantities[$productId] = max(1, $quantity);
    }

    // زيادة الكمية
    public function incrementQuantity($productId)
    {
        $current = $this->quantities[$productId] ?? 1;
        $this->quantities[$productId] = $current + 1;
    }

    // تقليل الكمية
    public function decrementQuantity($productId)
    {
        $current = $this->quantities[$productId] ?? 1;
        if ($current > 1) {
            $this->quantities[$productId] = $current - 1;
        }
    }

    // تحديث الوحدة
    public function updateUnit($productId, $unitId)
    {
        $this->units[$productId] = $unitId;
    }

    // إضافة المنتجات المحددة من البحث
    public function addSelectedFromSearch()
    {
        $addedCount = 0;

        foreach ($this->selectedProducts as $productId => $selected) {
            if ($selected) {
                $product = collect($this->searchResults)->firstWhere('id', $productId);

                if ($product && !$this->productExistsInList($productId)) {
                    $unitId = $this->units[$productId] ?? null;
                    $unitName = $this->getUnitName($product, $unitId);
                    $quantity = $this->quantities[$productId] ?? 1;

                    // البحث عن المنتج في المستودع
                    $productWarehouse = $this->getProductWarehouseStock($productId);

                    $this->products[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'bar_code' => $this->getProductBarcode($productId, $unitId),
                        'unit' => $unitName,
                        'product_unit_id' => $unitId,
                        'current_stock' => $productWarehouse,
                        'quantity' => $quantity,
                    ];

                    $addedCount++;
                }
            }
        }

        // إعادة تعيين البحث
        $this->selectedProducts = [];
        $this->selectAll = false;
        $this->selectedCount = 0;
        $this->showSearchModal = false;
        $this->searchQuery = '';
        $this->sectionId = '';

        // إشعار بالنجاح
        if ($addedCount > 0) {
            session()->flash('success_message', 'تم إضافة ' . $addedCount . ' منتج/منتجات');
        }
    }

    // التحقق مما إذا كان المنتج موجوداً في القائمة
    private function productExistsInList($productId)
    {
        return collect($this->products)
            ->where('id', $productId)
            ->isNotEmpty();
    }

    // الحصول على اسم الوحدة
    private function getUnitName($product, $unitId)
    {
        if (!$unitId) {
            return $product->units->first()->name ?? 'N/A';
        }

        $unit = $product->units->firstWhere('id', $unitId);
        return $unit ? $unit->name : 'N/A';
    }

    // الحصول على باركود المنتج
    private function getProductBarcode($productId, $unitId)
    {
        $barcode = Barcode::whereHas('productUnit', function ($query) use ($productId, $unitId) {
            $query->where('product_id', $productId);
            if ($unitId) {
                $query->where('unit_id', $unitId);
            }
        })->first();

        return $barcode ? $barcode->code : 'N/A';
    }

    // الحصول على كمية المنتج في المستودع
    private function getProductWarehouseStock($productId)
    {
        if (!$this->warehouse_id) {
            return 0;
        }

        $product = Product::find($productId);
        if (!$product) {
            return 0;
        }

        $warehouseProduct = $product->warehouseProducts()
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        return $warehouseProduct ? $warehouseProduct->quantity : 0;
    }

    // البحث بالباركود
    public function searchByBarcode()
    {
        $this->validate([
            'bar_code' => 'required|string',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $barcode = Barcode::where('code', $this->bar_code)->first();
        if (!$barcode) {
            session()->flash('error', 'لم يتم إيجاد المنتج');
            return;
        }

        $product = $barcode->productUnit->product;

        if ($product) {
            // التحقق مما إذا كان المنتج موجوداً بالفعل
            $exists = collect($this->products)
                ->where('id', $product->id)
                ->where('unit', $barcode->productUnit->unit->name)
                ->first();

            if (!$exists) {
                $productWarehouse = $product->warehouseProducts()
                    ->where('warehouse_id', $this->warehouse_id)
                    ->first();

                $this->products[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'bar_code' => $barcode->code,
                    'unit' => $barcode->productUnit->unit->name,
                    'product_unit_id' => $barcode->productUnit->id,
                    'current_stock' => $productWarehouse ? $productWarehouse->quantity : 0,
                    'quantity' => 1,
                ];
            }

            $this->bar_code = '';
            $this->dispatch('product-added');
        } else {
            session()->flash('error', 'لم يتم إيجاد المنتج');
        }
    }

    public function removeProduct($index)
    {
        if (isset($this->products[$index])) {
            unset($this->products[$index]);
            $this->products = array_values($this->products);
        }
    }

    public function clearAllProducts()
    {
        $this->products = [];
    }

    public function updatedBarCode()
    {
        if (!empty($this->bar_code)) {
            $this->searchByBarcode();
        }
    }

    public function updatedTransactionTypeId()
    {
        if ($this->transaction_type_id != 4) {
            $this->new_warehouse_id = null;
        }
        $this->changeNewWarehouseVisibility();
    }

    public function save(WarehouseTransactionsService $warehouseTransactionsService)
    {
        try {
            $warehouseTransactionsService->create(
                $this->products,
                $this->warehouse_id,
                $this->transaction_type_id,
                $this->new_warehouse_id
            );

            return redirect()
                ->route('warehouses_trasactions.index')
                ->with('success', 'تم إنشاء العملية بنجاح');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'فشل حفظ العملية: ' . $e->getMessage());
        }
    }

    #[Layout('admin.livewireLayout')]
    public function render()
    {
        $transactions_types = WarehouseTransactionType::all();
        $warehouses = Warehouse::where('is_active', 1)->get();

        return view('livewire.warehouse-transactions.create', [
            'transactions_types' => $transactions_types,
            'warehouses' => $warehouses,
            'selectedCount' => $this->selectedCount, // إضافة المتغير للعرض
        ]);
    }
}
