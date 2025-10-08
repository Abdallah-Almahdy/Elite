<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\ProductRecipeOverride;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class ProductRecipeOverrideController extends Controller
{
    public function index()
    {
        $overrides = ProductRecipeOverride::with(['product', 'ingredient'])->get();
        return view('pages.overrides.index', compact('overrides'));
    }

    public function create()
    {
        $products = Product::all();
        $ingredients = Ingredient::all();
        return view('pages.overrides.create', compact('products', 'ingredients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'custom_quantity' => 'nullable|numeric|min:0',
            'is_removed' => 'boolean',
            'active' => 'boolean',
        ]);

        ProductRecipeOverride::create($validated);

        return redirect()->route('overrides.index')->with('success', 'تم إضافة التعديل بنجاح');
    }

    public function edit(ProductRecipeOverride $override)
    {
        $products = Product::all();
        $ingredients = Ingredient::all();
        return view('pages.overrides.edit', compact('override', 'products', 'ingredients'));
    }

    public function update(Request $request, ProductRecipeOverride $override)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'custom_quantity' => 'nullable|numeric|min:0',
            'is_removed' => 'boolean',
            'active' => 'boolean',
        ]);

        $override->update($validated);

        return redirect()->route('overrides.index')->with('success', 'تم تحديث التعديل بنجاح');
    }

    public function destroy(ProductRecipeOverride $override)
    {
        $override->delete();
        return redirect()->route('overrides.index')->with('success', 'تم حذف التعديل بنجاح');
    }
}
