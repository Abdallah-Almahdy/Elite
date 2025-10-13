<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Company;
use App\Models\Options;
use App\Models\OptionsValues;
use App\Models\Product;
use App\Models\SubSection;
use App\Models\ProductOption;
use Livewire\WithFileUploads;


class Edit extends Component
{
    use WithFileUploads;

    public $productId;
    public $data;
    public $sections;
    public $companies = [];

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
    public $qnt;
    public $active;

    protected $rules = [
        'name' => 'required',
        'price' => 'required|numeric',
        'section' => 'required',
        'photo' => 'nullable|image|max:1024',
        'hasRecipe' => 'boolean',
        'stockQnt' => 'nullable|integer|min:0',
        'active' => 'boolean',
        'options.*.name' => 'required|string|max:255',
        'options.*.active' => 'boolean',
        'options.*.values.*.name' => 'required|string|max:255',
        'options.*.values.*.price' => 'nullable|numeric|min:0',
    ];


    public function mount($product = null)
    {
        // الحصول على الـ product إما من parameter أو من ID
        if ($product instanceof Product) {
            $this->data = $product;
            $this->productId = $product->id;
        } else {
            $this->productId = $product;
            $this->data = Product::findOrFail($this->productId);
        }

        $this->sections = SubSection::all();
        $this->companies = Company::all();

        // Initialize form data
        $this->name = $this->data->name;
        $this->price = $this->data->price;
        $this->description = $this->data->description;
        $this->section = $this->data->section_id;
        $this->bar_code = $this->data->bar_code;
        $this->hasRecipe = $this->data->uses_recipe;
        $this->qnt = $this->data->qnt;
        $this->enableStock = $this->data->qnt > 0;
        $this->stockQnt = $this->data->qnt;
        $this->active = $this->data->active;

        // Load existing options
        $this->loadOptions();
    }

    protected function loadOptions()
    {
        $this->options = [];

        // جلب الـ Options الخاصة بالمنتج
        $productOptions = ProductOption::where('product_id', $this->productId)
            ->with(['option.values'])
            ->get();

        foreach ($productOptions as $productOption) {
            $option = $productOption->option;
            $values = [];

            foreach ($option->values as $value) {
                $values[] = [
                    'name' => $value->name,
                    'price' => $value->price,
                    'id' => $value->id
                ];
            }

            $this->options[] = [
                'name' => $option->name,
                'active' => $option->active,
                'values' => $values,
                'id' => $option->id,
                'product_option_id' => $productOption->id
            ];
        }
    }

    // إضافة خيار جديد
    public function addOption()
    {
        $this->options[] = [
            'name' => '',
            'active' => true,
            'values' => [],
            'id' => null,
            'product_option_id' => null
        ];
    }

