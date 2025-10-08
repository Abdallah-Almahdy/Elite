<?php

namespace App\Http\Controllers\Back;

use App\Models\kitchen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KitchensController extends Controller
{
    public function index()
    {

        $kitchens = kitchen::all();
        return view('pages.kitchens.index', [
            'kitchens' => $kitchens
        ]);
    }

    public function create()
    {
        return view('pages.kitchens.create');
    }

    public function show(kitchen $kitchen)
    {


        return view('pages.kitchens.show', [
            'kitchen' => $kitchen
        ]);
    }

    public function store(Request $request)
    {

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        // Create the printer
        $kitchen = kitchen::create($validated);

        $kitchen->Printers()->attach([1, 2, 3, 5, 6, 7]);
        $kitchen->subSections()->attach([8, 14, 15, 16]);


        // Optionally redirect with success message
        return redirect()
            ->route('kitchens.index')
            ->with('success', 'تم إضافة المطبخ بنجاح');
    }
}
