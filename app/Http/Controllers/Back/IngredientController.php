<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Unit;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::with('unit')->get();
        return view('pages.ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        $units = Unit::Where('is_base_unit',0)->get();
        return view('pages.ingredients.create', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'is_active' => 'boolean'
        ]);

        // نخزن الكمية في المخزن = conversion_factor للوحدة
        $unit = Unit::findOrFail($validated['unit_id']);
        $validated['quantity_in_stock'] = $unit->conversion_factor;

        Ingredient::create($validated);

        return redirect()->route('ingredients.index')->with('success', 'تم إضافة المكون بنجاح');
    }

    public function edit(Ingredient $ingredient)
    {
        $units = Unit::active()->get();
        return view('pages.ingredients.update', compact('ingredient', 'units'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'is_active' => 'boolean'
        ]);

        // نخزن الكمية في المخزن = conversion_factor للوحدة
        $unit = Unit::findOrFail($validated['unit_id']);
        $validated['quantity_in_stock'] = $unit->conversion_factor;

        $ingredient->update($validated);

        return redirect()->route('ingredients.index')->with('success', 'تم تحديث المكون بنجاح');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->route('ingredients.index')->with('success', 'تم حذف المكون بنجاح');
    }
}
