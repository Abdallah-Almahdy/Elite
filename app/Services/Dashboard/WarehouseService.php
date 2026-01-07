<?php

namespace App\Services\Dashboard;

use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class WarehouseService
{



    public function create($request)
    {
        return DB::transaction(function () use ($request) {
            $warehouse = Warehouse::create([
                'name' => $request->name,
                'code' => $request->code,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'is_active' => $request->is_active ?? true,
                'is_default' => $request->is_default ?? false,
            ]);

            if (isset($request->is_default) && $request->is_default) {
                // Set other warehouses to not default
                Warehouse::where('id', '!=', $warehouse->id)->update(['is_default' => false]);
            }

            // Create address if line1 provided
            if ($request->filled('line1')) {
                $warehouse->addresses()->create([
                    'type' => 'primary',
                    'line1' => $request->line1,
                    'line2' => $request->line2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country ?? 'مصر',
                    'is_primary' => true,
                ]);
            }

            // Create phone if phone_number provided
            if ($request->filled('phone_number')) {
                $warehouse->phones()->create([
                    'number' => $request->phone_number,
                    'type' => $request->phone_type ?? 'mobile',
                    'is_primary' => true,
                ]);
            }
        });
    }
}
