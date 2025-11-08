<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Company;
use App\Models\SubSection;
use App\Models\Product;
use App\Models\Unit;

class Create extends Component
{
    use WithFileUploads;

    public $name;
    public $price;
    public $photo;
    public $section;
    public $bar_code;
    public $description;

    public $enableStock = false;
    public $stockQnt;

    public $units = [];
    public $baseUnit = 0;

    public $companies = [];
    public $options = [];
    public $hasRecipe = false;

    public $allProducts = []; // لاختيار المكونات
    public $MeasureUnits = [];
    public $showRecipes = [];
    public function toggleRecipeVisibility($index)
    {
        $this->showRecipes[$index] = !($this->showRecipes[$index] ?? false);
    }

    protected bool $updatingPrices = false;

    public function updated($propertyName, $value)
    {
        logger("Updated: " . $propertyName);

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

            // --------- 2) تحديث سعر الشراء (price) ---------
            // if ($changedIndex !== null && $changedField === 'price') {
            //     // تحديث الوحدات فوق الوحدة المعدلة بالقسمة
            //     for ($i = $changedIndex - 1; $i >= 0; $i--) {
            //         $nextPrice = $getFloat($this->units[$i + 1]['price']);
            //         $factorNext = $getFloat($this->units[$i + 1]['conversion_factor']);
            //         if ($factorNext <= 0) $factorNext = 1;
            //         $this->units[$i]['price'] = round($nextPrice / $factorNext, 2);
            //     }

            //     // تحديث الوحدة المعدلة وما بعدها بالضرب
            //     for ($i = $changedIndex + 1; $i < $count; $i++) {
            //         $prevPrice = $getFloat($this->units[$i - 1]['price']);
            //         $factor = $getFloat($this->units[$i]['conversion_factor']);
            //         if ($factor <= 0) $factor = 1;
            //         $this->units[$i]['price'] = round($prevPrice * $factor, 2);
            //     }
            // }

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
        $product = Product::find($productId);
        if (!$product) return;

        $this->units[$unitIndex]['components'][$componentIndex]['product_id'] = $product->id;
        $this->units[$unitIndex]['components'][$componentIndex]['product_name'] = $product->name;
        $this->units[$unitIndex]['components'][$componentIndex]['search'] = '';
        $this->units[$unitIndex]['components'][$componentIndex]['results'] = [];
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





    public $newUnit = [
        'name' => '',
        'is_active' => true,
    ];

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
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'section' => 'required',
            'units.*.name' => 'required|string|max:255',
        ]);

        session()->flash('done', '✅ تم التحقق من البيانات بنجاح (بدون حفظ)');
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
