<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Company;
use App\Models\SubSection;
use App\Models\Product;

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

    public function mount()
    {
        $this->companies = Company::all();
        $this->allProducts = Product::all();
        $this->addUnit(); // إضافة وحدة افتراضية
    }

    /***** إدارة الوحدات *****/
    public function addUnit()
    {
        $this->units[] = [
            'name' => '',
            'price' => 0,
            'conversion_factor' => 1.0,
            'isComposite' => false,
            'bar_codes' => [''],
            'components' => [],
        ];
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
            'unit_id' => null,
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
            
        ]);
    }
}
