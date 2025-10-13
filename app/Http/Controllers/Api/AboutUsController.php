<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AboutUsResource;
use App\Models\AboutUS;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = AboutUS::with('images')->first();
        //abdallah hiiii

        if (!$about) {
            return response()->json([
                'status' => false,
                'message' => 'لا توجد بيانات متاحة حالياً'
            ], 404);
        }

        return new AboutUsResource($about);
    }
}
