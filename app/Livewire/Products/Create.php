<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Company;
use App\Models\Options;
use App\Models\OptionsValues;
use App\Models\OrderProductOptionValue;
use App\Models\Product;
use App\Models\Section;
use Livewire\WithFileUploads;
use App\Models\SubSection;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;

class create extends Component
{
    use WithFileUploads;

    public $name;
    public $price;
    public $photo;
    public $section;
    public $stock;
    public $enableStock = false;
    public $bar_code;
    public $description;
    public $hasRecipe = false;
    public $options = [];
    public $stockQnt;
    public $companies = [];
    public $units = [];
    public $baseUnit = 0;

    public function mounts()
    {
        // إضافة وحدة افتراضية عند تحميل المكون
        $this->addUnit();
    }

    public function addUnit()
    {
        $this->units[] = [
            'name' => '',
            'conversion_factor' => 1.0
        ];
    }

    public function removeUnit($index)
    {
        if (count($this->units) > 1 && isset($this->units[$index])) {
            unset($this->units[$index]);
            $this->units = array_values($this->units);

            // إذا كانت الوحدة المحذوفة هي الأساسية، اجعل الوحدة الأولى أساسية
            if ($this->baseUnit == $index) {
                $this->baseUnit = 0;
            } elseif ($this->baseUnit > $index) {
                $this->baseUnit--;
            }
        }
    }
    protected $rules = [
        'name' => 'required',
        'price' => 'required|numeric',
        'section' => 'required',
        'photo' => 'nullable|image|max:1024',
        'hasRecipe' => 'boolean',
        'stockQnt' => 'nullable|integer|min:0',
        'options.*.name' => 'required|string|max:255',
        'options.*.active' => 'boolean',
        'options.*.values.*.name' => 'required|string|max:255',
        'options.*.values.*.price' => 'nullable|numeric|min:0',
    ];

    public function mount()
    {
        // التهيئة الأولية
        $this->companies = Company::all();
    }

    // إضافة خيار جديد
    public function addOption()
    {
        $this->options[] = [
            'name' => '',
            'active' => true,
            'values' => []
        ];
    }

    // إزالة خيار
    public function removeOption($index)
    {
        if (isset($this->options[$index])) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    // إضافة قيمة لخيار
    public function addValue($optionIndex)
    {
        if (isset($this->options[$optionIndex])) {
            $this->options[$optionIndex]['values'][] = [
                'name' => '',
                'price' => 0
            ];
        }
    }

    // إزالة قيمة من خيار
    public function removeValue($optionIndex, $valueIndex)
    {
        if (isset($this->options[$optionIndex]['values'][$valueIndex])) {
            unset($this->options[$optionIndex]['values'][$valueIndex]);
            $this->options[$optionIndex]['values'] = array_values($this->options[$optionIndex]['values']);
        }
    }

    public function create()
    {
        $this->validate();

        // تحضير بيانات المنتج
        $productData = [
            'name' => $this->name,
            'price' => $this->price,
            'section_id' => $this->section,
            'active' => $this->stock ?? true,
            'qnt' => $this->enableStock ? ($this->stockQnt ?? 0) : 0,
            'bar_code' => $this->bar_code,
            'description' => $this->description,
            'uses_recipe' => $this->hasRecipe,
        ];

        // حفظ الصورة إذا وجدت
        if ($this->photo) {
            $productData['photo'] = $this->photo->storeAs('products', rand() . '.jpg', 'my_public');
        }

        // إنشاء المنتج
        $product = Product::create($productData);

        // حفظ الـ Options والـ Values إذا وجدت
        if (!empty($this->options)) {
            foreach ($this->options as $optionData) {
                // إنشاء الـ Option
                $option = Options::create([
                    'name' => $optionData['name'],
                    'active' => $optionData['active'] ?? true,
                ]);

                ProductOption::create([
                    'product_id' => $product->id,
                    'option_id' => $option->id,
                ]);

                // حفظ الـ Values الخاصة بالـ Option
                if (!empty($optionData['values'])) {
                    foreach ($optionData['values'] as $valueData) {
                        $v =  OptionsValues::create([
                            'option_id' => $option->id,
                            'name' => $valueData['name'],
                            'price' => $valueData['price'] ?? 0,
                        ]);
                    }
                }
            }
        }
        // إنشاء وصفة إذا كان المنتج يحتوي على وصفة
        if ($this->hasRecipe) {
            $product->recipe()->create([
                'name' => $product->name
            ]);
        }

        session()->flash('done', 'تم إضافة منتج جديد بنجاح');
        $this->resetForm();
    }

    // إعادة تعيين النموذج
    public function resetForm()
    {
        $this->reset([
            'name',
            'price',
            'photo',
            'section',
            'stock',
            'enableStock',
            'bar_code',
            'description',
            'hasRecipe',
            'options',
            'stockQnt',
        ]);
    }

    public function render()
    {
        $sections = SubSection::all();
        return view('livewire.products.create', [
            'sections' => $sections,
            'companies' => $this->companies,
        ]);
    }
}