    // إزالة خيار
    public function removeOption($index)
    {
        if (isset($this->options[$index])) {
            // إذا كان الخيار موجود في قاعدة البيانات، قم بحذفه
            if ($this->options[$index]['id']) {
                $option = Options::find($this->options[$index]['id']);
                if ($option) {
                    // حذف الـ ProductOption أولاً
                    ProductOption::where('option_id', $option->id)
                                ->where('product_id', $this->productId)
                                ->delete();
                    // ثم حذف الـ Option
                    $option->delete();
                }
            }
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
                'price' => 0,
                'id' => null
            ];
        }
    }

    // إزالة قيمة من خيار
    public function removeValue($optionIndex, $valueIndex)
    {
        if (isset($this->options[$optionIndex]['values'][$valueIndex])) {
            // إذا كانت القيمة موجودة في قاعدة البيانات، قم بحذفها
            if ($this->options[$optionIndex]['values'][$valueIndex]['id']) {
                OptionsValues::find($this->options[$optionIndex]['values'][$valueIndex]['id'])->delete();
            }
            unset($this->options[$optionIndex]['values'][$valueIndex]);
            $this->options[$optionIndex]['values'] = array_values($this->options[$optionIndex]['values']);
        }
    }

    public function update()
    {
        $this->validate();

        $product = Product::findOrFail($this->productId);


        // تحضير بيانات المنتج
        $productData = [
            'name' => $this->name,
            'price' => $this->price,
            'section_id' => $this->section,
            'qnt' => $this->enableStock ? ($this->stockQnt ?? 0) : 0,
            'bar_code' => $this->bar_code,
            'description' => $this->description,
            'uses_recipe' => $this->hasRecipe,
            'active' => $this->active,
        ];

        // حفظ الصورة إذا وجدت
        if ($this->photo) {
            $productData['photo'] = $this->photo->storeAs('products', rand() . '.jpg', 'my_public');
        }

        // تحديث المنتج
        $product->update($productData);

        // حفظ الـ Options والـ Values
        $this->saveOptions($product);


        // إنشاء أو حذف وصفة إذا كان المنتج يحتوي على وصفة
        if ($this->hasRecipe) {
            if (!$product->recipe) {
                $product->recipe()->create([
                    'name' => $product->name
                ]);
            }
        } else {
            if ($product->recipe) {
                $product->recipe()->delete();
            }
        }

        session()->flash('done', 'تم تعديل المنتج بنجاح');
        $this->redirectRoute('products.index');
    }

   protected function saveOptions($product)
{
    // جمع جميع الـ option IDs الحالية المرتبطة بالمنتج
    $currentOptionIds = collect($this->options)->pluck('id')->filter()->toArray();

    // جلب جميع الـ Options المرتبطة بالمنتج حالياً
    $existingProductOptions = ProductOption::where('product_id', $product->id)->get();

    foreach ($existingProductOptions as $productOption) {
        // إذا كان الـ Option لم يعد موجوداً في الـ options الجديدة
        if (!in_array($productOption->option_id, $currentOptionIds)) {
            // حذف جميع الـ Values المرتبطة بهذا الـ Option أولاً
            OptionsValues::where('option_id', $productOption->option_id)->delete();

            // ثم حذف الـ ProductOption
            $productOption->delete();

            // التحقق إذا كان الـ Option مستخدم في أي منتج آخر
            $otherUses = ProductOption::where('option_id', $productOption->option_id)->count();
            if ($otherUses == 0) {
                // إذا لم يكن مستخدم في أي منتج آخر، احذف الـ Option نفسه
                Options::where('id', $productOption->option_id)->delete();
            }
        }
    }

    // إنشاء أو تحديث الـ Options والـ Values الجديدة
    if (!empty($this->options)) {
        foreach ($this->options as $optionData) {
            // إنشاء أو تحديث الـ Option
            if ($optionData['id']) {
                $option = Options::find($optionData['id']);
                if ($option) {
                    $option->update([
                        'name' => $optionData['name'],
                        'active' => $optionData['active'] ?? true,
                    ]);
                } else {
                    $option = Options::create([
                        'name' => $optionData['name'],
                        'active' => $optionData['active'] ?? true,
                    ]);
                }
            } else {
                $option = Options::create([
                    'name' => $optionData['name'],
                    'active' => $optionData['active'] ?? true,
                ]);
            }

            // ربط أو تحديث الـ ProductOption
            ProductOption::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'option_id' => $option->id,
                ],
                [
                    'product_id' => $product->id,
                    'option_id' => $option->id,
                ]
            );

            // جمع جميع الـ value IDs الحالية
            $currentValueIds = collect($optionData['values'])->pluck('id')->filter()->toArray();

            // حذف الـ Values التي لم تعد موجودة
            OptionsValues::where('option_id', $option->id)
                ->whereNotIn('id', $currentValueIds)
                ->delete();

            // حفظ الـ Values الخاصة بالـ Option
            if (!empty($optionData['values'])) {
                foreach ($optionData['values'] as $valueData) {
                    if ($valueData['id']) {
                        // تحديث الـ Value الموجود
                        OptionsValues::where('id', $valueData['id'])->update([
                            'name' => $valueData['name'],
                            'price' => $valueData['price'] ?? 0,
                        ]);
                    } else {
                        // إنشاء الـ Value جديد
                        OptionsValues::create([
                            'option_id' => $option->id,
                            'name' => $valueData['name'],
                            'price' => $valueData['price'] ?? 0,
                        ]);
                    }
                }
            }
        }
    }
}

    public function render()
    {
        return view('livewire.products.edit', [
            'sections' => $this->sections,
            'companies' => $this->companies,
        ]);
    }
}
