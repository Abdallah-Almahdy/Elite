<?php

namespace App\Http\Controllers\Back;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::with('primaryAddress')->get();
        return view('pages.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string',
            'account_number' => 'nullable|string|max:50',
            'active' => 'boolean',
            'notes' => 'nullable|string',
            'phones' => 'array',
            'phones.*.number' => 'required|string',
            'phones.*.type' => 'required|string',
            'phones.*.is_primary' => 'boolean',
            'addresses' => 'array',
            'addresses.*.street' => 'required|string',
            'addresses.*.city' => 'required|string',
            'addresses.*.state' => 'nullable|string',
            'addresses.*.postal_code' => 'required|string',
            'addresses.*.type' => 'required|string',
            'addresses.*.is_primary' => 'boolean',
        ]);

        $supplier = Supplier::create($validated);

        // Save phones
        if ($request->has('phones')) {
            foreach ($request->phones as $phoneData) {
                $supplier->phones()->create($phoneData);
            }
        }

        // Save addresses
        if ($request->has('addresses')) {
            foreach ($request->addresses as $addressData) {
                $supplier->addresses()->create($addressData);
            }
        }

        return redirect()->route('suppliers.index')
            ->with('success', 'تم إضافة المورد بنجاح');
    }

    public function create()
    {
        return view('pages.suppliers.create');
    }
    public function show(Supplier $supplier)
    {
        return view('pages.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('pages.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string',
            'account_number' => 'nullable|string|max:50',
            'active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'تم تحديث المورد بنجاح');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'تم حذف المورد بنجاح');
    }
}
