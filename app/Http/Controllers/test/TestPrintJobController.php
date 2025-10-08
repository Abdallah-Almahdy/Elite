<?php

namespace App\Http\Controllers\test;

use App\Models\User;
use App\Models\orderProduct;
use App\Jobs\CreatePrintJobs;
use App\Models\customer_info;
use App\Models\orderTracking;
use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\product;

class TestPrintJobController extends Controller
{
    public function testOrder()
    {
        // Pick a random user
        $user = User::first();
        if (!$user) {
            return response()->json(['error' => 'No user exists'], 404);
        }

        // Create dummy order
        $order = order::create([
            'user_id' => $user->id,
            'totalPrice' => 150,
            'address' => 1,
            'phoneNumber' => customer_info::where('user_id', $user->id)->first()->phonenum ?? '0000000000',
            'status' => 0,
            'payment_method' => 1,
        ]);

        // Create order tracking
        orderTracking::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'status' => 0,
        ]);

        // Attach 2–3 dummy products (make sure these product IDs actually exist)
        $products = product::whereHas('subSection.kitchens.printers')
            ->take(3)
            ->get();

        foreach ($products as $product) {
            orderProduct::create([
                'product_id' => $product->id,
                'order_id' => $order->id,
                'totalCount' => 1,
                'totalPrice' => $product->price,
            ]);
        }

        // Dispatch the background job
        CreatePrintJobs::dispatch($order);

        return response()->json([
            'message' => 'Test order created & print job dispatched',
            'order_id' => $order->id,
        ]);
    }
}
