<?php

namespace App\Http\Controllers\Back;

use App\Models\User;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\order;

class StaticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    // public function users()
    // {

    //     $users = User::with('customerInfo')->where('id', '>', 50)->paginate(25);

    //     return view('admin.statics.users', ['users' => $users]);
    // }


    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
