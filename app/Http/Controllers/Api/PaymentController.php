<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nafezly\Payments\Classes\PaymobPayment;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $payment = new PaymobPayment();


        $response = $payment->pay($request->amount,1,$request->user_first_name,$request->user_last_name,$request->user_email,$request->user_phone);



        // Return URL as JSON instead of redirecting
        return response()->json([
            'iframe_url' => $response['redirect_url']
        ]);
    }

    public function callback(Request $request)
    {
        $payment = new PaymobPayment();
        $result = $payment->verify($request);

        if ($result['success']) {
            // Here you can update order status in DB
            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'transaction_id' => $result['payment_id']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }
}
