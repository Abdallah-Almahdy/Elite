<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config;

class ConfigController extends Controller
{

    public function update(Request $request)
    {

        $config = Config::instance();
        return view('configs.edit',compact('config'));
    }

    public function edit(Request $request)
    {
        $request->validate([
            'color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/']
        ]);

        $config = Config::instance();


        $config->update([
            'min_supported_version' => $request->min_supported_version,
            'exact_blocked_version' => $request->exact_blocked_version,
            'maintenance_mode' =>  $request->maintenance_mode ?? 0,
            'maintenance_message' => $request->maintenance_message,
            'color' => $request->color ,
        ]);


        return redirect()->back()->with('success', 'Configuration updated successfully.');

    }



    public function check(Request $request)
    {
        $config = Config::latest()->first();

        $data = [

                'exact_blocked_version' => $config->exact_blocked_version,
                'min_supported_version' => $config->min_supported_version,
                'maintenance_mode' => (bool) $config->maintenance_mode,
                'maintenance_message' => $config->maintenance_message,
                'color' => $config->color
                // 'blocked_versions' => $config->blocked_versions ?? [],

        ];

        return response()->json([
            'status' => 'ok',
            'message' => 'Config fetched successfully',
            'data' => $data
        ], 200);
    }



}
