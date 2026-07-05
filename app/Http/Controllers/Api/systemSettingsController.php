<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvicePrinterSettings;
use App\Models\SectionPrinterSetting;
use App\Models\sectionUserSettings;
use App\Models\SubSection;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;

class systemSettingsController extends Controller
{




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

    public function sectionUserSettings(Request $request)
    {

        $setting = sectionUserSettings::where('user_id', $request->user_id ?? auth()->user()->id)->first();

        if(!$setting)
            {
                return response()->json([
                    'settings not setting'
                ]);
            }


        return response()->json([
            'message' => 'Section user settings created successfully.',
            'data' => [
                'allowdSectionsId' => $setting->allowdSections,
                'seenSectionsId' => $setting->seenSections,
                'sectionPrinters' => $setting->sectionPrinter->map(function ($sectionPrinter) {
                    return [
                        'printerName' => $sectionPrinter->printer_name,
                        'sectionName' => $sectionPrinter->section_name,
                        'sectionId' => $sectionPrinter->sub_sections_id
                    ];
                }),
            ]
        ], 201);

    }

    public function updateSectionUserSettings(Request $request)
    {
        $request->validate([
            'allowdSections' => 'nullable|array',
            'seenSections' => 'nullable|array',
            'user_id' => 'required'
        ]);

        $setting = sectionUserSettings::updateOrCreate(['user_id' => $request->user_id],[
            'allowdSections' => $request->allowdSections,
            'seenSections' => $request->seenSections,

        ]);

        foreach ($request->sectionPrinters as $printer) {
            $setting->sectionPrinter()->updateOrCreate(
                [
                    'sub_sections_id' => $printer['sub_sections_id'],
                ],
                [
                    'printer_name' => $printer['printer_name'],
                    'section_name' => $printer['section_name']
                ]
            );
        }

        return response()->json([
            'message' => 'Section user settings created successfully.',
            'data' => [
                'allowdSectionsId' => $setting->allowdSections,
                'seenSectionsId' => $setting->seenSections,
                'sectionPrinters' => $setting->sectionPrinter->map(function($sectionPrinter){
                    return [
                        'printerName' => $sectionPrinter->printer_name,
                        'sectionName' => $sectionPrinter->section_name,
                        'sectionId' => $sectionPrinter->sub_sections_id
                    ];
                }),
            ]
        ], 201);
    }

    public function invicePrintersUserSettings(Request $request)
    {


        $requestedSettings = InvicePrinterSettings::where('user_id', $request->user_id ? $request->user_id : auth()->user()->id)->where('type', 'user')->get();
        if ($requestedSettings->isEmpty()) {
            return response()->json(['message' => 'No printer settings found.'], 404);
        }
        return response()->json([
            'message' => 'Invoice printer settings retrieved successfully.',
            'data' => $requestedSettings->map(function ($setting) {
                return [
                    'id' => $setting->id,
                    'cashierPrinterName' => $setting->cashierPrinterName,
                    'ًAllowSaveWithoutPrint' => $setting->allowSaveWithoutPrint,
                    'barcodePrinterName' => $setting->barcodePrinterName,
                    'reportPrinterName' => $setting->reportPrinterName,
                    'type' => $setting->type,
                    'user_id' => $setting->user_id,
                    'invicePrinters' => $setting->invicePrinters->map(function ($printer) {
                        return [
                            'id' => $printer->id,
                            'formName' => $printer->formName,
                            'printerName' => $printer->printerName,
                            'permssionName' => $printer->permssionName,
                            'numOfCopies' => $printer->numOfCopies,
                            'isActive' => $printer->isActive,
                        ];
                    }),
                ];
            })
        ]);
    }
    public function invicePrintersSystemSettings()
    {

        $requestedSettings = InvicePrinterSettings::where('type','system')->get();
        if ($requestedSettings->isEmpty()) {
            return response()->json(['message' => 'No printer settings found.'], 404);
        }
        return response()->json([
            'message' => 'Invoice printer settings retrieved successfully.',
            'data' => $requestedSettings->map(function ($setting) {
                return [
                    'id' => $setting->id,
                    'cashierPrinterName' => $setting->cashierPrinterName,
                    'allowSaveWithoutPrint' => $setting->allowSaveWithoutPrint,
                    'barcodePrinterName' => $setting->barcodePrinterName,
                    'reportPrinterName' => $setting->reportPrinterName,
                    'type' => $setting->type,
                    'user_id' => $setting->user_id,
                    'invicePrinters' => $setting->invicePrinters->map(function ($printer) {
                        return [
                            'id' => $printer->id,
                            'formName' => $printer->formName,
                            'printerName' => $printer->printerName,
                            'permssionName' => $printer->permssionName,
                            'numOfCopies' => $printer->numOfCopies,
                            'isActive' => $printer->isActive,
                        ];
                    }),
                ];
            })
        ]);
    }

