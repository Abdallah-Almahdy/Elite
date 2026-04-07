<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class permissionsController extends Controller
{

    public function userPermissions()
    {
        $user = User::find(1); // المستخدم

        // جلب كل البرمشنز
        $allPermissions = Permission::pluck('name'); // مجموعة أسماء البرمشنز

        // إنشاء مصفوفة key => true/false
        $permissionsArray = $allPermissions->mapWithKeys(function ($permission) use ($user) {
            return [$permission => $user->hasPermissionTo($permission)];
        });

        // إرجاع JSON
        return response()->json([
            'permissions' => $permissionsArray
        ]);
    }

    public function edit(Request $request)
    {
        // 1️⃣ Validate
        $request->validate([
            'permissions' => 'required|array',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // 2️⃣ جلب المستخدم
        $user = User::findOrFail($request->user_id); // استخدم findOrFail بدل find للتأكد إنه موجود

        // 3️⃣ أخذ فقط البرمشنز اللي true
        // array_filter يحذف كل false, null, 0
        $permissions = array_keys(array_filter($request->permissions));

        // 4️⃣ تحديث البرمشنز
        $user->syncPermissions($permissions);

        // 5️⃣ Optional: إرجاع كل البرمشنز بعد التحديث مع true/false
        $allPermissions = \Spatie\Permission\Models\Permission::pluck('name');
        $permissionsArray = $allPermissions->mapWithKeys(function ($permission) use ($user) {
            return [$permission => $user->hasPermissionTo($permission)];
        });

        // 6️⃣ Response JSON
        return response()->json([
            'message' => 'Permissions updated successfully.',
            'permissions' => $permissionsArray,
        ]);
    }
}
