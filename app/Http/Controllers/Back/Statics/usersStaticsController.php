<?php

namespace App\Http\Controllers\Back\Statics;

use App\Models\User;
use App\Models\order;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersStaticsController extends Controller
{
    public function users(Request $request)
    {
        $query = User::with('customerInfo')->where('id', '>', 50);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->paginate(25)->appends(['search' => $search]);

        return view('admin.statics.users.users', compact('users', 'search'));
    }
    public function userInfo($id)
    {
        $user = User::with('customerInfo')->find($id);
        $addressCountryName = $user->customerInfo->addressCountry
            ? Delivery::where('id', $user->customerInfo->addressCountry)->value('name')
            : null;

        $addressCountry2Name = $user->customerInfo->addressCountry2
            ? Delivery::where('id', $user->customerInfo->addressCountry2)->value('name')
            : null;

        $userOrders = order::where('user_id', $id)->get();
        foreach ($userOrders as $order) {
            $order->userData = User::find($id);
        }
        return view('admin.statics.users.userInfo', [
            'user' => $user,
            'addressCountryName' => $addressCountryName,
            'addressCountry2Name' => $addressCountry2Name,
            'userOrders' => $userOrders,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function editUserInfo($id)
    {
        $user = User::with('customerInfo')->find($id);
        // var_dump($user);



        // Handle address country names

        $addressCountryName = $user->customerInfo->addressCountry
            ? Delivery::where('id', $user->customerInfo->addressCountry)->value('name')
            : null;

        $addressCountry2Name = $user->customerInfo->addressCountry2
            ? Delivery::where('id', $user->customerInfo->addressCountry2)->value('name')
            : null;


        return view('admin.statics.users.editUserInfo', [
            'user' => $user,
            'addressCountryName' => $addressCountryName,
            'addressCountry2Name' => $addressCountry2Name,
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function updateUserInfo($id, Request $request)
    {

        $user = User::find($id);
        $user->update($request->all());
        return redirect()->route('users.show', $id);
    }
    public function deleteUser($id)
    {

        $user = User::find($id);
        $user->delete();
        return redirect()->route('users');
    }
}
