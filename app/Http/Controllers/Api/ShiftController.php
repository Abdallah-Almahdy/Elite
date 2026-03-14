<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function close(Request $request)
    {

        $user = User::find(1);
        $shift = $user->shifts()->where('status', 'open')->latest()->first();
        if (!$shift) {
            return response()->json(['message' => 'لا يوجد ورديات مفتوحة'], 404);

            $request->validate([
                'end_cash' => 'required|numeric|min:0',
            ]);
            $shift->update([
                'status' => 'closed',
                'end_time' => now(),
                'end_cash' => $request->input('end_cash'),
            ]);
            return response()->json(['message' => 'تم إغلاق الوردية بنجاح']);
        }
    }
}
