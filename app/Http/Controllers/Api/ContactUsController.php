<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactUsRequest;
use App\Models\ContactUs;
use App\Traits\ApiTrait;

class ContactUsController extends Controller
{
    use ApiTrait;
    public function __invoke(ContactUsRequest $request)
    {
        $request->validated();

        $item = ContactUs::create([
                'message'  => $request->message,
                'reason_id' => $request->reason_id,
                'user_id', $request->user()->id,
        ]);

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
}
