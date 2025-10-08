<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Printer;
use Illuminate\Http\Request;

class PrintersController extends Controller
{
    public function index()
    {
        $printers = Printer::all();
        return view('pages.printers.index', [
            'printers' => $printers
        ]);
    }

    public function create()
    {
        return view('pages.printers.create');
    }
    public function store(Request $request)
    {

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        // Create the printer
        $printer = Printer::create($validated);

        // Optionally redirect with success message
        return redirect()
            ->route('printers.index')
            ->with('success', 'تم إضافة الطابعة بنجاح');
    }
}
