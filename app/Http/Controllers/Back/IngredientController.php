<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\MeasurementUnit;
use App\Models\Unit;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::with('MeasurementUnit')->get();
        return view('pages.ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        $units = MeasurementUnit::get();

        return view('pages.ingredients.create', compact('units'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'measurement_unit_id' => 'required|exists:measurement_units,id',
            'is_active' => 'boolean',
            "quantity_in_stock" => 'nullable|numeric'
        ]);

        Ingredient::create($validated);
        return redirect()->route('ingredients.index')->with('success', 'تم إضافة المكون بنجاح');
    }

    public function edit(Ingredient $ingredient)
    {
        $units = MeasurementUnit::get();
        return view('pages.ingredients.update', compact('ingredient', 'units'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'measurement_unit_id' => 'required|exists:measurement_units,id',
            'is_active' => 'boolean',
            "quantity_in_stock" => 'nullable|numeric'
        ]);

        // نخزن الكمية في المخزن = conversion_factor للوحدة
        $ingredient->update($validated);

        return redirect()->route('ingredients.index')->with('success', 'تم تحديث المكون بنجاح');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->route('ingredients.index')->with('success', 'تم حذف المكون بنجاح');
    }
}
