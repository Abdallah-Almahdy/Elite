<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('is_admin', 1)->get();
        $permissions = Permission::all();
        $selectedUser = $request->user_id ? User::find($request->user_id) : null;

        return view('pages.permissions.index', compact('users', 'permissions', 'selectedUser'));
    }

    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

   
        // syncPermissions يستبدل الصلاحيات القديمة با  لجديدة
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->back()->with('success', 'تم تحديث صلاحيات المستخدم بنجاح ✅');
    }
}
