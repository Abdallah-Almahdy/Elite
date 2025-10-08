<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config;

class ConfigController extends Controller
{
    public function check(Request $request)
    {
        $config = Config::latest()->first();

        $data = [

                'exact_blocked_version' => $config->exact_blocked_version,
                'min_supported_version' => $config->min_supported_version,
                'maintenance_mode' => (bool) $config->maintenance_mode,
                'maintenance_message' => $config->maintenance_message,
                // 'blocked_versions' => $config->blocked_versions ?? [],
            
        ];

        return response()->json([
            'status' => 'ok',
            'message' => 'Config fetched successfully',
            'data' => $data
        ], 200);
    }
}