    public function updateInvicePrintersSettings(Request $request)
    {


        $validatedData = $request->validate([
            'invoicePrinterSettings' => 'sometimes|array',
            'invoicePrinterSettings.*.formName' => 'required|string|max:255',
            'invoicePrinterSettings.*.printerName' => 'required|string|max:255',
            'invoicePrinterSettings.*.permssionName' => 'required|string|max:255',
            'invoicePrinterSettings.*.numOfCopies' => 'nullable|integer|min:1',
            'invoicePrinterSettings.*.isActive' => 'required|boolean',

            'cashierPrinterName' => 'nullable|string|max:255',
            'AllowSaveWithoutPrint' => 'nullable|boolean',
            'barcodePrinterName' => 'nullable|string|max:255',
            'reportPrinterName' => 'nullable|string|max:255',
            'type' => 'required|string|in:user,system',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);


        if ($validatedData['type'] == "system") {
            $invicePrinterSettings = InvicePrinterSettings::where('type', 'system')->first();
            $validatedData['user_id'] = auth()->user()->id;
        } else {
            $invicePrinterSettings = InvicePrinterSettings::where('user_id', $validatedData['user_id'])->first();
        }

        if(!$invicePrinterSettings)
        {

            $invicePrinterSettings = InvicePrinterSettings::create([
                'cashierPrinterName' => $validatedData['cashierPrinterName'] ?? $invicePrinterSettings->cashierPrinterName ?? null,
                'allowSaveWithoutPrint' => $validatedData['allowSaveWithoutPrint'] ?? $invicePrinterSettings->allowSaveWithoutPrint ?? 0,
                'barcodePrinterName' => $validatedData['barcodePrinterName'] ?? $invicePrinterSettings->barcodePrinterName ?? null,
                'reportPrinterName' => $validatedData['reportPrinterName'] ?? $invicePrinterSettings->reportPrinterName ?? null,
                'type' => $validatedData['type'] ?? $invicePrinterSettings->type,
                'user_id' => $validatedData['user_id'] ?? $invicePrinterSettings->user_id
            ]);
        }else
        {
            $invicePrinterSettings->update([
                'cashierPrinterName' => $validatedData['cashierPrinterName'] ?? $invicePrinterSettings->cashierPrinterName,
                'allowSaveWithoutPrint' => $validatedData['AllowSaveWithoutPrint'] ?? $invicePrinterSettings->allowSaveWithoutPrint,
                'barcodePrinterName' => $validatedData['barcodePrinterName'] ?? $invicePrinterSettings->barcodePrinterName,
                'reportPrinterName' => $validatedData['reportPrinterName'] ?? $invicePrinterSettings->reportPrinterName,
                'type' => $validatedData['type'] ?? $invicePrinterSettings->type,
                'user_id' => $validatedData['user_id'] ?? $invicePrinterSettings->user_id
            ]);
        }



        if(isset($validatedData['invoicePrinterSettings']))
        {
            foreach ($validatedData['invoicePrinterSettings'] as $printerSetting) {
                $invicePrinterSettings->invicePrinters()->updateOrCreate(
                    ['permssionName' => $printerSetting['permssionName']],
                    [
                        'formName' => $printerSetting['formName'],
                        'printerName' => $printerSetting['printerName'],
                        'numOfCopies' => $printerSetting['numOfCopies'] ?? null,
                        'isActive' => $printerSetting['isActive'],
                    ]
                );
            }
        }


        return response()->json(['message' => 'Invoice printer settings updated successfully.']);
    }


    public function getWarehouses()
    {
        $warehouses = Warehouse::all(['id', 'name', 'is_active', 'is_default']);
        return response()->json($warehouses);
    }

    public function userWarehouseSettings(Request $request)
    {
        $user = User::find($request->user_id ?? auth()->user()->id);
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
            $user->warehouses()->attach(
                $warehouseData['id'],
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
