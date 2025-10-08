<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\rateResource;
use App\Models\Rating;
//use App\Models\User;
use App\Models\CustomerInfo;
use Illuminate\Http\Request;

class RatesController extends Controller
{
    public function rate(Request $request)
    {
        $request->validate([
            'rate'    => 'required|integer|min:1|max:5',
            'message' => 'nullable|string|max:1000',
        ]);

        $item = Rating::create(
            [
                'raitnum'       => $request->rate,
                'reviewMessage' => $request->message,
                'uid'           => $request->user()->id,
            ]
        );

        if ($item) {

            return response()->json([
                'data' => $item,
                'message' => 'done, added successfully',
                'code' => '200'
            ], 200);
        } else {

            return response()->json([
                'message' => 'faild, aleardy sent',
                'code' => '208'
            ], 208);
        }
    }
    public function get_all_users_raiting(Request $request)
    {

        function splitName($name)
        {
            // Use the explode function to split the name by the comma
            $parts = explode(',', $name);

            // Check if the split resulted in exactly 2 parts
            if (count($parts) == 2) {
                // Return an associative array with firstName and lastName
                return [
                    'firstName' => trim($parts[0]),
                    'lastName'  => trim($parts[1])
                ];
            } else {
                // Handle the case where the input is not in the expected format
                return [
                    'firstName' => null,
                    'lastName'  => null
                ];
            }
        }

        $items = Rating::all();
        foreach ($items as $item) {
            $item['user_info'] = CustomerInfo::where('user_id', $item->uid)->first();

            $item['user_info']['user_fname'] = $item['user_info']->firstName;
            $item['user_info']['user_lname'] = $item['user_info']->lastName;
        }

        if ($items) {

            return rateResource::collection($items);
        } else {

            return response()->json([
                'message' => 'faild, aleardy sent',
                'code' => '208'
            ], 208);
        }
    }
}
