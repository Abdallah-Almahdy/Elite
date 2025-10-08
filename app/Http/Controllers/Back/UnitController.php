<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('baseUnit')->get();
        return view('pages.units.index', compact('units'));
    }

    public function create()
    {
        $baseUnits = Unit::where('is_base_unit', true)->get();
        return view('pages.units.create', compact('baseUnits'));
    }

   public function store(Request $request)
{
    // القواعد حسب نوع الوحدة
    $rules = [
        'name'         => 'required|string|max:255',
        'short_code'   => 'required|string|max:10',
        'is_base_unit' => 'required|in:0,1',
        'type'         => 'required|in:weight,volume,count',
        'active'       => 'boolean',
    ];

    if (! $request->is_base_unit) {
        $rules['unit_code']         = 'required|in:kg,g,mg,ton,l,ml,piece';
        $rules['conversion_factor'] = 'required|numeric|min:1';
        $rules['base_unit_id']      = 'required|exists:units,id';
    }

    $validated = $request->validate($rules);

    // لو أساسية
    if ($request->is_base_unit) {
        $validated['conversion_factor'] = 1;
        $validated['base_unit_id'] = null;
        $validated['unit_code'] = null;
    }

    $unit = Unit::create($validated);

    // خريطة التحويلات التلقائية
    $autoMap = [
        'kg'  => ['unit_code' => 'g',  'name' => 'جرام',     'factor' => 1000],
        'g'   => ['unit_code' => 'mg', 'name' => 'مليجرام',  'factor' => 1000],
        'ton' => ['unit_code' => 'kg', 'name' => 'كيلو',     'factor' => 1000],
        'l'   => ['unit_code' => 'ml', 'name' => 'مليلتر',   'factor' => 1000],
    ];

    if (! $unit->is_base_unit && $unit->unit_code && isset($autoMap[$unit->unit_code])) {
        $child = $autoMap[$unit->unit_code];

        $exists = Unit::where('base_unit_id', $unit->id)
                      ->where('unit_code', $child['unit_code'])
                      ->exists();

        if (! $exists) {
            Unit::create([
                'name'              => $child['name'],
                'short_code'        => $unit->short_code,
                'unit_code'         => $child['unit_code'],
                'is_base_unit'      => false,
                'conversion_factor' => $unit->conversion_factor * $child['factor'],
                'base_unit_id'      => $unit->id,
                'active'            => true,
                'type'              => $unit->type,
            ]);
        }
    }

    return redirect()->route('units.index')
        ->with('success', 'تم إضافة الوحدة بنجاح');
}


    public function show(Unit $unit)
    {
        $unit->load('baseUnit', 'derivedUnits');
        return view('pages.units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        $baseUnits = Unit::where('is_base_unit', true)
            ->where('id', '!=', $unit->id)
            ->get();

        return view('pages.units.edit', compact('unit', 'baseUnits'));
    }

  public function update(Request $request, Unit $unit)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'short_code' => 'required|string|max:10',
        'is_base_unit' => 'sometimes|boolean',
        'conversion_factor' => 'required_if:is_base_unit,0|numeric|min:0.0001',
        'base_unit_id' => 'required_if:is_base_unit,0|exists:units,id',
        'active' => 'boolean',
    ]);

    $validated['is_base_unit'] = isset($validated['is_base_unit']) ? (bool)$validated['is_base_unit'] : $unit->is_base_unit;

    if ($validated['is_base_unit']) {
        $validated['conversion_factor'] = 1;
        $validated['base_unit_id'] = null;
    }

    $unit->update($validated);

    // تحديث الوحدات المشتقة (Derived Units)
    $autoMap = [
        'kg'  => ['unit_code' => 'g',  'name' => 'جرام',      'factor' => 1000],
        'g'   => ['unit_code' => 'mg', 'name' => 'مليجرام',   'factor' => 1000],
        'ton' => ['unit_code' => 'kg', 'name' => 'كيلو',      'factor' => 1000],
        'l'   => ['unit_code' => 'ml', 'name' => 'مليلتر',    'factor' => 1000],
    ];

    if (! $unit->is_base_unit && $unit->unit_code && isset($autoMap[$unit->unit_code])) {
        $child = $autoMap[$unit->unit_code];

        // نحدث الوحدة المشتقة لو موجودة بالفعل بنفس base_unit_id
        $derived = Unit::where('base_unit_id', $unit->id)
                       ->where('unit_code', $child['unit_code'])
                       ->first();

        if ($derived) {
            $derived->update([
                'name' => $child['name'],
                'short_code' => $unit->short_code,
                'conversion_factor' => $unit->conversion_factor * $child['factor'],
                'active' => $unit->active,
                'type' => $unit->type,
            ]);
        } else {
            // إذا ما موجودش، نعمله
            Unit::create([
                'name' => $child['name'],
                'short_code' => $unit->short_code,
                'unit_code' => $child['unit_code'],
                'is_base_unit' => false,
                'conversion_factor' => $unit->conversion_factor * $child['factor'],
                'base_unit_id' => $unit->id, // <--- هنا نربطه بالـ id للكيلو
                'active' => true,
                'type' => $unit->type,
            ]);
        }
    }

    return redirect()->route('units.index')
                     ->with('success', 'تم تحديث الوحدة والوحدات المشتقة بنجاح');
}



    public function destroy(Unit $unit)
    {
        // Check if this unit is used as a base unit for other units
        if ($unit->derivedUnits()->count() > 0) {
            return redirect()->route('units.index')
                ->with('error', 'لا يمكن حذف هذه الوحدة لأنها مستخدمة كوحدة أساسية لوحدات أخرى');
        }

        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'تم حذف الوحدة بنجاح');
    }
}
