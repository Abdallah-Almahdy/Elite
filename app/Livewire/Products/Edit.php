<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Company;
use App\Models\Options;
use App\Models\OptionsValues;
use App\Models\Product;
use App\Models\SubSection;
use App\Models\ProductOption;
use App\Models\productUintComponent;
use App\Models\ProductUnits;
use App\Models\Unit;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $name;
    public $photo;
    public $section;
    public $description;
    public $hasRecipe = false;
    public $isActive = true;
    public $company;
    public $productId;
    public $enableStock = false;
    public $stockQnt = 0;
    public $is_stock;
    public $is_weight;

    public $units = [];
    public $baseUnit = 0;
    public $sallPrice;
    public $price;
    public $bar_codes;
    public $companies = [];
    public $options = [];

    public $allProducts = [];
    public $MeasureUnits = [];
    public $showRecipes = [];
    public $addons = [];

    public $defaultUnit;

    protected bool $updatingPrices = false;
    public $newUnit = [
        'name' => '',
        'is_active' => true,
    ];
    public $sections = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'section' => 'required',
        'photo' => 'nullable|image|max:1024',
        'hasRecipe' => 'boolean',
        'stockQnt' => 'nullable|integer|min:0',
        'isActive' => 'boolean',
        'is_stock' => 'boolean',
        'is_weight' => 'boolean',

        'units.*.measure_unit_id' => 'required|exists:units,id',
        'units.*.price' => 'required|numeric|min:0',
        'units.*.sallPrice' => 'required|numeric|min:0',
        'units.*.conversion_factor' => 'required|numeric|min:0.01',

        'units.*.components.*.product_id' => 'required|exists:products,id',
        'units.*.components.*.component_unit_id' => 'required|exists:units,id',
        'units.*.components.*.quantity' => 'required|numeric|min:0.01',

        'options.*.name' => 'required|string|max:255',
        'options.*.active' => 'boolean',
        'options.*.values.*.name' => 'required|string|max:255',
        'options.*.values.*.price' => 'nullable|numeric|min:0',

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
        'is_stock' => 'منتج مخزني',
        'is_weight' => 'منتج بوزن',

        'units.*.measure_unit_id' => 'الوحدة',
        'units.*.price' => 'سعر الشراء',
        'units.*.sallPrice' => 'سعر البيع',
        'units.*.conversion_factor' => 'معامل التحويل',

        'units.*.components.*.product_id' => 'المنتج المكوّن',
        'units.*.components.*.component_unit_id' => 'وحدة المكوّن',
        'units.*.components.*.quantity' => 'كمية المكوّن',

        'options.*.name' => 'اسم الخيار',
        'options.*.values.*.name' => 'اسم قيمة الخيار',
        'options.*.values.*.price' => 'سعر قيمة الخيار',

        'addons.*.name' => 'اسم الإضافة',
        'addons.*.price' => 'سعر الإضافة',
        'addons.*.active' => 'حالة الإضافة',
    ];

    public function mount($product)
    {
        $this->sections = SubSection::all();
        $this->companies = Company::all();
        $this->MeasureUnits = Unit::all();
        $this->productId = $product;
        $product = Product::with('section', 'units')->find($product);

        $this->name = $product->name;
        $this->description = $product->description;
        $this->section = $product->section_id;
        $this->hasRecipe = $product->uses_recipe;
        $this->isActive = $product->active ? true : false;
        $this->is_stock = $product->is_stock ? true : false;
        $this->is_weight = $product->is_weight ? true : false;

        $index = 0;
        $conversionFactor = 1;
        $conversion_factor = [];
        $this->units = $product->units->map(function ($unit) use (&$index, &$conversionFactor, &$conversion_factor) {
            $index++;
            $pivot = $unit->pivot;
            $conversion_factor[$index] = $pivot->conversion_factor;
            $conversionFactor = $pivot->conversion_factor;
            $pivot->refresh();
            for ($i = $index; $i > 1; $i--) {
                $conversionFactor = $conversionFactor / $conversion_factor[$i - 1];
            }

            return [
                'id' => $pivot->id,
                'measure_unit_id' => $unit->id,
                'conversion_factor' => number_format((float) $conversionFactor, 2, '.', ''),
                'sallPrice' => $pivot->sallprice,
                'price' => $pivot->price,
                'bar_codes' => $pivot->barcodes->pluck('code')->toArray() ?? [''],
                'total_cost' => $total_cost ?? 0,
                'components' => $pivot->components->map(function ($comp) {
                    $product = $comp->product;
                    return [
                        'id' => $comp->id,
                        'product_id' => $comp->product_id,
                        'product_name' => $product->name ?? '',
                        'component_unit_id' => $comp->component_unit_id,
                        'quantity' => $comp->quantity,
                        'available_units' => $product
                            ? $product->units->map(function ($u) {
                                return ['id' => $u->id, 'name' => $u->name];
                            })
                            : [],
                        'search' => '',
                        'results' => [],
                    ];
                })->toArray(),
            ];
        })->toArray();

        $this->options = $product->options->map(function ($option) {
            return [
                'id' => $option->id,
                'name' => $option->name,
                'active' => $option->active ? true : false,
                'values' => $option->values->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'name' => $value->name,
                        'price' => $value->price,
                    ];
                })->toArray(),
            ];
        })->toArray();

        $this->addons = $product->addsOn->map(function ($addon) {
            return [
                'id' => $addon->id,
                'name' => $addon->name,
                'price' => $addon->price,
                'active' => $addon->active ? true : false,
            ];
        })->toArray();
    }

    public function toggleRecipeVisibility($index)
    {
        $this->showRecipes[$index] = !($this->showRecipes[$index] ?? false);
    }

    public function updated($propertyName, $value)
    {
        if (str_contains($propertyName, 'components') && str_ends_with($propertyName, 'search')) {
            $this->handleProductSearch($propertyName);
        }

        if (
            str_contains($propertyName, 'units') &&
            (str_ends_with($propertyName, 'price') || str_ends_with($propertyName, 'sallPrice') || str_ends_with($propertyName, 'conversion_factor'))
        ) {
            preg_match('/units\.(\d+)\.(price|sallPrice|conversion_factor)/', $propertyName, $matches);
            $changedIndex = isset($matches[1]) ? (int) $matches[1] : null;
            $changedField = $matches[2] ?? null;
            $this->updateDependentPrices($changedIndex, $changedField);
        }

        if (preg_match('/units\.(\d+)\.components\.(\d+)\.(quantity|component_unit_id)/', $propertyName, $matches)) {
            $unitIndex = $matches[1];
            $componentIndex = $matches[2];
            $component = $this->units[$unitIndex]['components'][$componentIndex] ?? null;
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

            if (($changedIndex === 0 && $changedField === 'sallPrice') || $changedField === 'conversion_factor') {
                for ($i = 1; $i < $count; $i++) {
                    $prevSell = $getFloat($this->units[$i - 1]['sallPrice']);
                    $factor = $getFloat($this->units[$i]['conversion_factor']);
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

            if ($changedField === 'conversion_factor' && $changedIndex !== null && $changedIndex > 0) {
                $prevPrice = $getFloat($this->units[$changedIndex - 1]['price']);
                $factor = $getFloat($this->units[$changedIndex]['conversion_factor']);
                if ($factor <= 0) $factor = 1;
                $this->units[$changedIndex]['price'] = round($prevPrice * $factor, 2);
            }

            $this->units = array_values($this->units);
            $this->dispatch('prices-updated', units: $this->units);
        } finally {
            $this->updatingPrices = false;
        }
    }

    public function handleProductSearch($propertyName)
    {
        preg_match('/units\.(\d+)\.components\.(\d+)\.search/', $propertyName, $matches);
        if (!$matches) return;

        [, $unitIndex, $componentIndex] = $matches;
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
        $product = Product::with('units')->find($productId);
        if (!$product) return;

        $this->units[$unitIndex]['components'][$componentIndex]['product_id'] = $product->id;
        $this->units[$unitIndex]['components'][$componentIndex]['product_name'] = $product->name;
        $this->units[$unitIndex]['components'][$componentIndex]['search'] = '';
        $this->units[$unitIndex]['components'][$componentIndex]['results'] = [];

        $this->units[$unitIndex]['components'][$componentIndex]['available_units'] = $product->units->map(function ($u) {
            return ['id' => $u->id, 'name' => $u->name];
        })->toArray();

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

        $this->MeasureUnits = Unit::all();

        $lastIndex = count($this->units) - 1;
        if ($lastIndex >= 0) {
            $this->units[$lastIndex]['measure_unit_id'] = $unit->id;
        }

        $this->newUnit = ['name' => '', 'is_active' => true];
        $this->dispatch('close-modal', id: 'addUnitModal');
    }

    public function addUnit()
    {
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

        $baseUnit = $this->units[0];
        $baseBuy = (float) ($baseUnit['price'] ?? 0);
        $baseSell = (float) ($baseUnit['sallPrice'] ?? 0);

        $newUnit = [
            'name' => '',
            'conversion_factor' => 1.0,
            'bar_codes' => [''],
            'components' => [],
            'price' => 0,
            'sallPrice' => 0,
        ];
        $factor = 1.0;
        foreach ($this->units as $unit) {
            if (!empty($unit['conversion_factor'])) {
                $factor = $unit['conversion_factor'] * $factor;
            }
        }

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

    public function addBarCode($unitIndex)
    {
        $this->units[$unitIndex]['bar_codes'][] = '';
    }

    public function addBarcodeOnEnter($unitIndex)
    {
        $this->units[$unitIndex]['bar_codes'][] = '';
        $this->dispatch('focus-last-barcode', unitIndex: $unitIndex);
    }

    public function removeBarCode($unitIndex, $barcodeIndex)
    {
        if (isset($this->units[$unitIndex]['bar_codes'][$barcodeIndex])) {
            unset($this->units[$unitIndex]['bar_codes'][$barcodeIndex]);
            $this->units[$unitIndex]['bar_codes'] = array_values($this->units[$unitIndex]['bar_codes']);
        }
    }

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

    public function updateProduct()
    {
        $this->validate();

        $product = Product::findOrFail($this->productId);

        $product->update([
            'name' => $this->name,
            'description' => $this->description,
            'section_id' => $this->section,
            'company_id' => $this->company,
            'uses_recipe' => $this->hasRecipe,
            'active' => $this->isActive,
            'is_stock' => $this->is_stock ? 1 : 0,
            'is_weight' => $this->is_weight ? 1 : 0,
        ]);

        if ($this->photo) {
            $path = $this->photo->store('products', 'public');
            $product->update(['photo' => $path]);
        }

        $unitIds = [];

        foreach ($this->units as $index => $unitData) {
            $conversionFactor = 1;
            for ($i = $index; $i > 0; $i--) {
                $conversionFactor *= $this->units[$i]['conversion_factor'] ?? 1;
            }

            if (!empty($unitData['id'])) {
                $pivot = ProductUnits::find($unitData['id']);
                if ($pivot) {
                    $pivot->update([
                        'unit_id' => $unitData['measure_unit_id'],
                        'price' => $unitData['price'],
                        'sallprice' => $unitData['sallPrice'],
                        'conversion_factor' => $conversionFactor,
                    ]);

                    $pivot->barcodes()->delete();
                    if (!empty($unitData['bar_codes'])) {
                        foreach ($unitData['bar_codes'] as $code) {
                            if ($code) $pivot->barcodes()->create(['code' => $code]);
                        }
                    }

                    $unitIds[] = $pivot->id;
                }
            } else {
                $pivot = ProductUnits::create([
                    'product_id' => $product->id,
                    'unit_id' => $unitData['measure_unit_id'],
                    'price' => $unitData['price'],
                    'sallprice' => $unitData['sallPrice'],
                    'conversion_factor' => $conversionFactor,
                ]);

                if (!empty($unitData['bar_codes'])) {
                    foreach ($unitData['bar_codes'] as $code) {
                        if ($code) $pivot->barcodes()->create(['code' => $code]);
                    }
                }

                $unitIds[] = $pivot->id;
            }

            $componentIds = [];

            if (!empty($unitData['components'])) {
                foreach ($unitData['components'] as $compData) {
                    if (!empty($compData['id'])) {
                        $component = productUintComponent::find($compData['id']);
                        if ($component) {
                            $component->update([
                                'product_id' => $compData['product_id'],
                                'component_unit_id' => $compData['component_unit_id'],
                                'quantity' => $compData['quantity'],
                            ]);
                            $componentIds[] = $component->id;
                        }
                    } else {
                        $component = productUintComponent::create([
                            'component_unit_id' => $compData['component_unit_id'],
                            'product_id' => $compData['product_id'],
                            'quantity' => $compData['quantity'],
                            'product_unit_id' => $pivot->id
                        ]);
                        $componentIds[] = $component->id;
                    }
                }

                productUintComponent::where('product_unit_id', $pivot->id)
                    ->whereNotIn('id', $componentIds)
                    ->delete();
            }
        }

        ProductUnits::where('product_id', $product->id)
            ->whereNotIn('id', $unitIds)
            ->delete();

        $optionIds = [];
        foreach ($this->options as $optData) {
            if (!empty($optData['id'])) {
                $option = Options::find($optData['id']);
                $option->update([
                    'name' => $optData['name'],
                    'active' => $optData['active'] ?? true,
                ]);
            } else {
                $option = Options::create([
                    'product_id' => $this->productId,
                    'name' => $optData['name'],
                    'active' => $optData['active'] ?? true,
                ]);
            }
            $optionIds[] = $option->id;

            $valueIds = [];
            foreach ($optData['values'] as $valData) {
                if (!empty($valData['id'])) {
                    $val = OptionsValues::find($valData['id']);
                    $val->update([
                        'name' => $valData['name'],
                        'price' => $valData['price'] ?? 0,
                    ]);
                } else {
                    $val = OptionsValues::create([
                        'option_id' => $option->id,
                        'name' => $valData['name'],
                        'price' => $valData['price'] ?? 0,
                    ]);
                }
                $valueIds[] = $val->id;
            }

            OptionsValues::where('option_id', $option->id)
                ->whereNotIn('id', $valueIds)
                ->delete();
        }

        Options::where('product_id', $this->productId)
            ->whereNotIn('id', $optionIds)
            ->delete();

        $addonIds = [];
        foreach ($this->addons as $addonData) {
            if (!empty($addonData['id'])) {
                $addon = $product->addsOn()->find($addonData['id']);
                $addon->update([
                    'name' => $addonData['name'],
                    'price' => $addonData['price'],
                    'active' => $addonData['active'] ?? true,
                ]);
            } else {
                $addon = $product->addsOn()->create([
                    'name' => $addonData['name'],
                    'price' => $addonData['price'],
                    'active' => $addonData['active'] ?? true,
                ]);
            }
            $addonIds[] = $addon->id;
        }
        $product->addsOn()->whereNotIn('id', $addonIds)->delete();

        session()->flash('success', 'تم تحديث المنتج بنجاح');
        return redirect()->route('products.edit', $product->id);
    }

    public function render()
    {
        return view('livewire.products.edit', [
            'sections' => $this->sections,
            'companies' => $this->companies,
            'MeasureUnits' => $this->MeasureUnits,
        ]);
    }
}
