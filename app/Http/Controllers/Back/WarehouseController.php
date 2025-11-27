<?php

namespace App\Http\Controllers\Back;

use App\Models\Phone;
use App\Models\Address;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\WarehouseService;
use App\Http\Requests\Api\NewWarehouseRequest;

class WarehouseController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | IOC
    |--------------------------------------------------------------------------
    */

    protected $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    /*
    |--------------------------------------------------------------------------
    | Main methods
    |--------------------------------------------------------------------------
    */


    public function index()
    {
        $warehouses = Warehouse::with(['addresses', 'phones'])->latest()->get();
        return view(
            'pages.warehouses.index',
            [
                'warehouses' => $warehouses
            ]
        );
    }

    public function create()
    {
        return view('pages.warehouses.create');
    }

    public function store(Request $request)
    {
           
        try {
            $this->warehouseService->create($request);

            return redirect()
                ->route('warehouses.create')
                ->with('success', 'تم إنشاء المستودع بنجاح');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'فشل في إنشاء المستودع: ' . $e->getMessage());
        }
    }

    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['addresses', 'phones']);
        return view('pages.warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        $warehouse->load(['addresses', 'phones']);
        return view('pages.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('warehouses')->ignore($warehouse->id)],
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'is_active' => 'boolean',

            // Address fields
            'line1' => 'nullable|string|max:255',
            'line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:255',

            // Phone fields
            'phone_number' => 'nullable|string|max:20',
            'phone_type' => 'nullable|string|max:20',
        ]);

        // Update warehouse
        $warehouse->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Update or create primary address
        if (!empty($validated['line1'])) {
            $addressData = [
                'type' => 'primary',
                'line1' => $validated['line1'],
                'line2' => $validated['line2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'] ?? null,
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'] ?? 'السعودية',
                'is_primary' => true,
            ];

            if ($warehouse->primaryAddress()) {
                $warehouse->primaryAddress()->update($addressData);
            } else {
                $warehouse->addresses()->create($addressData);
            }
        }

        // Update or create primary phone
        if (!empty($validated['phone_number'])) {
            $phoneData = [
                'number' => $validated['phone_number'],
                'type' => $validated['phone_type'] ?? 'mobile',
                'is_primary' => true,
            ];

            if ($warehouse->primaryPhone()) {
                $warehouse->primaryPhone()->update($phoneData);
            } else {
                $warehouse->phones()->create($phoneData);
            }
        }

        return redirect()->route('warehouses.index')
            ->with('success', 'تم تحديث المستودع بنجاح');
    }

    public function destroy(Warehouse $warehouse)
    {
        // Delete related addresses and phones
        $warehouse->addresses()->delete();
        $warehouse->phones()->delete();

        // Delete warehouse
        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'تم حذف المستودع بنجاح');
    }
}
