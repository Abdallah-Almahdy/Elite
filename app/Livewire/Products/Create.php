<?php

namespace App\Livewire\Products;

use App\Models\Barcode;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Company;
use App\Models\SubSection;
use App\Models\Product;
use App\Models\ProductUnits;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    use WithFileUploads;

    public $name;
    public $photo;
    public $section;
    public $description;
    public $hasRecipe = false;
    public $isActive = true;
    public $company;

    public $enableStock = false;
    public $stockQnt = 0;



    public $units = [];
    public $baseUnit = 0;
    public $sallPrice;
    public $price;
    public $bar_codes;

    public $companies = [];
    public $options = [];

    public $allProducts = []; // لاختيار المكونات
    public $MeasureUnits = [];
    public $showRecipes = [];
    public $addons = [];

    protected bool $updatingPrices = false;
    public $newUnit = [
        'name' => '',
        'is_active' => true,
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'section' => 'required',
        'photo' => 'nullable|image|max:1024',
        'hasRecipe' => 'boolean',
        'stockQnt' => 'nullable|integer|min:0',
        'isActive' => 'boolean',

        // Units
        'units.*.measure_unit_id' => 'required|exists:units,id',
        'units.*.price' => 'required|numeric|min:0',
        'units.*.sallPrice' => 'required|numeric|min:0',
        'units.*.conversion_factor' => 'required|numeric|min:0.01',
        'units.*.bar_codes.*' => 'nullable|string|max:255|unique:barcodes,code|distinct',

        // Components داخل الوحدة
        'units.*.components.*.product_id' => 'required|exists:products,id',
        'units.*.components.*.component_unit_id' => 'required|exists:units,id',
        'units.*.components.*.quantity' => 'required|numeric|min:0.01',

        // Options
        'options.*.name' => 'required|string|max:255',
        'options.*.active' => 'boolean',
        'options.*.values.*.name' => 'required|string|max:255',
        'options.*.values.*.price' => 'nullable|numeric|min:0',

        // Addons
        'addons.*.name' => 'required|string|max:255',
        'addons.*.price' => 'required|numeric|min:0',
        'addons.*.active' => 'boolean',
    ];


    protected $validationAttributes = [
        'name' => 'اسم المنتج',
        'price' => 'سعر المنتج',
        'section' => 'القسم',
        'photo' => 'صورة المنتج',
        'hasRecipe' => 'استخدام وصفة',
        'stockQnt' => 'الكمية في المخزن',
        'isActive' => 'حالة المنتج',

        // Units
        'units.*.measure_unit_id' => 'الوحدة',
        'units.*.price' => 'سعر الشراء',
        'units.*.sallPrice' => 'سعر البيع',
        'units.*.conversion_factor' => 'معامل التحويل',

        // Components
        'units.*.components.*.product_id' => 'المنتج المكوّن',
        'units.*.components.*.component_unit_id' => 'وحدة المكوّن',
        'units.*.components.*.quantity' => 'كمية المكوّن',
        'units.*.bar_codes.*' => 'الباركود',
        // Options
        'options.*.name' => 'اسم الخيار',
        'options.*.values.*.name' => 'اسم قيمة الخيار',
        'options.*.values.*.price' => 'سعر قيمة الخيار',

        // Addons
        'addons.*.name' => 'اسم الإضافة',
        'addons.*.price' => 'سعر الإضافة',
        'addons.*.active' => 'حالة الإضافة',
    ];


    public function toggleRecipeVisibility($index)
    {
        $this->showRecipes[$index] = !($this->showRecipes[$index] ?? false);
    }



    public function updated($propertyName, $value)
    {


        // product search updated
        if (str_contains($propertyName, 'components') && str_ends_with($propertyName, 'search')) {
            $this->handleProductSearch($propertyName);
        }

        // units price/conversion changed -> recalc dependent prices
        if (
            str_contains($propertyName, 'units') &&
            (str_ends_with($propertyName, 'price') || str_ends_with($propertyName, 'sallPrice') || str_ends_with($propertyName, 'conversion_factor'))
        ) {

            // استخراج index والوحدة المعدلة من اسم الخاصية
            preg_match('/units\.(\d+)\.(price|sallPrice|conversion_factor)/', $propertyName, $matches);
            $changedIndex = isset($matches[1]) ? (int) $matches[1] : null;
            $changedField = $matches[2] ?? null;

            $this->updateDependentPrices($changedIndex, $changedField);
        }

        // نتأكد أن اللي اتغير quantity لأي component
        if (preg_match('/units\.(\d+)\.components\.(\d+)\.(quantity|component_unit_id)/', $propertyName, $matches)) {
            $unitIndex = $matches[1];
            $componentIndex = $matches[2];

            $component = $this->units[$unitIndex]['components'][$componentIndex] ?? null;

            // نتحقق من وجود المنتج والوحدة والكمية
            if (
                $component
                && !empty($component['product_id'])
                && !empty($component['component_unit_id'])
                && !empty($component['quantity'])
            ) {
                $this->calculateTotalCost($unitIndex);
            }
        }
    }

    public function calculateTotalCost($unitIndex)
    {
        $unit = &$this->units[$unitIndex];
        $total = 0;

        if (!empty($unit['components'])) {
            foreach ($unit['components'] as $component) {

                if (
                    !empty($component['product_id'])
                    && !empty($component['component_unit_id'])
                    && !empty($component['quantity'])
                ) {
                    // جلب سعر الوحدة من قاعدة البيانات
                    $productUnit = ProductUnits::where('product_id', $component['product_id'])
                        ->where('unit_id', $component['component_unit_id'])
                        ->first();



                    if ($productUnit) {
                        $total += $productUnit->price * $component['quantity'] * $productUnit->conversion_factor;
                    }
                }
            }
        }

        $unit['total_cost'] = $total;
    }


    public function updateDependentPrices($changedIndex = null, $changedField = null)
    {
        if ($this->updatingPrices) return;
        $this->updatingPrices = true;

        try {
            if (empty($this->units) || !isset($this->units[0])) return;

            $count = count($this->units);
            $getFloat = fn($v) => (float) ($v ?? 0);

            // --------- 1) تحديث سعر البيع (sallPrice) ---------
            if (($changedIndex === 0 && $changedField === 'sallPrice') || $changedField === 'conversion_factor') {
                for ($i = 1; $i < $count; $i++) {
                    $prevSell = $getFloat($this->units[$i - 1]['sallPrice']);
                    $factor = $getFloat($this->units[$i]['conversion_factor']);
                    // السماح بعوامل <1 ولكن منع الصفر
                    if ($factor <= 0) $factor = 1;
                    $this->units[$i]['sallPrice'] = round($prevSell * $factor, 2);
                }
            }
            if ($changedIndex === 0 && $changedField === 'price') {
                for ($i = 1; $i < $count; $i++) {
                    $prevPrice = $getFloat($this->units[$i - 1]['price']);
                    $factor = $getFloat($this->units[$i]['conversion_factor']);
                    if ($factor <= 0) $factor = 1;
                    $this->units[$i]['price'] = round($prevPrice * $factor, 2);
                }
            }

            // --------- 3) تحديث الوحدة عند تغيير conversion_factor ---------
            if ($changedField === 'conversion_factor' && $changedIndex !== null && $changedIndex > 0) {
                $prevPrice = $getFloat($this->units[$changedIndex - 1]['price']);
                $factor = $getFloat($this->units[$changedIndex]['conversion_factor']);
                if ($factor <= 0) $factor = 1;
                $this->units[$changedIndex]['price'] = round($prevPrice * $factor, 2);
            }

            // --------- 4) إعادة ترتيب المصفوفة لالتقاط Livewire للتغيير -----
            $this->units = array_values($this->units);

            // --------- 5) إرسال الحدث لتحديث الواجهة (JS) -----
            $this->dispatch('prices-updated', units: $this->units);
        } finally {
            $this->updatingPrices = false;
        }
    }



    public function handleProductSearch($propertyName)
    {
        preg_match('/units\.(\d+)\.components\.(\d+)\.search/', $propertyName, $matches);
        if (!$matches) return;

        [$full, $unitIndex, $componentIndex] = $matches;
        $query = $this->units[$unitIndex]['components'][$componentIndex]['search'] ?? '';

        if (strlen($query) < 2) {
            $this->units[$unitIndex]['components'][$componentIndex]['results'] = [];
            return;
        }

        $results = Product::where('name', 'like', "%{$query}%")
            ->take(10)
            ->get(['id', 'name'])
            ->toArray();

        $this->units[$unitIndex]['components'][$componentIndex]['results'] = $results;
    }

    public function selectProduct($unitIndex, $componentIndex, $productId)
    {
        $product = Product::with('units')->find($productId); // جيب الـ units معاه
        if (!$product) return;

        // حفظ بيانات المنتج
        $this->units[$unitIndex]['components'][$componentIndex]['product_id'] = $product->id;
        $this->units[$unitIndex]['components'][$componentIndex]['product_name'] = $product->name;
        $this->units[$unitIndex]['components'][$componentIndex]['search'] = '';
        $this->units[$unitIndex]['components'][$componentIndex]['results'] = [];

        // هنا نحدث الـ units الخاصة بالـ select
        $this->units[$unitIndex]['components'][$componentIndex]['available_units'] = $product->units->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
            ];
        })->toArray();

        // اختياري: إعادة تعيين الـ unit_id لو كان موجود
        $this->units[$unitIndex]['components'][$componentIndex]['unit_id'] = null;
    }


    public function clearProductSelection($unitIndex, $componentIndex)
    {
        if (!isset($this->units[$unitIndex]['components'][$componentIndex])) return;

        $this->units[$unitIndex]['components'][$componentIndex]['product_id'] = '';
        $this->units[$unitIndex]['components'][$componentIndex]['product_name'] = '';
        $this->units[$unitIndex]['components'][$componentIndex]['search'] = '';
        $this->units[$unitIndex]['components'][$componentIndex]['results'] = [];
    }





    public function mount()
    {
        $this->companies = Company::all();
        $this->allProducts = Product::all();
        $this->MeasureUnits = Unit::all();
        $this->addUnit(); // إضافة وحدة افتراضية
    }


    public function addAddon()
    {
        $this->addons[] = ['name' => '', 'price' => 0, 'active' => true];
    }

    public function removeAddon($index)
    {
        unset($this->addons[$index]);
        $this->addons = array_values($this->addons);
    }



    public function storeUnit()
    {
        $this->validate([
            'newUnit.name' => 'required|string|max:255',
        ]);

        $unit = Unit::create([
            'name' => $this->newUnit['name'],
            'is_active' => $this->newUnit['is_active'] ?? true,
        ]);

        // تحدّث القائمة
        $this->MeasureUnits = Unit::all();

        // خلي آخر وحدة مختارة
        $lastIndex = count($this->units) - 1;
        if ($lastIndex >= 0) {
            $this->units[$lastIndex]['measure_unit_id'] = $unit->id;
        }

        // تنظيف المودال
        $this->newUnit = ['name' => '', 'is_active' => true];

        // إغلاق المودال من الواجهة
        $this->dispatch('close-modal', id: 'addUnitModal');
    }

    /***** إدارة الوحدات *****/
    public function addUnit()
    {
        // لو أول مرة نضيف وحدة
        if (empty($this->units)) {
            $this->units[] = [
                'name' => '',
                'price' => 0,
                'sallPrice' => 0,
                'conversion_factor' => 1.0,
                'bar_codes' => [''],
                'components' => [],
            ];
            return;
        }

        // أول وحدة كأساس
        $baseUnit = $this->units[0];
        $baseBuy = (float) ($baseUnit['price'] ?? 0);
        $baseSell = (float) ($baseUnit['sallPrice'] ?? 0);

        // الوحدة الجديدة الافتراضية
        $newUnit = [
            'name' => '',
            'conversion_factor' => 1.0,
            'bar_codes' => [''],
            'components' => [],
            'price' => 0,
            'sallPrice' => 0,
        ];
        $factor = 1.0;
        // نحسب السعر لو في وحدات سابقة ومعامل معروف
        foreach ($this->units as $unit) {
            if (!empty($unit['conversion_factor'])) {
                $factor = $unit['conversion_factor'] * $factor;
            }
        }


        // السعر هنا يعتمد على أول وحدة وليس السابقة
        $newUnit['price'] = round($baseBuy * $factor, 2);
        $newUnit['sallPrice'] = round($baseSell * $factor, 2);

        $this->units[] = $newUnit;
    }


    public function removeUnit($index)
    {
        if (count($this->units) > 1 && isset($this->units[$index])) {
            unset($this->units[$index]);
            $this->units = array_values($this->units);
            if ($this->baseUnit == $index) $this->baseUnit = 0;
            elseif ($this->baseUnit > $index) $this->baseUnit--;
        }
    }

    /***** إدارة البركود *****/
    public function addBarCode($unitIndex)
    {

        $this->units[$unitIndex]['bar_codes'][] = '';
    }

    public function removeBarCode($unitIndex, $barcodeIndex)
    {
        if (isset($this->units[$unitIndex]['bar_codes'][$barcodeIndex])) {
            unset($this->units[$unitIndex]['bar_codes'][$barcodeIndex]);
            $this->units[$unitIndex]['bar_codes'] = array_values($this->units[$unitIndex]['bar_codes']);
        }
    }
    public function addBarcodeOnEnter($unitIndex)
    {
        // إضافة input جديد
        $this->units[$unitIndex]['bar_codes'][] = '';

        // ابعت event للـ JS عشان يعمل focus
        $this->dispatch('focus-last-barcode', unitIndex: $unitIndex);

    }

    /***** إدارة المكونات للوحدة المركبة *****/
    public function addComponent($unitIndex)
    {
        if (!isset($this->units[$unitIndex]['components']) || !is_array($this->units[$unitIndex]['components'])) {
            $this->units[$unitIndex]['components'] = [];
        }

        $this->units[$unitIndex]['components'][] = [
            'product_id' => null,
            'quantity' => 0,
            'product_unit_id' => null,
        ];
    }

    public function removeComponent($unitIndex, $compIndex)
    {
        if (isset($this->units[$unitIndex]['components'][$compIndex])) {
            unset($this->units[$unitIndex]['components'][$compIndex]);
            $this->units[$unitIndex]['components'] = array_values($this->units[$unitIndex]['components']);
        }

        $this->calculateTotalCost($unitIndex);
    }

    /***** إدارة الخيارات *****/
    public function addOption()
    {
        $this->options[] = [
            'name' => '',
            'active' => true,
            'values' => []
        ];
    }

    public function removeOption($index)
    {
        if (isset($this->options[$index])) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    public function addValue($optionIndex)
    {
        if (isset($this->options[$optionIndex])) {
            $this->options[$optionIndex]['values'][] = [
                'name' => '',
                'price' => 0
            ];
        }
    }

    public function removeValue($optionIndex, $valueIndex)
    {
        if (isset($this->options[$optionIndex]['values'][$valueIndex])) {
            unset($this->options[$optionIndex]['values'][$valueIndex]);
            $this->options[$optionIndex]['values'] = array_values($this->options[$optionIndex]['values']);
        }
    }

    /***** فقط التحقق بدون حفظ *****/
    public function create()
    {
        $this->validate();
        try {
            DB::beginTransaction();


            $path = null;
            if ($this->photo) {
                $path = $this->photo->store('products', 'public');
            }

            $product = Product::create([
                'name' => $this->name,
                'section_id' => $this->section,
                'description' => $this->description,
                'photo' =>  $path ?: null,
                'uses_recipe' => $this->hasRecipe,
                'company_id' => null, // مؤقتاً
                'active'   => $this->isActive,
                'qtn' => $this->stockQnt,
                'offer_rate'    => 0,
                'company_id' => $this->company_id ?? null,
            ]);

            foreach ($this->units as $index => $unitData) {

                $conversionFactor = 1;
                for ($i = $index; $i > 0; $i--) {
                    if ($index == 0) {
                        $conversionFactor = 1;
                    } else {
                        $conversionFactor = $unitData['conversion_factor'] * $conversionFactor;
                    }
                }

                $productUnit = ProductUnits::create([
                    'product_id' => $product->id,
                    'unit_id' => $unitData['measure_unit_id'],
                    'conversion_factor' => $conversionFactor,
                    'price' => $product->uses_recipe ? $unitData['total_cost'] : $unitData['price'],
                    'sallprice' => $unitData['sallPrice'],
                    'is_base' => ($index == 0) ? true : false,
                ]);
                $productUnit->refresh();

                foreach ($unitData['bar_codes'] as $barcode) {

                    if (!empty($barcode)) {
                        $productUnit->barcodes()->create([
                            'code' => $barcode,
                            'product_unit_id' => $productUnit->id,
                        ]);
                    } else {
                        // نتخطى البركود الفارغ
                        $barcode = Barcode::where('is_default', true)->latest('id')->first();

                        if ($barcode) {
                            $code = $barcode->code;

                            $code = (int) $code + 1;
                            $productUnit->barcodes()->create([
                                'code' => $code,
                                'product_unit_id' => $productUnit->id,
                                'is_default' => true,
                            ]);
                        }
                    }
                }

                if ($product->uses_recipe) {

                    foreach ($unitData['components'] as $component) {
                        $productUnit->components()->create([
                            // المنتج الاساسي اللي الوحدة دي بتتكون منه
                            'product_id' => $component['product_id'],
                            // الوحدة بتاعة المكون
                            'component_unit_id' => $component['component_unit_id'],

                            'quantity' => $component['quantity'],
                        ]);
                    }
                }
            }


            foreach ($this->options as $optionData) {
                $option = $product->options()->create([
                    'name' => $optionData['name'],
                    'active' => $optionData['active'] ?? true,
                ]);
                foreach ($optionData['values'] as $valueData) {
                    $option->values()->create([
                        'name' => $valueData['name'],
                        'price' => $valueData['price'],
                    ]);
                }
            }

            if (!empty($this->addons)) {
                foreach ($this->addons as $addonData) {
                    $product->addsOn()->create([
                        'name' => $addonData['name'],
                        'price' => $addonData['price'] ?? 0,
                        'active' => $addonData['active'] ?? true,
                    ]);
                }
            }




            DB::commit();

            session()->flash('success', '✅ تم  إنشاء المنتج بنجاح!');

            return redirect()->route('products.create');
        } catch (\Exception $e) {
            DB::rollBack();

            session()->flash('error', '❌ حدث خطأ أثناء التحقق: ' . $e->getMessage());
            return redirect()->route('products.create');
        }
    }
    public function render()
    {
        $sections = SubSection::all();

        return view('livewire.products.create', [
            'sections' => $sections,
            'companies' => $this->companies,
            'allProducts' => $this->allProducts,
            'MeasureUnits' => $this->MeasureUnits,

        ]);
    }
}
