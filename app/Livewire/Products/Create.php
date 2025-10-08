<?php

namespace App\Livewire\Products;

use Livewire\Component;

use App\Models\Company;
use App\Models\Product;
use App\Models\Section;
use Livewire\WithFileUploads;
use App\Models\SubSection;

class create  extends Component
{
    use WithFileUploads;

    public $name;
    public $price;
    public $photo;
    public $section;
    public $stock;
    public $stockQnt;
    public $bar_code;
    public $description;
    public $hasRecipe = false;
    public function create()
    {
        $this->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'section' => 'required',
            'photo' => 'image|max:1024',
            'hasRecipe' => 'boolean'
        ]);

        $data = [
            'name' => $this->name,
            'price' => $this->price,
            'photo' => $this->photo->storeAs('products', rand() . '.jpg', 'my_public'),
            'section_id' => $this->section,
            'active' => $this->stock ?? true,
            'qnt' => $this->stockQnt ?? 5000,
            'bar_code' => $this->bar_code,
            'description' => $this->description,
            'uses_recipe' => $this->hasRecipe,
        ];

        $product = Product::create($data);
        if ($this->hasRecipe) {
        $product->recipe()->create([
            'name' => $product->name
        ]);
    }

        session()->flash('done', 'تم إضافة منتج جديد بنجاح');
        $this->reset();


    }

    public function render()
    {
        $sections = SubSection::all();
        $companies = Company::all();
        return view('livewire.products.create', [
            'sections' => $sections,
            'companies' => $companies,
        ]);
    }
}
