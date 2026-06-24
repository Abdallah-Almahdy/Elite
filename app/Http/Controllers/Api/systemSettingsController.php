<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvicePrinterSettings;
use App\Models\SectionPrinterSetting;
use App\Models\SubSection;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;

class systemSettingsController extends Controller
{
    // public function getSystemSettings()
    // {
    //     $settings = [
    //         'invoiceConfig' => auth()->user()->inviceConfig,
    //         'userConfig' => auth()->user()->userConfig,
    //         'warehouses' => auth()->user()->warahouses,
    //     ];

    //     return response()->json($settings);
    // }

    public function userPrinterSettings()
    {
        $userConfig = User::find(1)->userConfig;

        if (!$userConfig) {
            return response()->json(['message' => 'User configuration not found.'], 404);
        }

        return response()->json($userConfig);
    }

    public function updateUserPrinterSettings(Request $request)
    {
        $user = User::find(1);

        $validatedData = $request->validate([
            'CashierPrinterName' => 'nullable|string|max:255',
            'AllowSaveWithoutPrint' => 'nullable|boolean',
            'barcodePrinterName' => 'nullable|string|max:255',
            'reportPrinterName' => 'nullable|string|max:255',
        ]);

        $user->userConfig()->updateOrCreate(
            ['user_id' => $user->id],
            $validatedData
        );


        return response()->json(['message' => 'User printer settings updated successfully.', 'data' => $user->userConfig]);
    }

    public function sectionsPrinterSettings()
    {
        $sections = SubSection::all();

        return response()->json([
            "message" => "Sections printer settings retrieved successfully.",
            "data" => $sections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                    'printerSettings' => $section->sectionPrinterSettings->map(function ($setting) {
                        return [
                            'id' => $setting->id,
                            'printerName' => $setting->printer_name,
                        ];
                    }),
                ];
            })
        ]);
    }

    public function updateSectionPrinterSettings(Request $request)
    {

        $validatedData = $request->validate([
            'data' => 'required|array',
            'data.*.section_id' => 'required|exists:sub_sections,id',
            'data.*.printer_name' => 'required|string|max:255',


        ]);



        foreach ($validatedData['data'] as $sectionData) {
            $section = SubSection::findOrFail($sectionData['section_id']);
            if ($section) {

                SectionPrinterSetting::updateOrCreate(
                    ['sub_sections_id' => $section->id],
                    ['printer_name' => $sectionData['printer_name'], 'section_name' => $section->name],

                );
            }
        }


        return response()->json([
            'message' => 'Section printer settings updated successfully.',
            'data' =>
            subSection::with('sectionPrinterSettings')->get()->map(function ($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                    'printerSettings' => $section->sectionPrinterSettings->map(function ($setting) {
                        return [
                            'id' => $setting->id,
                            'printerName' => $setting->printer_name,
                        ];
                    }),
                ];
            })
        ]);
    }


    public function InvicePrinterSettings()
    {
        $requestedSettings = InvicePrinterSettings::all();
        if ($requestedSettings->isEmpty()) {
            return response()->json(['message' => 'No printer settings found.'], 404);
        }
        return response()->json($requestedSettings);
    }

    public function updateInvicePrinterSettings(Request $request)
    {
        $validatedData = $request->validate([
            'data' => 'required|array',
            'data.*.formName' => 'required|string|max:255',
            'data.*.printerName' => 'required|string|max:255',
            'data.*.permssionName' => 'required|string|max:255',
            'data.*.numOfCopies' => 'nullable|integer|min:1',
            'data.*.isActive' => 'required|boolean',
        ]);

        foreach ($validatedData['data'] as $settingData) {
            InvicePrinterSettings::updateOrCreate(
                ['permssionName' => $settingData['permssionName']],
                [
                    'printerName' => $settingData['printerName'],
                    'formName' => $settingData['formName'],
                    'numOfCopies' => $settingData['numOfCopies'] ?? null,
                    'isActive' => $settingData['isActive'],
                ]
            );
        }

        return response()->json(['message' => 'Invoice printer settings updated successfully.']);
    }


    public function getWarehouse()
    {
        $warehouses = Warehouse::all(['id', 'name', 'is_active', 'is_default']);
        return response()->json($warehouses);
    }

    public function userWarehouseSettings()
    {
        $user = User::find(1); // Replace with the actual user ID or use auth()->user() if you have authentication set up
        $warehouses = $user->warehouses()->get(['warehouses.id', 'warehouses.name', 'warehouse_permissions.is_default', 'warehouse_permissions.warehouse_name']);
        return response()->json($warehouses);
    }

    public function updateUserWarehouseSettings(Request $request)
    {
        // Replace with the actual user ID or use auth()->user() if you have authentication set up

        $validatedData = $request->validate([
            'warehouses' => 'required|array',
            'warehouses.*.id' => 'required|exists:warehouses,id',
            'warehouses.*.is_default' => 'required|boolean',
            'warehouses.*.warehouse_name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);

        // Detach all existing warehouses
        $user->warehouses()->detach();

        // Attach the new warehouses with pivot data
        foreach ($validatedData['warehouses'] as $warehouseData) {
            $user->warehouses()->attach($warehouseData['id'],
            ['is_default' => $warehouseData['is_default'], 'warehouse_name' => $warehouseData['warehouse_name']],
          );
        }

        return response()->json(['message' => 'User warehouse settings updated successfully.']);
    }


    public function setDefaultWarehouse(Request $request)
    {
        $validatedData = $request->validate([

            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $warehouses = Warehouse::all();
        foreach ($warehouses as $warehouse) {
          if ($warehouse->id == $validatedData['warehouse_id']) {
                $warehouse->is_default = true;
            } else {
                $warehouse->is_default = false;
            }
            $warehouse->save();
        }

        return response()->json(['message' => 'Default warehouse set successfully.']);
    }

}
