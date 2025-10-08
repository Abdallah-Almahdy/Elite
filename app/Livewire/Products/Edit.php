<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $data;
    public $sections;

    public $name;
    public $price;
    public $photo;
    public $description;
    public $section;
    public $qnt;
    public $offer_rate;
    public $hasRecipe;

    public function update()
    {
        $product = Product::find($this->data->id);

        if ($this->name) {
            $this->validate(['name' => 'string',]);
            $product->name = $this->name;
        }


        if ($this->price) {
            $this->validate(['price' => 'required|numeric']);
            $product->price = $this->price;
        }

        if ($this->section) {
            $this->validate(['section' => 'integer',]);
            $product->section_id = $this->section;
        }
        if ($this->description) {
            $this->validate(['description' => 'string',]);
            $product->description = $this->description;
        }


        if ($this->photo) {
            $this->validate(['photo' => 'image|mimes:jpeg,jpg,png',]);
            $product->photo = $this->photo->storeAs('products', rand() . '.jpg', 'my_public');
        }



        if (isset($this->hasRecipe)) {
            $product->uses_recipe = $this->hasRecipe;
            if ($this->hasRecipe && !$product->recipe) {
            $product->recipe()->create(['name' => $product->name  , 'product_id' => $product->id,]);

            }
            if (!$this->hasRecipe && $product->recipe) {
            $product->recipe()->delete();
            }
        }



       if ($this->offer_rate + 0 === 0) {

            $product->offer_rate = 0;
        }
        if ($this->offer_rate) {
            if ($this->offer_rate + 0 > 0) {
                $this->validate(['offer_rate' => 'integer',]);
                $product->offer_rate = $this->offer_rate;
            }
        }
        // dd($this->qnt);
        if ($this->qnt != null) {
            if ($this->qnt == 1) {
                $product->qnt = 5000;
            }
            if ($this->qnt == 0) {
                $product->qnt = 0;
            }
        }


        $product->save();

        $this->redirectRoute('products.index');
    }

    public function render()
    {

        return view('livewire.products.edit');
    }
}
