<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{

    public function index()
    {
        $units = Unit::paginate(5);
        return view('pages.units.index', compact('units'));
    }
    public function create()
    {
        return view('pages.units.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    public function edit(Unit $unit)
    {
        return view('pages.units.edit', compact('unit'));
    }
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}
