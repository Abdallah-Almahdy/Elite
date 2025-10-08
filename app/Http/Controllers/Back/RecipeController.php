<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Unit;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with(['ingredients', 'product'])->get();
        return view('pages.recipes.index', compact('recipes'));
    }

    public function create()
    {
        $products = Product::where('uses_recipe', true)->get();
        $ingredients = Ingredient::active()->get();
        $baseRecipes = Recipe::where('is_base', true)->get();
        $units = Unit::active()->get();

        return view('pages.recipes.create', compact('products','ingredients','baseRecipes','units'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'is_base' => 'nullable|boolean',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity_needed' => 'required|numeric|min:0.01',
            'base_recipe_id' => 'nullable|exists:recipes,id',
            'is_active' => 'boolean',
        ];

        if (!$request->boolean('is_base')) {
            $rules['product_id'] = 'required|exists:products,id';
        } else {
            $rules['product_id'] = 'nullable';
        }

        $validated = $request->validate($rules);

        $recipe = Recipe::create([
            'name' => $validated['name'],
            'product_id' => $validated['product_id'] ?? null,
            'is_base' => $request->boolean('is_base'),
            'base_recipe_id' => $validated['base_recipe_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // تجهيز المكونات للفورم
        $ingredientsToSync = [];
        if (!empty($validated['ingredients'])) {
            foreach ($validated['ingredients'] as $ingredient) {
                $ingredientsToSync[$ingredient['id']] = [
                    'quantity_needed' => $ingredient['quantity_needed']
                ];
            }
        }

        // لو فيه وصفة أساسية
        if (!empty($validated['base_recipe_id'])) {
            $baseRecipe = Recipe::find($validated['base_recipe_id']);
            foreach ($baseRecipe->ingredients as $ingredient) {
                $ingredientsToSync[$ingredient->id] = [
                    'quantity_needed' => $ingredient->pivot->quantity_needed
                ];
            }
        }

        // sync بدال attach علشان يمنع التكرار
        $recipe->ingredients()->sync($ingredientsToSync);

        return redirect()->route('recipes.index')->with('success', 'تم إضافة الوصفة بنجاح');
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['ingredients', 'product', 'baseRecipe']);
        return view('pages.recipes.show', compact('recipe'));
    }

    public function edit(Recipe $recipe)
    {
        $ingredients = Ingredient::active()->get();
        $baseRecipes = Recipe::where('is_base', true)->get();
        $units = Unit::active()->get();
        $products = Product::where('uses_recipe', true)->get();

        $recipe->load('ingredients');

        return view('pages.recipes.update', compact('recipe','ingredients','baseRecipes','units','products'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $isBaseRecipe = $request->boolean('is_base');
        $productRule = $isBaseRecipe ? 'nullable' : 'required';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => $productRule . '|exists:products,id',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity_needed' => 'required|numeric|min:0.01',
            'base_recipe_id' => 'nullable|exists:recipes,id',
            'is_active' => 'boolean',
        ]);

        $recipe->update([
            'name' => $validated['name'],
            'product_id' => $isBaseRecipe ? null : $validated['product_id'] ?? null,
            'is_base' => $isBaseRecipe,
            'base_recipe_id' => $validated['base_recipe_id'] ?? null,
            'is_active' => $validated['is_active'] ?? $recipe->is_active,
        ]);

        // تجهيز المكونات
        $ingredientsToSync = [];
        if (!empty($validated['ingredients'])) {
            foreach ($validated['ingredients'] as $ingredient) {
                $ingredientsToSync[$ingredient['id']] = [
                    'quantity_needed' => $ingredient['quantity_needed']
                ];
            }
        }

        // لو فيه وصفة أساسية
        if (!empty($validated['base_recipe_id'])) {
            $baseRecipe = Recipe::find($validated['base_recipe_id']);
            foreach ($baseRecipe->ingredients as $ingredient) {
                $ingredientsToSync[$ingredient->id] = [
                    'quantity_needed' => $ingredient->pivot->quantity_needed
                ];
            }
        }

        // تحديث المكونات بشكل نظيف
        $recipe->ingredients()->sync($ingredientsToSync);

        return redirect()->route('recipes.index')->with('success','تم تحديث الوصفة بنجاح');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->ingredients()->detach();
        $recipe->delete();
        return redirect()->route('recipes.index')->with('success','تم حذف الوصفة بنجاح');
    }
}
