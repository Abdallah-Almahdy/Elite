<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Nafezly\Payments\Classes\PaymobPayment;

class PaymentController extends Controller
{
    public function pay($data)
    {


        $payment = new PaymobPayment();

        $response = $payment->pay($data['amount'], $data['user_id'], $data['user_first_name'], $data['user_last_name'], $data['user_email'], $data['user_phone']);



        // return response()->json([
        //     'iframe_url' => $response['redirect_url']
        // ]);

        return $response;
    }

    public function callback(Request $request)
    {
        $payment = new PaymobPayment();
        $result = $payment->verify($request);
        $paymentRecord = Payment::where('provider_payment_id', $result['payment_id'])->first();

        if ($paymentRecord) {
            $paymentRecord->update([
                'status' => $result['success'] ? 'paid' : 'failed',
                'response_data' => json_encode($result['process_data']),
            ]);


            if ($result['success']) {
                // Here you can update order status in DB


                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful',
                    'transaction_id' => $result['payment_id']
                ]);
            } else {

                $paymentRecord->order->update(['status' => 'fail']);
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        }
    }
}
