<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Representative;

class RepresentativeController extends Controller
{
    public function index()
    {
        $representatives = Representative::all();
        return view('pages.representatives.index', compact('representatives'));
    }

    public function create()
    {
        return view('pages.representatives.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $representative = Representative::create($validated);

        return redirect()->route('representatives.index')
            ->with('success', 'تم إضافة المندوب بنجاح');
    }

    public function show(Representative $representative)
    {
        return view('pages.representatives.show', compact('representative'));
    }

    public function edit(Representative $representative)
    {
        return view('pages.representatives.edit', compact('representative'));
    }

    public function update(Request $request, Representative $representative)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $representative->update($validated);

        return redirect()->route('representatives.index')
            ->with('success', 'تم تحديث المندوب بنجاح');
    }

    public function destroy(Representative $representative)
    {
        $representative->delete();

        return redirect()->route('representatives.index')
            ->with('success', 'تم حذف المندوب بنجاح');
    }
}
